<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Services\AccountingService;

class AccountingController extends Controller
{
    protected $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['entries.account'])->latest()->paginate(10);
        return view('accounting::transactions.index', compact('transactions'));
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
            'type' => 'required|in:regular,opening,closing,collection,payment',
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
                    'type' => $validated['type']
                ],
                $validated['entries']
            );

            return redirect()->route('accounting.index')->with('success', 'Fiş başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => $e->getMessage()])->withInput();
        }
    }
}
