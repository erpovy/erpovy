<?php

namespace Modules\Accounting\Services;

use Modules\Accounting\Models\CashBankAccount;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\Cheque;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashFlowService
{
    /**
     * Belirtilen gün sayısı için nakit akış öngörüsü hazırlar.
     */
    public function getForecastData(int $days = 30): array
    {
        $companyId = auth()->user()->company_id;
        $startDate = now()->startOfDay();
        $endDate = now()->addDays($days)->endOfDay();

        // 1. Mevcut Nakit ve Banka Bakiyesi
        $currentBalance = CashBankAccount::where('company_id', $companyId)
            ->active()
            ->sum('current_balance');

        $dailyForecast = [];
        $tempBalance = $currentBalance;

        // Günlük bazda iskelet oluştur
        for ($i = 0; $i <= $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $dailyForecast[$date] = [
                'date' => $date,
                'inflow' => 0,
                'outflow' => 0,
                'opening_balance' => 0,
                'closing_balance' => 0,
                'details' => []
            ];
        }

        // 2. Satış Faturaları (Alacaklar - Inflow) - Sadece açık olanlar (ödenmemiş kısım)
        $receivables = Invoice::where('company_id', $companyId)
            ->where('direction', 'out')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->where('status', '!=', 'paid') // Veya bakiye kontrolü
            ->get();

        foreach ($receivables as $invoice) {
            $date = $invoice->due_date->format('Y-m-d');
            if (isset($dailyForecast[$date])) {
                $dailyForecast[$date]['inflow'] += $invoice->grand_total; // Ödenmemiş bakiye mantığı eklenebilir
                $dailyForecast[$date]['details'][] = "Alacak: {$invoice->invoice_number} ({$invoice->contact->name})";
            }
        }

        // 3. Alış Faturaları (Borçlar - Outflow)
        $payables = Invoice::where('company_id', $companyId)
            ->where('direction', 'in')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->where('status', '!=', 'paid')
            ->get();

        foreach ($payables as $invoice) {
            $date = $invoice->due_date->format('Y-m-d');
            if (isset($dailyForecast[$date])) {
                $dailyForecast[$date]['outflow'] += $invoice->grand_total;
                $dailyForecast[$date]['details'][] = "Borç: {$invoice->invoice_number} ({$invoice->contact->name})";
            }
        }

        // 4. Çekler (Alınan/Verilen)
        $cheques = Cheque::where('company_id', $companyId)
            ->whereBetween('due_date', [$startDate, $endDate])
            ->whereIn('status', ['at_hand', 'portfolio']) // Henüz tahsil edilmemiş/ödenmemiş durumlar
            ->get();

        foreach ($cheques as $cheque) {
            $date = $cheque->due_date->format('Y-m-d');
            if (isset($dailyForecast[$date])) {
                if ($cheque->type === 'received') {
                    $dailyForecast[$date]['inflow'] += $cheque->amount;
                    $dailyForecast[$date]['details'][] = "Alınan Çek: {$cheque->cheque_number}";
                } else {
                    $dailyForecast[$date]['outflow'] += $cheque->amount;
                    $dailyForecast[$date]['details'][] = "Verilen Çek: {$cheque->cheque_number}";
                }
            }
        }

        // 5. Kümülatif Bakiye Hesaplama
        foreach ($dailyForecast as $date => &$data) {
            $data['opening_balance'] = $tempBalance;
            $data['closing_balance'] = $tempBalance + $data['inflow'] - $data['outflow'];
            $tempBalance = $data['closing_balance'];
        }

        return [
            'total_current_balance' => $currentBalance,
            'forecast' => array_values($dailyForecast),
            'min_balance' => min(array_column($dailyForecast, 'closing_balance')),
            'risk_days' => array_filter($dailyForecast, fn($d) => $d['closing_balance'] < 0)
        ];
    }
}
