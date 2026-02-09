<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\PromissoryNote;
use Modules\Accounting\Models\ChequeNoteTransaction;
use Modules\CRM\Models\Contact;
use Modules\Accounting\Models\CashBankAccount;

class PromissoryNoteController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;
        
        $query = PromissoryNote::where('company_id', $companyId)
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
        
        $notes = $query->orderBy('due_date')->paginate(20);
        
        return view('accounting::promissory-notes.index', compact('notes'));
    }

    public function create()
    {
        $companyId = auth()->user()->company_id;
        $contacts = Contact::where('company_id', $companyId)->orderBy('name')->get();
        $cashBankAccounts = CashBankAccount::where('company_id', $companyId)
                                          ->where('is_active', true)
                                          ->orderBy('name')
                                          ->get();
        
        return view('accounting::promissory-notes.create', compact('contacts', 'cashBankAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:received,issued',
            'note_number' => 'required|string|max:255',
            'drawer' => 'required|string|max:255',
            'endorser' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'place_of_issue' => 'nullable|string|max:255',
            'place_of_payment' => 'nullable|string|max:255',
            'contact_id' => 'nullable|exists:contacts,id',
            'notes' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['status'] = 'portfolio';

        $note = PromissoryNote::create($validated);

        // İlk hareket kaydı
        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'promissory_note',
            'transaction_id' => $note->id,
            'action' => $validated['type'] === 'received' ? 'received' : 'issued',
            'transaction_date' => $validated['issue_date'],
            'amount' => $validated['amount'],
            'contact_id' => $validated['contact_id'] ?? null,
            'description' => 'Senet ' . ($validated['type'] === 'received' ? 'alındı' : 'verildi'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('accounting.promissory-notes.show', $note)
                        ->with('success', 'Senet başarıyla oluşturuldu.');
    }

    public function show($id)
    {
        $companyId = auth()->user()->company_id;
        
        $note = PromissoryNote::where('company_id', $companyId)
                              ->with(['contact', 'cashBankAccount', 'transactions.creator'])
                              ->findOrFail($id);
                              
        $contacts = Contact::where('company_id', $companyId)->orderBy('name')->get();
        $cashBankAccounts = CashBankAccount::where('company_id', $companyId)
                                          ->where('is_active', true)
                                          ->orderBy('name')
                                          ->get();
        
        return view('accounting::promissory-notes.show', compact('note', 'contacts', 'cashBankAccounts'));
    }

    public function edit($id)
    {
        $note = PromissoryNote::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        if ($note->status !== 'portfolio') {
            return redirect()->route('accounting.promissory-notes.show', $note)
                           ->with('error', 'Sadece portföydeki senetler düzenlenebilir.');
        }
        
        $companyId = auth()->user()->company_id;
        $contacts = Contact::where('company_id', $companyId)->orderBy('name')->get();
        
        return view('accounting::promissory-notes.edit', compact('note', 'contacts'));
    }

    public function update(Request $request, $id)
    {
        $note = PromissoryNote::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        if ($note->status !== 'portfolio') {
            return redirect()->route('accounting.promissory-notes.show', $note)
                           ->with('error', 'Sadece portföydeki senetler düzenlenebilir.');
        }

        $validated = $request->validate([
            'note_number' => 'required|string|max:255',
            'drawer' => 'required|string|max:255',
            'endorser' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'place_of_issue' => 'nullable|string|max:255',
            'place_of_payment' => 'nullable|string|max:255',
            'contact_id' => 'nullable|exists:contacts,id',
            'notes' => 'nullable|string',
        ]);

        $note->update($validated);

        return redirect()->route('accounting.promissory-notes.show', $note)
                        ->with('success', 'Senet başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $note = PromissoryNote::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        if ($note->status !== 'portfolio') {
            return redirect()->route('accounting.promissory-notes.index')
                           ->with('error', 'Sadece portföydeki senetler silinebilir.');
        }

        $note->transactions()->delete();
        $note->delete();

        return redirect()->route('accounting.promissory-notes.index')
                        ->with('success', 'Senet başarıyla silindi.');
    }

    public function deposit(Request $request, $id)
    {
        $note = PromissoryNote::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        if ($note->status !== 'portfolio') {
            return back()->with('error', 'Bu senet zaten işleme alınmış.');
        }

        $validated = $request->validate([
            'cash_bank_account_id' => 'required|exists:cash_bank_accounts,id',
            'transaction_date' => 'required|date',
        ]);

        $note->update([
            'status' => 'deposited',
            'cash_bank_account_id' => $validated['cash_bank_account_id'],
        ]);

        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'promissory_note',
            'transaction_id' => $note->id,
            'action' => 'deposited',
            'transaction_date' => $validated['transaction_date'],
            'amount' => $note->amount,
            'cash_bank_account_id' => $validated['cash_bank_account_id'],
            'description' => 'Senet bankaya yatırıldı',
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Senet bankaya yatırıldı.');
    }

    public function cash(Request $request, $id)
    {
        $note = PromissoryNote::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        if (!in_array($note->status, ['portfolio', 'deposited'])) {
            return back()->with('error', 'Bu senet tahsil edilemez.');
        }

        $validated = $request->validate([
            'cash_bank_account_id' => 'required|exists:cash_bank_accounts,id',
            'transaction_date' => 'required|date',
        ]);

        $note->update([
            'status' => 'cashed',
            'cash_bank_account_id' => $validated['cash_bank_account_id'],
        ]);

        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'promissory_note',
            'transaction_id' => $note->id,
            'action' => 'cashed',
            'transaction_date' => $validated['transaction_date'],
            'amount' => $note->amount,
            'cash_bank_account_id' => $validated['cash_bank_account_id'],
            'description' => 'Senet tahsil edildi',
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Senet tahsil edildi.');
    }

    public function transfer(Request $request, $id)
    {
        $note = PromissoryNote::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        if ($note->status !== 'portfolio') {
            return back()->with('error', 'Sadece portföydeki senetler ciro edilebilir.');
        }

        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'transaction_date' => 'required|date',
            'endorser' => 'required|string|max:255',
        ]);

        $note->update([
            'status' => 'transferred',
            'endorser' => $validated['endorser'],
        ]);

        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'promissory_note',
            'transaction_id' => $note->id,
            'action' => 'transferred',
            'transaction_date' => $validated['transaction_date'],
            'amount' => $note->amount,
            'contact_id' => $validated['contact_id'],
            'description' => 'Senet ciro edildi: ' . $validated['endorser'],
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Senet ciro edildi.');
    }

    public function protest(Request $request, $id)
    {
        $note = PromissoryNote::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        $validated = $request->validate([
            'transaction_date' => 'required|date',
        ]);

        $note->update(['status' => 'protested']);

        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'promissory_note',
            'transaction_id' => $note->id,
            'action' => 'protested',
            'transaction_date' => $validated['transaction_date'],
            'amount' => $note->amount,
            'description' => 'Senet protesto edildi',
            'created_by' => auth()->id(),
        ]);

        return back()->with('warning', 'Senet protesto edildi.');
    }

    public function cancel(Request $request, $id)
    {
        $note = PromissoryNote::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        $validated = $request->validate([
            'transaction_date' => 'required|date',
        ]);

        $note->update(['status' => 'cancelled']);

        ChequeNoteTransaction::create([
            'company_id' => auth()->user()->company_id,
            'transaction_type' => 'promissory_note',
            'transaction_id' => $note->id,
            'action' => 'cancelled',
            'transaction_date' => $validated['transaction_date'],
            'amount' => $note->amount,
            'description' => 'Senet iptal edildi',
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Senet iptal edildi.');
    }
}
