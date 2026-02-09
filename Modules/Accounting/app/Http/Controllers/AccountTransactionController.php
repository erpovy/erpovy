<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\AccountTransaction;
use Modules\Accounting\Services\AccountTransactionService;
use Modules\CRM\Models\Contact;

class AccountTransactionController extends Controller
{
    protected $service;

    public function __construct(AccountTransactionService $service)
    {
        $this->service = $service;
    }

    /**
     * Cari hesap listesi
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;
        
        // Tüm müşteri ve tedarikçileri getir
        $contacts = Contact::where('company_id', $companyId)
            ->whereIn('type', ['customer', 'vendor'])
            ->with('accountTransactions')
            ->get()
            ->map(function ($contact) {
                $contact->calculated_balance = $this->service->calculateBalance($contact->id);
                $contact->debit_total = $contact->accountTransactions->where('type', 'debit')->sum('amount');
                $contact->credit_total = $contact->accountTransactions->where('type', 'credit')->sum('amount');
                $contact->last_transaction_date = $contact->accountTransactions->max('transaction_date');
                return $contact;
            })
            ->sortByDesc('calculated_balance');

        return view('accounting::account-transactions.index', compact('contacts'));
    }

    /**
     * Cari hesap ekstresi
     */
    public function show(Request $request, $contactId)
    {
        $contact = Contact::with(['accountTransactions' => function ($query) {
            $query->orderBy('transaction_date', 'asc')->orderBy('created_at', 'asc');
        }])->findOrFail($contactId);

        // Tarih filtresi
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $transactions = $this->service->getStatement($contactId, $startDate, $endDate);

        // Toplam hesaplamalar
        $totalDebit = $transactions->where('type', 'debit')->sum('amount');
        $totalCredit = $transactions->where('type', 'credit')->sum('amount');
        $balance = $totalDebit - $totalCredit;

        return view('accounting::account-transactions.show', compact(
            'contact',
            'transactions',
            'totalDebit',
            'totalCredit',
            'balance',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Manuel cari hareket girişi formu
     */
    public function create()
    {
        $companyId = auth()->user()->company_id;
        $contacts = Contact::where('company_id', $companyId)
            ->whereIn('type', ['customer', 'vendor'])
            ->orderBy('name')
            ->get();

        return view('accounting::account-transactions.create', compact('contacts'));
    }

    /**
     * Manuel cari hareket kaydetme
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
        ]);

        try {
            $this->service->recordTransaction([
                'company_id' => auth()->user()->company_id,
                'contact_id' => $validated['contact_id'],
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'description' => $validated['description'],
                'transaction_date' => $validated['transaction_date'],
            ]);

            return redirect()
                ->route('accounting.account-transactions.show', $validated['contact_id'])
                ->with('success', 'Cari hareket başarıyla kaydedildi.');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['msg' => 'Hata: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
