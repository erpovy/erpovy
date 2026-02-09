<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = \Modules\CRM\Models\Lead::with('assignedUser')->latest();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leads = $query->paginate(15);
        return view('crm::leads.index', compact('leads'));
    }

    public function create()
    {
        $settings = auth()->user()->company->settings['crm_settings'] ?? [];
        $sourcesString = $settings['lead_source_options'] ?? 'Web Sitesi, Referans, Sosyal Medya, Doğrudan Satış';
        $sources = array_map('trim', explode(',', $sourcesString));
        
        $users = \App\Models\User::where('company_id', auth()->user()->company_id)->get();

        return view('crm::leads.create', compact('sources', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'status' => 'required|string|in:New,Contacted,Qualified,Lost,Won',
            'score' => 'integer|min:0|max:100',
        ]);

        $lead = new \Modules\CRM\Models\Lead($request->all());
        $lead->company_id = auth()->user()->company_id;
        $lead->save();

        return redirect()->route('crm.leads.index')->with('success', 'Potansiyel müşteri başarıyla oluşturuldu.');
    }
    public function show(\Modules\CRM\Models\Lead $lead)
    {
        return view('crm::leads.show', compact('lead'));
    }

    public function edit(\Modules\CRM\Models\Lead $lead)
    {
        $settings = auth()->user()->company->settings['crm_settings'] ?? [];
        $sourcesString = $settings['lead_source_options'] ?? 'Web Sitesi, Referans, Sosyal Medya, Doğrudan Satış';
        $sources = array_map('trim', explode(',', $sourcesString));
        
        $users = \App\Models\User::where('company_id', auth()->user()->company_id)->get();

        return view('crm::leads.edit', compact('lead', 'sources', 'users'));
    }

    public function update(Request $request, \Modules\CRM\Models\Lead $lead)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'status' => 'required|string|in:New,Contacted,Qualified,Lost,Won',
            'score' => 'integer|min:0|max:100',
        ]);

        $lead->update($request->all());

        return redirect()->route('crm.leads.index')->with('success', 'Potansiyel müşteri başarıyla güncellendi.');
    }
}
