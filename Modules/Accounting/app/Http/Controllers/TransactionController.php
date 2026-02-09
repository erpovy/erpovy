<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Services\AccountingService;

class TransactionController extends Controller
{
    protected $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::query();

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('receipt_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_approved', false);
            }
        }

        $transactions = $query->latest('date')->paginate(20)->withQueryString();

        // Calculate Stats
        $stats = [
            'total_count' => Transaction::count(),
            'approved_count' => Transaction::where('is_approved', true)->count(),
            'total_volume' => \Modules\Accounting\Models\LedgerEntry::sum('debit'),
            'monthly_count' => Transaction::whereMonth('date', now()->month)->count(),
        ];

        return view('accounting::transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::where('is_active', true)->get();
        return view('accounting::transactions.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'entries' => 'required|array|min:2',
            'entries.*.account_id' => 'required|exists:accounts,id',
            'entries.*.debit' => 'required|numeric|min:0',
            'entries.*.credit' => 'required|numeric|min:0',
        ]);

        try {
            $this->accountingService->createJournalEntry(
                [
                    'date' => $validated['date'],
                    'description' => $validated['description'],
                    'type' => 'regular'
                ],
                $validated['entries']
            );

            return redirect()->route('accounting.transactions.index')->with('success', 'Fiş başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => $e->getMessage()])->withInput();
        }
    }
}
