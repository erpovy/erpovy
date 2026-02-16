<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\FiscalPeriod;

class FiscalPeriodController extends Controller
{
    public function index()
    {
        $periods = FiscalPeriod::where('company_id', auth()->user()->company_id)->get();
        return view('accounting::fiscal-periods.index', compact('periods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        FiscalPeriod::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'open',
        ]);

        return back()->with('success', 'Mali dönem başarıyla oluşturuldu.');
    }

    public function open(FiscalPeriod $fiscalPeriod)
    {
        $fiscalPeriod->update(['status' => 'open']);
        return back()->with('success', 'Mali dönem açıldı.');
    }

    public function close(FiscalPeriod $fiscalPeriod)
    {
        $fiscalPeriod->update(['status' => 'closed']);
        return back()->with('success', 'Mali dönem kapatıldı.');
    }
}
