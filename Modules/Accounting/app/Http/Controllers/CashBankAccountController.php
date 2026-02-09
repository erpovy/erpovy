<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\CashBankAccount;
use Modules\Accounting\Models\CashBankTransaction;

class CashBankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;
        
        $accounts = CashBankAccount::where('company_id', $companyId)
            ->orderBy('type')
            ->orderBy('name')
            ->get();
        
        return view('accounting::cash-bank-accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounting::cash-bank-accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:cash,bank',
            'name' => 'required|string|max:255',
            'currency' => 'required|string|max:3',
            'opening_balance' => 'required|numeric',
            'bank_name' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:34',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['current_balance'] = $validated['opening_balance'];

        CashBankAccount::create($validated);

        return redirect()->route('accounting.cash-bank-accounts.index')
            ->with('success', 'Hesap başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $account = CashBankAccount::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        $transactions = CashBankTransaction::where('cash_bank_account_id', $id)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('accounting::cash-bank-accounts.show', compact('account', 'transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $account = CashBankAccount::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);
        
        return view('accounting::cash-bank-accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $account = CashBankAccount::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:34',
            'is_active' => 'boolean',
        ]);

        $account->update($validated);

        return redirect()->route('accounting.cash-bank-accounts.index')
            ->with('success', 'Hesap başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $account = CashBankAccount::where('company_id', auth()->user()->company_id)
            ->findOrFail($id);

        // Hareketli hesap silinemez
        if ($account->transactions()->count() > 0) {
            return redirect()->route('accounting.cash-bank-accounts.index')
                ->with('error', 'Hareketli hesap silinemez!');
        }

        $account->delete();

        return redirect()->route('accounting.cash-bank-accounts.index')
            ->with('success', 'Hesap başarıyla silindi.');
    }
}
