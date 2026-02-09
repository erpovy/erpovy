<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = \Modules\CRM\Models\Contract::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhereHas('contact', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contracts = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => \Modules\CRM\Models\Contract::count(),
            'active' => \Modules\CRM\Models\Contract::where('status', 'Active')->count(),
            'draft' => \Modules\CRM\Models\Contract::where('status', 'Draft')->count(),
            'expired' => \Modules\CRM\Models\Contract::where('status', 'Expired')->count(),
        ];

        return view('crm::contracts.index', compact('contracts', 'stats'));
    }

    public function create()
    {
        $contacts = \Modules\CRM\Models\Contact::all();
        $deals = \Modules\CRM\Models\Deal::all();
        return view('crm::contracts.create', compact('contacts', 'deals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'contact_id' => 'required|exists:contacts,id',
            'value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        \Modules\CRM\Models\Contract::create($validated);

        return redirect()->route('crm.contracts.index')->with('success', 'Sözleşme başarıyla oluşturuldu.');
    }

    public function show(\Modules\CRM\Models\Contract $contract)
    {
        return view('crm::contracts.show', compact('contract'));
    }

    public function edit(\Modules\CRM\Models\Contract $contract)
    {
        $contacts = \Modules\CRM\Models\Contact::all();
        $deals = \Modules\CRM\Models\Deal::all();
        return view('crm::contracts.edit', compact('contract', 'contacts', 'deals'));
    }

    public function update(Request $request, \Modules\CRM\Models\Contract $contract)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'contact_id' => 'required|exists:contacts,id',
            'value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        $contract->update($validated);

        return redirect()->route('crm.contracts.index')->with('success', 'Sözleşme güncellendi.');
    }

    public function destroy(\Modules\CRM\Models\Contract $contract)
    {
        $contract->delete();
        return redirect()->route('crm.contracts.index')->with('success', 'Sözleşme silindi.');
    }
}
