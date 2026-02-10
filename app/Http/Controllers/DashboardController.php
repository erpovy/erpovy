<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Accounting\Models\Invoice;
use Modules\CRM\Models\Contact;
use Modules\Inventory\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Services\WeatherService;
use Modules\Accounting\Models\CashBankTransaction;

class DashboardController extends Controller
{
    protected $weatherService;
    protected $activityService;

    public function __construct(\App\Services\WeatherService $weatherService, \App\Services\ActivityService $activityService)
    {
        $this->weatherService = $weatherService;
        $this->activityService = $activityService;
    }

    public function index()
    {
        // Monthly Revenue (Cash Inflow)
        $monthlyRevenue = CashBankTransaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->where('type', 'income')
            ->sum('amount');

        // Pending Invoices (Accounting)
        $pendingInvoicesCount = Invoice::whereNotIn('status', ['paid', 'cancelled'])->count();

        // Total Contacts (CRM)
        $totalContacts = Contact::count();

        // Low Stock Products (Inventory) 
        // We use a collection filter since 'stock' is an accessor
        $lowStockProducts = Product::all()->filter(function($product) {
            return $product->stock < 10;
        })->count();

        // Recent Invoices with Contact relation
        $recentInvoices = Invoice::with('contact')->latest('issue_date')->take(5)->get();

        // Recent Contacts
        $recentContacts = Contact::latest()->take(5)->get();

        // --- Activity Feed Logic ---
        $activities = $this->activityService->getActivities(10);

        // --- Chart Data (Last 6 Months Revenue) ---
        $chartData = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            // 'M' format gives short month name (Jan, Feb, etc.). 
            // In Turkish locale with translatedFormat, it should give Oca, Şuban, etc.
            $monthName = $date->translatedFormat('M'); 
            
            $revenue = CashBankTransaction::whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->where('type', 'income')
                ->sum('amount');
            
            $chartData->push([
                'month' => $monthName,
                'revenue' => $revenue
            ]);
        }
        
        // Calculate percentages for bar heights
        $maxRevenue = $chartData->max('revenue');
        $maxRevenue = $maxRevenue > 0 ? $maxRevenue : 1; // Prevent division by zero
        
        $chartData = $chartData->map(function ($item) use ($maxRevenue) {
            $item['percentage'] = round(($item['revenue'] / $maxRevenue) * 100);
            return $item;
        });

        // Get weather data
        $weather = null;
        $city = auth()->user()->company?->getCity();
        if ($city) {
            $weather = $this->weatherService->getWeather($city);
        }

        return view('dashboard', compact(
            'monthlyRevenue',
            'pendingInvoicesCount',
            'totalContacts',
            'lowStockProducts',
            'recentInvoices',
            'recentContacts',
            'activities',
            'chartData',
            'weather'
        ));
    }
}
