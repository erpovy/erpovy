<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Sales\Models\SalesReturn;
use Modules\Accounting\Models\Invoice;
use Carbon\Carbon;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesReturn::with(['invoice.contact']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhereHas('invoice', function($sq) use ($search) {
                      $sq->where('invoice_number', 'like', "%{$search}%")
                        ->orWhereHas('contact', function($ssq) use ($search) {
                            $ssq->where('name', 'like', "%{$search}%");
                        });
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->latest()->paginate(10);

        $stats = [
            'total_returns' => SalesReturn::count(),
            'pending_returns' => SalesReturn::where('status', 'Pending')->count(),
            'approved_returns' => SalesReturn::where('status', 'Approved')->count(),
            'total_refunded' => SalesReturn::where('status', 'Refunded')->sum('total_amount'),
        ];

        return view('sales::returns.index', compact('returns', 'stats'));
    }

    public function create()
    {
        $invoices = Invoice::with('contact')->latest()->take(100)->get();
        return view('sales::returns.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'return_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'status' => 'required|in:Pending,Approved,Rejected,Refunded',
            'notes' => 'nullable|string',
        ]);

        $validated['return_number'] = 'RET-' . strtoupper(uniqid());
        $validated['company_id'] = auth()->user()->company_id;

        SalesReturn::create($validated);

        return redirect()->route('sales.returns.index')
            ->with('success', 'Satış iade kaydı başarıyla oluşturuldu.');
    }

    public function show(SalesReturn $return)
    {
        return view('sales::returns.show', compact('return'));
    }

    public function edit(SalesReturn $return)
    {
        $invoices = Invoice::with('contact')->latest()->take(100)->get();
        return view('sales::returns.edit', compact('return', 'invoices'));
    }

    public function update(Request $request, SalesReturn $return)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'return_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'status' => 'required|in:Pending,Approved,Rejected,Refunded',
            'notes' => 'nullable|string',
        ]);

        $return->update($validated);

        return redirect()->route('sales.returns.index')
            ->with('success', 'Satış iade kaydı başarıyla güncellendi.');
    }

    public function destroy(SalesReturn $return)
    {
        $return->delete();
        return redirect()->route('sales.returns.index')
            ->with('success', 'Satış iade kaydı başarıyla silindi.');
    }
}
