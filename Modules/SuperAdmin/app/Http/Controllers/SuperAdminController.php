<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Total Companies Stats
        $totalCompanies = \App\Models\Company::count();
        $lastMonthCompanies = \App\Models\Company::where('created_at', '<', now()->subMonth())->count();
        $companyGrowth = $lastMonthCompanies > 0 ? (($totalCompanies - $lastMonthCompanies) / $lastMonthCompanies) * 100 : 100;

        // 2. Monthly Revenue Stats (from Paid Invoices)
        $currentMonthRevenue = \Modules\Accounting\Models\Invoice::whereMonth('issue_date', now()->month)
            ->whereYear('issue_date', now()->year)
            ->where('status', 'paid')
            ->sum('total_amount');
        
        $lastMonthRevenue = \Modules\Accounting\Models\Invoice::whereMonth('issue_date', now()->subMonth()->month)
            ->whereYear('issue_date', now()->subMonth()->year)
            ->where('status', 'paid')
            ->sum('total_amount');
            
        $revenueGrowth = $lastMonthRevenue > 0 ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 100;

        // 3. Active Users Stats
        $totalUsers = \App\Models\User::count();
        $lastWeekUsers = \App\Models\User::where('created_at', '<', now()->subWeek())->count();
        $userGrowth = $lastWeekUsers > 0 ? (($totalUsers - $lastWeekUsers) / $lastWeekUsers) * 100 : 100;

        $stats = [
            'total_companies' => $totalCompanies,
            'company_growth' => round($companyGrowth),
            'monthly_revenue' => $currentMonthRevenue,
            'revenue_growth' => round($revenueGrowth),
            'total_users' => $totalUsers,
            'user_growth' => round($userGrowth),
            'active_companies' => \App\Models\Company::where('status', 'active')->count(),
            'db_size' => \Modules\SuperAdmin\Services\MetricService::getDatabaseSize(),
        ];

        // Fetch Real Recent Companies
        $recentCompanies = \App\Models\Company::latest()
            ->take(6)
            ->get()
            ->map(function($company) {
                // Determine plan based on settings or default to Starter
                $plan = $company->settings['plan'] ?? 'Starter';
                $init = collect(explode(' ', $company->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->join('');
                
                return [
                    'n' => $company->name,
                    'p' => ucfirst($plan),
                    't' => $company->created_at->diffForHumans(),
                    'bg' => 'bg-blue-500/20',
                    'c' => 'text-blue-400',
                    'init' => strtoupper($init),
                ];
            });

        // Get company locations for map
        $companyLocations = \App\Models\Company::all()
            ->map(function($company) {
                return $company->settings['city'] ?? null;
            })
            ->filter()
            ->countBy()
            ->map(function($count, $city) {
                return [
                    'city' => $city,
                    'count' => $count
                ];
            })
            ->values();

        // Real Activity Logs from System Events
        $latestUsers = \App\Models\User::latest()->take(5)->get()->map(function($user) {
            return [
                'causer' => 'Sistem',
                'action' => 'Yeni Kullanıcı Kaydı: ' . $user->name,
                'date' => $user->created_at->diffForHumans(),
                'timestamp' => $user->created_at->timestamp
            ];
        });

        $latestCompanies = \App\Models\Company::latest()->take(5)->get()->map(function($company) {
            return [
                'causer' => 'Sistem',
                'action' => 'Yeni Şirket Oluşturuldu: ' . $company->name,
                'date' => $company->created_at->diffForHumans(),
                'timestamp' => $company->created_at->timestamp
            ];
        });

        $recentLogs = $latestUsers->concat($latestCompanies)
            ->sortByDesc('timestamp')
            ->take(6)
            ->values();

        return view('superadmin::index', compact('stats', 'recentLogs', 'recentCompanies', 'companyLocations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('superadmin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('superadmin::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
