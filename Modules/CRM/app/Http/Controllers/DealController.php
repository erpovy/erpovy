<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRM\Models\Deal;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with(['lead', 'contact', 'assignedUser'])->get();
        // Group deals by stage for Kanban
        $dealsByStage = [
            'new' => $deals->where('stage', 'new'),
            'negotiation' => $deals->where('stage', 'negotiation'),
            'proposal' => $deals->where('stage', 'proposal'),
            'won' => $deals->where('stage', 'won'),
            'lost' => $deals->where('stage', 'lost'),
        ];

        return view('crm::deals.index', compact('dealsByStage'));
    }

    public function create()
    {
        // This might be handled via modal in index, but keeping for fallback
        return view('crm::deals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'contact_id' => 'nullable|exists:contacts,id',
            'lead_id' => 'nullable|exists:leads,id',
            'stage' => 'required|string',
            'probability' => 'integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['assigned_to'] = auth()->id();

        Deal::create($validated);

        return redirect()->route('crm.deals.index')->with('success', 'Fırsat başarıyla oluşturuldu.');
    }

    public function update(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'stage' => 'required|string',
            'probability' => 'integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $deal->update($validated);

        return redirect()->route('crm.deals.index')->with('success', 'Fırsat güncellendi.');
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();
        return redirect()->route('crm.deals.index')->with('success', 'Fırsat silindi.');
    }

    public function updateStage(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'stage' => 'required|string',
        ]);

        $deal->update(['stage' => $validated['stage']]);

        return response()->json(['success' => true, 'message' => 'Aşama güncellendi.']);
    }
}
