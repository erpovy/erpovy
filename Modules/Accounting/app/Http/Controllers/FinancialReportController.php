<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Services\FinancialReportService;

class FinancialReportController extends Controller
{
    /**
     * Rapor ana sayfası
     */
    public function index()
    {
        return view('accounting::reports.index');
    }

    /**
     * Gelir Tablosu
     */
    public function incomeStatement(Request $request)
    {
        $reportService = new FinancialReportService();
        $companyId = auth()->user()->company_id;
        
        // Varsayılan tarih aralığı: Bu ay
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        
        $data = $reportService->getIncomeStatement($companyId, $startDate, $endDate);
        
        return view('accounting::reports.income-statement', $data);
    }

    /**
     * Bilanço
     */
    public function balanceSheet(Request $request)
    {
        $reportService = new FinancialReportService();
        $companyId = auth()->user()->company_id;
        
        // Varsayılan tarih: Bugün
        $asOfDate = $request->as_of_date ?? now()->format('Y-m-d');
        
        $data = $reportService->getBalanceSheet($companyId, $asOfDate);
        
        return view('accounting::reports.balance-sheet', $data);
    }

    /**
     * Mizan
     */
    public function trialBalance(Request $request)
    {
        $reportService = new FinancialReportService();
        $companyId = auth()->user()->company_id;
        
        // Varsayılan tarih aralığı: Bu ay
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        
        $data = $reportService->getTrialBalance($companyId, $startDate, $endDate);
        
        return view('accounting::reports.trial-balance', $data);
    }

    /**
     * KDV Beyannamesi
     */
    public function vatDeclaration(Request $request)
    {
        $reportService = new FinancialReportService();
        $companyId = auth()->user()->company_id;
        
        // Varsayılan tarih aralığı: Bu ay
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        
        $data = $reportService->getVatDeclaration($companyId, $startDate, $endDate);
        
        return view('accounting::reports.vat-declaration', $data);
    }
}
