<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Sales\Models\Subscription;
use Modules\CRM\Models\Contact;
use Modules\Inventory\Models\Product;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subscription::with(['contact', 'product']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('contact', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->latest()->paginate(15)->withQueryString();

        // Stats - Calculate MRR and Annual Volume by normalizing all billing intervals
        $activeSubscriptions = Subscription::where('status', 'active')->get();
        
        $mrr = $activeSubscriptions->sum(function($sub) {
            return match($sub->billing_interval) {
                'monthly' => $sub->price,
                'quarterly' => $sub->price / 3,
                'yearly' => $sub->price / 12,
                default => 0
            };
        });

        $annualVolume = $activeSubscriptions->sum(function($sub) {
            return match($sub->billing_interval) {
                'monthly' => $sub->price * 12,
                'quarterly' => $sub->price * 4,
                'yearly' => $sub->price,
                default => 0
            };
        });

        $stats = [
            'active_count' => $activeSubscriptions->count(),
            'mrr' => $mrr,
            'total_volume' => $annualVolume,
            'expired_count' => Subscription::where('status', 'expired')->count(),
        ];

        return view('sales::subscriptions.index', compact('subscriptions', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contacts = Contact::where('type', 'customer')->orderBy('name')->get();
        $products = Product::where('type', 'service')->orderBy('name')->get();
        return view('sales::subscriptions.create', compact('contacts', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'product_id' => 'nullable|exists:products,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'billing_interval' => 'required|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'status' => 'required|in:active,suspended,cancelled',
            'notes' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        
        // Simple next billing date calculation
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        if ($validated['billing_interval'] === 'monthly') {
            $validated['next_billing_date'] = $startDate->copy()->addMonth();
        } elseif ($validated['billing_interval'] === 'quarterly') {
            $validated['next_billing_date'] = $startDate->copy()->addMonths(3);
        } else {
            $validated['next_billing_date'] = $startDate->copy()->addYear();
        }

        Subscription::create($validated);

        return redirect()->route('sales.subscriptions.index')->with('success', 'Abonelik başarıyla oluşturuldu.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $subscription = Subscription::with(['contact', 'product'])->findOrFail($id);
        return view('sales::subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        $contacts = Contact::where('type', 'customer')->orderBy('name')->get();
        $products = Product::where('type', 'service')->orderBy('name')->get();
        return view('sales::subscriptions.edit', compact('subscription', 'contacts', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'product_id' => 'nullable|exists:products,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'billing_interval' => 'required|in:monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'status' => 'required|in:active,suspended,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Recalculate next billing date if interval or start date changes
        if ($subscription->billing_interval !== $validated['billing_interval'] || $subscription->start_date->format('Y-m-d') !== $validated['start_date']) {
            $startDate = \Carbon\Carbon::parse($validated['start_date']);
            if ($validated['billing_interval'] === 'monthly') {
                $validated['next_billing_date'] = $startDate->copy()->addMonth();
            } elseif ($validated['billing_interval'] === 'quarterly') {
                $validated['next_billing_date'] = $startDate->copy()->addMonths(3);
            } else {
                $validated['next_billing_date'] = $startDate->copy()->addYear();
            }
        }

        $subscription->update($validated);

        return redirect()->route('sales.subscriptions.show', $id)->with('success', 'Abonelik başarıyla güncellendi.');
    }

    /**
     * Toggle the status of the subscription.
     */
    public function toggleStatus($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->status = $subscription->status === 'active' ? 'suspended' : 'active';
        $subscription->save();

        return back()->with('success', 'Abonelik durumu güncellendi.');
    }

    /**
     * Create a manual invoice for the subscription.
     */
    public function createInvoice($id)
    {
        $subscription = Subscription::with('contact')->findOrFail($id);
        
        DB::transaction(function() use ($subscription) {
            $invoice = \Modules\Accounting\Models\Invoice::create([
                'company_id' => $subscription->company_id,
                'contact_id' => $subscription->contact_id,
                'invoice_number' => 'SUB-' . strtoupper(substr(uniqid(), -6)),
                'issue_date' => now(),
                'due_date' => now()->addDays(7),
                'total_amount' => $subscription->price,
                'tax_amount' => $subscription->price * 0.20, // Example 20% Tax
                'status' => 'pending',
                'notes' => $subscription->name . ' Abonelik Faturası',
            ]);

            $invoice->items()->create([
                'description' => $subscription->name . ' - ' . ucfirst($subscription->billing_interval) . ' Abonelik Bedeli',
                'quantity' => 1,
                'unit_price' => $subscription->price,
                'tax_rate' => 20,
                'total' => $subscription->price,
            ]);
        });

        return back()->with('success', 'Manuel fatura başarıyla oluşturuldu.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return redirect()->route('sales.subscriptions.index')->with('success', 'Abonelik başarıyla silindi.');
    }
}
