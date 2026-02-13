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

        $pendingQuotesCount = Quote::where('company_id', $companyId)
            ->where('status', 'pending')
            ->count();

        $activeSubscriptionsCount = Subscription::where('company_id', $companyId)
            ->where('status', 'active')
            ->count();

        $activeRentalsCount = Rental::where('company_id', $companyId)
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
            'pendingQuotesCount',
            'activeSubscriptionsCount',
            'activeRentalsCount',
            'monthlySales',
            'quoteStages',
            'recentSales',
            'upcomingBillings'
        ));
    }
}
