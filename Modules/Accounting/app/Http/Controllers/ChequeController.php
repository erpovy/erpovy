<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Cheque;
use Modules\Accounting\Models\ChequeNoteTransaction;
use Modules\CRM\Models\Contact;
use Modules\Accounting\Models\CashBankAccount;

class ChequeController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;
        
        $query = Cheque::where('company_id', $companyId)
                      ->with(['contact', 'cashBankAccount']);
        
        // Filtreleme
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('due_date_from')) {
            $query->where('due_date', '>=', $request->due_date_from);
        }
        
        if ($request->filled('due_date_to')) {
            $query->where('due_date', '<=', $request->due_date_to);
        }
        
        $cheques = $query->orderBy('due_date')->paginate(20);
        
        return view('accounting::cheques.index', compact('cheques'));
    }

    public function create()
    {
        $companyId = auth()->user()->company_id;
        $contacts = Contact::where('company_id', $companyId)->orderBy('name')->get();
        $cashBankAccounts = CashBankAccount::where('company_id', $companyId)
                                          ->where('is_active', true)
                                          ->orderBy('name')
                                          ->get();
        
        return view('accounting::cheques.create', compact('contacts', 'cashBankAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:received,issued',
            'cheque_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'drawer' => 'required|string|max:255',
            'endorser' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'contact_id' => 'nullable|exists:contacts,id',
            'notes' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['status'] = 'portfolio';

        $cheque = Cheque::create($validated);

        // İlk hareket kaydı
        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'cheque',
            'transaction_id' => $cheque->id,
            'action' => $validated['type'] === 'received' ? 'received' : 'issued',
            'transaction_date' => $validated['issue_date'],
            'amount' => $validated['amount'],
            'contact_id' => $validated['contact_id'] ?? null,
            'description' => 'Çek ' . ($validated['type'] === 'received' ? 'alındı' : 'verildi'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('accounting.cheques.show', $cheque)
                        ->with('success', 'Çek başarıyla oluşturuldu.');
    }

    public function show($id)
    {
        $companyId = auth()->user()->company_id;
        
        $cheque = Cheque::where('company_id', $companyId)
                       ->with(['contact', 'cashBankAccount', 'transactions.creator'])
                       ->findOrFail($id);
                       
        $contacts = Contact::where('company_id', $companyId)->orderBy('name')->get();
        $cashBankAccounts = CashBankAccount::where('company_id', $companyId)
                                          ->where('is_active', true)
                                          ->orderBy('name')
                                          ->get();
        
        return view('accounting::cheques.show', compact('cheque', 'contacts', 'cashBankAccounts'));
    }

    public function edit($id)
    {
        $cheque = Cheque::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        // Sadece portföydeki çekler düzenlenebilir
        if ($cheque->status !== 'portfolio') {
            return redirect()->route('accounting.cheques.show', $cheque)
                           ->with('error', 'Sadece portföydeki çekler düzenlenebilir.');
        }
        
        $companyId = auth()->user()->company_id;
        $contacts = Contact::where('company_id', $companyId)->orderBy('name')->get();
        
        return view('accounting::cheques.edit', compact('cheque', 'contacts'));
    }

    public function update(Request $request, $id)
    {
        $cheque = Cheque::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        if ($cheque->status !== 'portfolio') {
            return redirect()->route('accounting.cheques.show', $cheque)
                           ->with('error', 'Sadece portföydeki çekler düzenlenebilir.');
        }

        $validated = $request->validate([
            'cheque_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'drawer' => 'required|string|max:255',
            'endorser' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'contact_id' => 'nullable|exists:contacts,id',
            'notes' => 'nullable|string',
        ]);

        $cheque->update($validated);

        return redirect()->route('accounting.cheques.show', $cheque)
                        ->with('success', 'Çek başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $cheque = Cheque::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        if ($cheque->status !== 'portfolio') {
            return redirect()->route('accounting.cheques.index')
                           ->with('error', 'Sadece portföydeki çekler silinebilir.');
        }

        $cheque->transactions()->delete();
        $cheque->delete();

        return redirect()->route('accounting.cheques.index')
                        ->with('success', 'Çek başarıyla silindi.');
    }

    public function deposit(Request $request, $id)
    {
        $cheque = Cheque::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        if ($cheque->status !== 'portfolio') {
            return back()->with('error', 'Bu çek zaten işleme alınmış.');
        }

        $validated = $request->validate([
            'cash_bank_account_id' => 'required|exists:cash_bank_accounts,id',
            'transaction_date' => 'required|date',
        ]);

        $cheque->update([
            'status' => 'deposited',
            'cash_bank_account_id' => $validated['cash_bank_account_id'],
        ]);

        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'cheque',
            'transaction_id' => $cheque->id,
            'action' => 'deposited',
            'transaction_date' => $validated['transaction_date'],
            'amount' => $cheque->amount,
            'cash_bank_account_id' => $validated['cash_bank_account_id'],
            'description' => 'Çek bankaya yatırıldı',
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Çek bankaya yatırıldı.');
    }

    public function cash(Request $request, $id)
    {
        $cheque = Cheque::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        if (!in_array($cheque->status, ['portfolio', 'deposited'])) {
            return back()->with('error', 'Bu çek tahsil edilemez.');
        }

        $validated = $request->validate([
            'cash_bank_account_id' => 'required|exists:cash_bank_accounts,id',
            'transaction_date' => 'required|date',
        ]);

        $cheque->update([
            'status' => 'cashed',
            'cash_bank_account_id' => $validated['cash_bank_account_id'],
        ]);

        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'cheque',
            'transaction_id' => $cheque->id,
            'action' => 'cashed',
            'transaction_date' => $validated['transaction_date'],
            'amount' => $cheque->amount,
            'cash_bank_account_id' => $validated['cash_bank_account_id'],
            'description' => 'Çek tahsil edildi',
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Çek tahsil edildi.');
    }

    public function transfer(Request $request, $id)
    {
        $cheque = Cheque::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        if ($cheque->status !== 'portfolio') {
            return back()->with('error', 'Sadece portföydeki çekler ciro edilebilir.');
        }

        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'transaction_date' => 'required|date',
            'endorser' => 'required|string|max:255',
        ]);

        $cheque->update([
            'status' => 'transferred',
            'endorser' => $validated['endorser'],
        ]);

        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'cheque',
            'transaction_id' => $cheque->id,
            'action' => 'transferred',
            'transaction_date' => $validated['transaction_date'],
            'amount' => $cheque->amount,
            'contact_id' => $validated['contact_id'],
            'description' => 'Çek ciro edildi: ' . $validated['endorser'],
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Çek ciro edildi.');
    }

    public function bounce(Request $request, $id)
    {
        $cheque = Cheque::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        $validated = $request->validate([
            'transaction_date' => 'required|date',
        ]);

        $cheque->update(['status' => 'bounced']);

        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'cheque',
            'transaction_id' => $cheque->id,
            'action' => 'bounced',
            'transaction_date' => $validated['transaction_date'],
            'amount' => $cheque->amount,
            'description' => 'Çek karşılıksız çıktı',
            'created_by' => auth()->id(),
        ]);

        return back()->with('warning', 'Çek karşılıksız olarak işaretlendi.');
    }

    public function cancel(Request $request, $id)
    {
        $cheque = Cheque::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        $validated = $request->validate([
            'transaction_date' => 'required|date',
        ]);

        $cheque->update(['status' => 'cancelled']);

        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'cheque',
            'transaction_id' => $cheque->id,
            'action' => 'cancelled',
            'transaction_date' => $validated['transaction_date'],
            'amount' => $cheque->amount,
            'description' => 'Çek iptal edildi',
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Çek iptal edildi.');
    }
}
