<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Invoice;
use Modules\Sales\Models\Quote;
use Modules\Sales\Models\Subscription;
use Modules\Sales\Models\Rental;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;

        // Temel Metrikler
        $totalSalesAmount = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->sum('total_amount');

        // Aylık Karşılaştırma (Büyüme Oranı)
        $thisMonthSales = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereMonth('issue_date', now()->month)
            ->whereYear('issue_date', now()->year)
            ->sum('total_amount');

        $lastMonthSales = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->whereMonth('issue_date', now()->subMonth()->month)
            ->whereYear('issue_date', now()->subMonth()->year)
            ->sum('total_amount');

        $salesGrowth = $lastMonthSales > 0 ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        $pendingInvoicesAmount = Invoice::where('company_id', $companyId)
            ->whereIn('status', ['unpaid', 'partially_paid', 'sent'])
            ->sum('total_amount');

        $pendingQuotesCount = Quote::where('company_id', $companyId)
            ->where('status', 'pending')
            ->count();

        $activeSubscriptionsCount = Subscription::where('company_id', $companyId)
            ->where('status', 'active')
            ->count();

        // Aylık Satış Trendi (Son 6 Ay)
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $monthFormat = $isSqlite ? "strftime('%Y-%m', issue_date)" : "DATE_FORMAT(issue_date, '%Y-%m')";

        $monthlySales = Invoice::where('company_id', $companyId)
            ->where('status', 'paid')
            ->where('issue_date', '>=', now()->subMonths(6))
            ->select(
                DB::raw("$monthFormat as month"), 
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // En Çok Satan Ürünler (Top 5)
        $topProducts = DB::table('invoice_items')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->where('invoices.company_id', $companyId)
            ->where('invoices.status', 'paid')
            ->select('description as name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_revenue'))
            ->groupBy('description')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // Teklif Durum Dağılımı
        $quoteStages = Quote::where('company_id', $companyId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Son Satışlar
        $recentSales = Invoice::where('company_id', $companyId)
            ->with('contact')
            ->latest()
            ->take(5)
            ->get();

        // Yaklaşan Abonelik Tahsilatları
        $upcomingBillings = Subscription::where('company_id', $companyId)
            ->where('status', 'active')
            ->where('next_billing_date', '>=', now())
            ->orderBy('next_billing_date')
            ->take(5)
            ->get();

        return view('sales::dashboard', compact(
            'totalSalesAmount',
            'thisMonthSales',
            'salesGrowth',
            'pendingInvoicesAmount',
            'pendingQuotesCount',
            'activeSubscriptionsCount',
            'monthlySales',
            'topProducts',
            'quoteStages',
            'recentSales',
            'upcomingBillings'
        ));
    }
}
