<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Modules\CRM\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contact::query();
        $type = $request->query('type', 'customer');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Type Filter (if explicitly provided, otherwise default to context)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        } else {
            $query->where('type', $type);
        }

        $contacts = $query->latest()->paginate(20)->withQueryString();

        // Calculate Stats
        $stats = [
            'total' => Contact::count(),
            'customers' => Contact::where('type', 'customer')->count(),
            'vendors' => Contact::where('type', 'vendor')->count(),
            'total_balance' => Contact::sum('current_balance'),
        ];

        return view('crm::index', compact('contacts', 'type', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('crm::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:customer,vendor,lead',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:20',
            'tax_office' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $contact = new Contact($validated);
        $contact->company_id = auth()->user()->company_id;
        $contact->save();

        return redirect()->route('crm.contacts.index', ['type' => $validated['type']])
            ->with('success', 'Kayıt başarıyla oluşturuldu.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $contact = Contact::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('crm::show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contact = Contact::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('crm::edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contact = Contact::where('company_id', auth()->user()->company_id)->findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:customer,vendor,lead',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:20',
            'tax_office' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $contact->update($validated);

        return redirect()->route('crm.contacts.index', ['type' => $validated['type']])
            ->with('success', 'Kayıt başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contact = Contact::where('company_id', auth()->user()->company_id)->findOrFail($id);
        $contact->delete();
        
        return redirect()->route('crm.contacts.index', ['type' => $contact->type])
            ->with('success', 'Kayıt başarıyla silindi.');
    }
}
