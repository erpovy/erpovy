<?php

namespace Modules\Purchasing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRM\Models\Contact;
use Modules\Purchasing\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;
        
        $suppliers = Contact::where('company_id', $companyId)
            ->where('type', 'vendor')
            ->withCount(['purchaseOrders as total_orders'])
            ->withSum('purchaseOrders as total_spent', 'total_amount')
            ->orderBy('total_spent', 'desc')
            ->paginate(15);

        return view('purchasing::suppliers.index', compact('suppliers'));
    }

    public function show(Contact $supplier)
    {
        if ($supplier->company_id !== auth()->user()->company_id || $supplier->type !== 'vendor') {
            abort(403);
        }

        $orders = PurchaseOrder::where('supplier_id', $supplier->id)
            ->latest()
            ->paginate(10);
            
        return view('purchasing::suppliers.show', compact('supplier', 'orders'));
    }
}
