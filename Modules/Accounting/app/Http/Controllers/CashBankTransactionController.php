<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\CashBankAccount;
use Modules\Accounting\Models\CashBankTransaction;
use Modules\Accounting\Services\CashBankService;
use Modules\CRM\Models\Contact;

class CashBankTransactionController extends Controller
{
    protected $cashBankService;

    public function __construct(CashBankService $cashBankService)
    {
        $this->cashBankService = $cashBankService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;
        
        $transactions = CashBankTransaction::where('company_id', $companyId)
            ->with(['account', 'contact'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('accounting::cash-bank.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $companyId = auth()->user()->company_id;
        
        $accounts = CashBankAccount::where('company_id', $companyId)
            ->where('is_active', true)
            ->get();
        
        $contacts = Contact::where('company_id', $companyId)->get();
        
        $type = $request->get('type', 'collection'); // collection, payment, transfer, general
        
        return view('accounting::cash-bank.transactions.create', compact('accounts', 'contacts', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $type = $request->input('transaction_type');
        
        try {
            switch ($type) {
                case 'collection':
                    $this->cashBankService->recordCollection($request->all());
                    $message = 'Tahsilat başarıyla kaydedildi.';
                    break;
                
                case 'payment':
                    $this->cashBankService->recordPayment($request->all());
                    $message = 'Ödeme başarıyla kaydedildi.';
                    break;
                
                case 'transfer':
                    $this->cashBankService->recordTransfer($request->all());
                    $message = 'Virman başarıyla kaydedildi.';
                    break;
                
                case 'general':
                    $this->cashBankService->recordGeneralTransaction($request->all());
                    $message = 'İşlem başarıyla kaydedildi.';
                    break;
                
                default:
                    return redirect()->back()->with('error', 'Geçersiz işlem tipi!');
            }
            
            return redirect()->route('accounting.cash-bank-transactions.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = CashBankTransaction::where('company_id', auth()->user()->company_id)
            ->with(['account', 'contact', 'accountingTransaction'])
            ->findOrFail($id);
        
        return view('accounting::cash-bank.transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Kasa/Banka hareketleri genelde düzenlenmez
        return redirect()->route('accounting.cash-bank-transactions.index')
            ->with('error', 'Kasa/Banka hareketleri düzenlenemez!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Kasa/Banka hareketleri genelde düzenlenmez
        return redirect()->route('accounting.cash-bank-transactions.index')
            ->with('error', 'Kasa/Banka hareketleri düzenlenemez!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Kasa/Banka hareketleri genelde silinmez
        // Muhasebe fişi ve cari hareket ile bağlantılı olduğu için
        return redirect()->route('accounting.cash-bank-transactions.index')
            ->with('error', 'Kasa/Banka hareketleri silinemez!');
    }
}
