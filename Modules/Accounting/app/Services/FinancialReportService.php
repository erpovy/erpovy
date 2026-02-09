<?php

namespace Modules\Accounting\Services;

use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\LedgerEntry;
use Modules\Accounting\Models\Transaction;
use Illuminate\Support\Facades\DB;

class FinancialReportService
{
    /**
     * Gelir Tablosu (Income Statement)
     * Gelir/Gider analizi ve Net Kar/Zarar hesaplama
     */
    public function getIncomeStatement($companyId, $startDate, $endDate)
    {
        // Gelirleri hesapla (6xx hesaplar)
        $revenues = Account::where('company_id', $companyId)
            ->where('code', 'LIKE', '6%')
            ->where('is_active', true)
            ->get()
            ->map(function($account) use ($startDate, $endDate) {
                $ledgerEntries = LedgerEntry::where('account_id', $account->id)
                    ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [$startDate, $endDate])
                          ->where('is_approved', true);
                    })
                    ->get();
                
                $credit = $ledgerEntries->sum('credit');
                $debit = $ledgerEntries->sum('debit');
                $amount = $credit - $debit;
                
                return [
                    'code' => $account->code,
                    'name' => $account->name,
                    'amount' => $amount,
                ];
            })
            ->filter(function($item) {
                return $item['amount'] != 0;
            })
            ->values();

        // Giderleri hesapla (7xx hesaplar)
        $expenses = Account::where('company_id', $companyId)
            ->where('code', 'LIKE', '7%')
            ->where('is_active', true)
            ->get()
            ->map(function($account) use ($startDate, $endDate) {
                $ledgerEntries = LedgerEntry::where('account_id', $account->id)
                    ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [$startDate, $endDate])
                          ->where('is_approved', true);
                    })
                    ->get();
                
                $debit = $ledgerEntries->sum('debit');
                $credit = $ledgerEntries->sum('credit');
                $amount = $debit - $credit;
                
                return [
                    'code' => $account->code,
                    'name' => $account->name,
                    'amount' => $amount,
                ];
            })
            ->filter(function($item) {
                return $item['amount'] != 0;
            })
            ->values();

        // Net Kar/Zarar
        $totalRevenue = $revenues->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $netIncome = $totalRevenue - $totalExpense;

        return [
            'revenues' => $revenues,
            'expenses' => $expenses,
            'total_revenue' => $totalRevenue,
            'total_expense' => $totalExpense,
            'net_income' => $netIncome,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    /**
     * Bilanço (Balance Sheet)
     * Aktif/Pasif dengesi
     */
    public function getBalanceSheet($companyId, $asOfDate)
    {
        // Aktifleri hesapla (Varlıklar)
        $assets = Account::where('company_id', $companyId)
            ->where('type', 'asset')
            ->where('is_active', true)
            ->get()
            ->map(function($account) use ($asOfDate) {
                $ledgerEntries = LedgerEntry::where('account_id', $account->id)
                    ->whereHas('transaction', function($q) use ($asOfDate) {
                        $q->where('date', '<=', $asOfDate)
                          ->where('is_approved', true);
                    })
                    ->get();
                
                $debit = $ledgerEntries->sum('debit');
                $credit = $ledgerEntries->sum('credit');
                $balance = $debit - $credit;
                
                return [
                    'code' => $account->code,
                    'name' => $account->name,
                    'balance' => $balance,
                ];
            })
            ->filter(function($item) {
                return $item['balance'] != 0;
            })
            ->values();

        // Pasifleri hesapla (Kaynaklar)
        $liabilities = Account::where('company_id', $companyId)
            ->where('type', 'liability')
            ->where('is_active', true)
            ->get()
            ->map(function($account) use ($asOfDate) {
                $ledgerEntries = LedgerEntry::where('account_id', $account->id)
                    ->whereHas('transaction', function($q) use ($asOfDate) {
                        $q->where('date', '<=', $asOfDate)
                          ->where('is_approved', true);
                    })
                    ->get();
                
                $debit = $ledgerEntries->sum('debit');
                $credit = $ledgerEntries->sum('credit');
                $balance = $credit - $debit;
                
                return [
                    'code' => $account->code,
                    'name' => $account->name,
                    'balance' => $balance,
                ];
            })
            ->filter(function($item) {
                return $item['balance'] != 0;
            })
            ->values();

        // Özkaynakları hesapla
        $equity = Account::where('company_id', $companyId)
            ->where('type', 'equity')
            ->where('is_active', true)
            ->get()
            ->map(function($account) use ($asOfDate) {
                $ledgerEntries = LedgerEntry::where('account_id', $account->id)
                    ->whereHas('transaction', function($q) use ($asOfDate) {
                        $q->where('date', '<=', $asOfDate)
                          ->where('is_approved', true);
                    })
                    ->get();
                
                $debit = $ledgerEntries->sum('debit');
                $credit = $ledgerEntries->sum('credit');
                $balance = $credit - $debit;
                
                return [
                    'code' => $account->code,
                    'name' => $account->name,
                    'balance' => $balance,
                ];
            })
            ->filter(function($item) {
                return $item['balance'] != 0;
            })
            ->values();

        $totalAssets = $assets->sum('balance');
        $totalLiabilities = $liabilities->sum('balance');
        $totalEquity = $equity->sum('balance');

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'total_assets' => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'total_equity' => $totalEquity,
            'total_liabilities_equity' => $totalLiabilities + $totalEquity,
            'as_of_date' => $asOfDate,
        ];
    }

    /**
     * Mizan (Trial Balance)
     * Tüm hesapların borç/alacak toplamları
     */
    public function getTrialBalance($companyId, $startDate, $endDate)
    {
        $accounts = Account::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get()
            ->map(function($account) use ($startDate, $endDate) {
                $ledgerEntries = LedgerEntry::where('account_id', $account->id)
                    ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('date', [$startDate, $endDate])
                          ->where('is_approved', true);
                    })
                    ->get();
                
                $debit = $ledgerEntries->sum('debit');
                $credit = $ledgerEntries->sum('credit');
                
                // Bakiye hesaplama (hesap tipine göre)
                $balance = $this->calculateBalance($account->type, $debit, $credit);
                
                return [
                    'code' => $account->code,
                    'name' => $account->name,
                    'type' => $account->type,
                    'debit' => $debit,
                    'credit' => $credit,
                    'balance' => $balance,
                ];
            })
            ->filter(function($item) {
                return $item['debit'] > 0 || $item['credit'] > 0;
            })
            ->values();

        $totalDebit = $accounts->sum('debit');
        $totalCredit = $accounts->sum('credit');

        return [
            'accounts' => $accounts,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    /**
     * KDV Beyannamesi
     * Hesaplanan/İndirilecek/Ödenecek KDV
     */
    public function getVatDeclaration($companyId, $startDate, $endDate)
    {
        // Hesaplanan KDV (Satışlardan - 391)
        $calculatedVat = LedgerEntry::whereHas('account', function($q) use ($companyId) {
                $q->where('company_id', $companyId)
                  ->where('code', '391');
            })
            ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate])
                  ->where('is_approved', true);
            })
            ->sum('credit');

        // İndirilecek KDV (Alışlardan - 191)
        $deductibleVat = LedgerEntry::whereHas('account', function($q) use ($companyId) {
                $q->where('company_id', $companyId)
                  ->where('code', '191');
            })
            ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate])
                  ->where('is_approved', true);
            })
            ->sum('debit');

        // Ödenecek/Devreden KDV
        $payableVat = $calculatedVat - $deductibleVat;

        return [
            'calculated_vat' => $calculatedVat,
            'deductible_vat' => $deductibleVat,
            'payable_vat' => $payableVat,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    /**
     * Helper: Bakiye hesaplama
     */
    private function calculateBalance($accountType, $debit, $credit)
    {
        if (in_array($accountType, ['asset', 'expense'])) {
            return $debit - $credit;
        }
        
        return $credit - $debit;
    }
}
