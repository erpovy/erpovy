<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Services\CashFlowService;

class CashFlowController extends Controller
{
    protected $cashFlowService;

    public function __construct(CashFlowService $cashFlowService)
    {
        $this->cashFlowService = $cashFlowService;
    }

    public function index(Request $request)
    {
        $days = $request->get('days', 30);
        $data = $this->cashFlowService->getForecastData($days);
        
        return view('accounting::cash-flow.index', compact('data', 'days'));
    }
}
