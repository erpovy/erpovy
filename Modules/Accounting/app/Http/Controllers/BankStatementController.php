<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Services\Mt940ParserService;
use Modules\Accounting\Models\CashBankAccount;
use Modules\Accounting\Models\CashBankTransaction;
use Modules\CRM\Models\Contact;
use Illuminate\Support\Facades\Storage;

class BankStatementController extends Controller
{
    protected $parser;

    public function __construct(Mt940ParserService $parser)
    {
        $this->parser = $parser;
    }

    public function index()
    {
        $bankAccounts = CashBankAccount::bank()->active()->get();
        return view('accounting::bank-statements.index', compact('bankAccounts'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'bank_account_id' => 'required|exists:cash_bank_accounts,id',
            'statement_file' => 'required|file'
        ]);

        $content = file_get_contents($request->file('statement_file')->getRealPath());
        $transactions = $this->parser->parse($content);

        // Geçici olarak session'da sakla (Reconcile ekranı için)
        session(['pending_statement_transactions' => $transactions]);
        session(['pending_bank_account_id' => $request->bank_account_id]);

        return redirect()->route('accounting.bank-statements.reconcile');
    }

    public function reconcile()
    {
        $transactions = session('pending_statement_transactions', []);
        $bankAccountId = session('pending_bank_account_id');
        
        if (empty($transactions)) {
            return redirect()->route('accounting.bank-statements.index')->with('error', 'İşlenecek veri bulunamadı.');
        }

        $bankAccount = CashBankAccount::find($bankAccountId);
        $contacts = Contact::all();

        return view('accounting::bank-statements.reconcile', compact('transactions', 'bankAccount', 'contacts'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'transactions' => 'required|array',
            'transactions.*.contact_id' => 'nullable|exists:contacts,id',
            'transactions.*.process' => 'nullable|boolean'
        ]);

        $bankAccountId = session('pending_bank_account_id');
        $bankAccount = CashBankAccount::findOrFail($bankAccountId);
        $pendingTransactions = session('pending_statement_transactions', []);
        
        $cashBankService = app(\Modules\Accounting\Services\CashBankService::class);
        $count = 0;

        foreach ($request->transactions as $index => $input) {
            if (!isset($input['process']) || !$input['process']) continue;

            $txData = $pendingTransactions[$index];
            $contactId = $input['contact_id'] ?? null;

            if ($contactId) {
                // Cari ile eşleşen bir işlem (Tahsilat veya Ödeme)
                if ($txData['type'] === 'income') {
                    $cashBankService->recordCollection([
                        'cash_bank_account_id' => $bankAccount->id,
                        'contact_id' => $contactId,
                        'amount' => $txData['amount'],
                        'method' => 'transfer',
                        'transaction_date' => $txData['date'],
                        'description' => "Banka Entegrasyonu (MT940): " . $txData['description'],
                    ]);
                } else {
                    try {
                        $cashBankService->recordPayment([
                            'cash_bank_account_id' => $bankAccount->id,
                            'contact_id' => $contactId,
                            'amount' => $txData['amount'],
                            'method' => 'transfer',
                            'transaction_date' => $txData['date'],
                            'description' => "Banka Entegrasyonu (MT940): " . $txData['description'],
                        ]);
                    } catch (\Exception $e) {
                        // Bakiye yetersiz vs. hatası olursa geçelim veya loglayalım
                        continue;
                    }
                }
            } else {
                // Cari olmayan genel işlem
                $cashBankService->recordGeneralTransaction([
                    'cash_bank_account_id' => $bankAccount->id,
                    'type' => $txData['type'],
                    'amount' => $txData['amount'],
                    'method' => 'transfer',
                    'transaction_date' => $txData['date'],
                    'description' => "Banka Entegrasyonu (Genel): " . $txData['description'],
                ]);
            }
            $count++;
        }

        session()->forget(['pending_statement_transactions', 'pending_bank_account_id']);

        return redirect()->route('accounting.bank-statements.index')->with('success', "{$count} banka hareketi başarıyla işlendi.");
    }
}
