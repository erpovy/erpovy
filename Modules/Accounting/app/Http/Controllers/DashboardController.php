<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\LedgerEntry;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Services\AccountTransactionService;
use Modules\CRM\Models\Contact;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Toplam Gelir (Gelir hesaplarındaki net alacak bakiyesi)
        $totalIncome = LedgerEntry::whereHas('account', function($query) {
                $query->where('type', 'income');
            })
            ->select(DB::raw('SUM(credit) - SUM(debit) as balance'))
            ->first()->balance ?? 0;

        // Toplam Gider (Gider hesaplarındaki net borç bakiyesi)
        $totalExpense = LedgerEntry::whereHas('account', function($query) {
                $query->where('type', 'expense');
            })
            ->select(DB::raw('SUM(debit) - SUM(credit) as balance'))
            ->first()->balance ?? 0;

        $netProfit = $totalIncome - $totalExpense;

        // Bekleyen Tahsilatlar (Pending Invoices) - Added for V2 Dashboard
        $pendingInvoicesCount = Invoice::where('status', 'pending')->count();

        // Son Fişler
        $recentTransactions = Transaction::with(['entries.account'])
            ->latest('date')
            ->take(5)
            ->get();

        // Aylık Rapor (Son 6 Ay)
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $dateFormat = $isSqlite ? "strftime('%Y-%m', transactions.date)" : "DATE_FORMAT(transactions.date, '%Y-%m')";

        $monthlyReport = LedgerEntry::whereHas('account', function($query) {
                $query->whereIn('type', ['income', 'expense']);
            })
            ->join('transactions', 'ledger_entries.transaction_id', '=', 'transactions.id')
            ->join('accounts', 'ledger_entries.account_id', '=', 'accounts.id')
            ->select(
                DB::raw("$dateFormat as month"),
                DB::raw("SUM(CASE WHEN accounts.type = 'income' THEN ledger_entries.credit - ledger_entries.debit ELSE 0 END) as income"),
                DB::raw("SUM(CASE WHEN accounts.type = 'expense' THEN ledger_entries.debit - ledger_entries.credit ELSE 0 END) as expense")
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get()
            ->reverse();

        // Recent Invoices (Shared with main dashboard logic)
        $recentInvoices = Invoice::with('contact')
            ->where('company_id', auth()->user()->company_id)
            ->latest()
            ->take(5)
            ->get();

        // Cash/Bank Balances (Sum of liquid assets)
        $liquidBalance = LedgerEntry::whereHas('account', function($query) {
                $query->whereIn('code', ['100', '102']); // Assuming 100 is Cash, 102 is Bank in TR accounting
            })
            ->select(DB::raw('SUM(debit) - SUM(credit) as balance'))
            ->first()->balance ?? 0;

        // Cari Hesap Özetleri
        $accountTransactionService = app(AccountTransactionService::class);
        $topDebtors = $accountTransactionService->getTopDebtors(5);
        $topCreditors = $accountTransactionService->getTopCreditors(5);

        // Toplam Alacak/Borç
        $totalReceivables = Contact::where('company_id', auth()->user()->company_id)
            ->where('type', 'customer')
            ->where('current_balance', '>', 0)
            ->sum('current_balance');

        $totalPayables = Contact::where('company_id', auth()->user()->company_id)
            ->where('type', 'vendor')
            ->where('current_balance', '>', 0)
            ->sum('current_balance');

        return view('accounting::dashboard', compact(
            'totalIncome',
            'totalExpense',
            'netProfit',
            'pendingInvoicesCount',
            'recentTransactions',
            'monthlyReport',
            'recentInvoices',
            'liquidBalance',
            'topDebtors',
            'topCreditors',
            'totalReceivables',
            'totalPayables'
        ));
    }
}
