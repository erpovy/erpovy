<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRM\Models\Lead;
use Modules\CRM\Models\Deal;
use Modules\CRM\Models\Contact;
use Modules\CRM\Models\Contract;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;

        // Temel Metrikler
        $totalLeads = Lead::where('company_id', $companyId)->count();
        $activeDeals = Deal::where('company_id', $companyId)
            ->whereNotIn('stage', ['Closed Won', 'Closed Lost'])
            ->count();
        $totalWonAmount = Deal::where('company_id', $companyId)
            ->where('stage', 'Closed Won')
            ->sum('amount');
        $totalContacts = Contact::where('company_id', $companyId)->count();

        // Anlaşma Aşamaları Dağılımı (Grafik için)
        $dealStages = Deal::where('company_id', $companyId)
            ->select('stage', DB::raw('count(*) as total'))
            ->groupBy('stage')
            ->get();

        // Haftalık Yeni Adaylar (Grafik için)
        $weeklyLeads = Lead::where('company_id', $companyId)
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Son Anlaşmalar
        $recentDeals = Deal::where('company_id', $companyId)
            ->with(['contact', 'lead'])
            ->latest()
            ->take(5)
            ->get();

        // Son Kayıtlar (Adaylar)
        $recentLeads = Lead::where('company_id', $companyId)
            ->latest()
            ->take(5)
            ->get();

        return view('crm::dashboard', compact(
            'totalLeads',
            'activeDeals',
            'totalWonAmount',
            'totalContacts',
            'dealStages',
            'weeklyLeads',
            'recentDeals',
            'recentLeads'
        ));
    }
}
