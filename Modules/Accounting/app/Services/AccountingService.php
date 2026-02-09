<?php

namespace Modules\Accounting\Services;

use Illuminate\Support\Facades\DB;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\FiscalPeriod;
use Exception;

class AccountingService
{
    /**
     * Create a new Journal Entry.
     * 
     * @param array $data Transaction header data
     * @param array $entries Array of entries [['account_id', 'debit', 'credit', 'description']]
     * @return Transaction
     * @throws Exception
     */
    public function createJournalEntry(array $data, array $entries): Transaction
    {
        $totalDebit = collect($entries)->sum('debit');
        $totalCredit = collect($entries)->sum('credit');

        if (abs($totalDebit - $totalCredit) > 0.001) {
            throw new Exception("Fiş dengesiz. Borç: $totalDebit, Alacak: $totalCredit");
        }

        return DB::transaction(function () use ($data, $entries) {
            // Find active fiscal period if not provided
            if (!isset($data['fiscal_period_id'])) {
                $period = FiscalPeriod::where('status', 'open')
                    ->whereDate('start_date', '<=', $data['date'])
                    ->whereDate('end_date', '>=', $data['date'])
                    ->firstOrFail();
                $data['fiscal_period_id'] = $period->id;
            }

            $transaction = Transaction::create([
                'fiscal_period_id' => $data['fiscal_period_id'],
                'type' => $data['type'] ?? 'regular',
                'receipt_number' => $data['receipt_number'] ?? $this->generateReceiptNumber(),
                'date' => $data['date'],
                'description' => $data['description'] ?? null,
                'is_approved' => true, // Auto approve for now
            ]);

            foreach ($entries as $entry) {
                $transaction->entries()->create([
                    'account_id' => $entry['account_id'],
                    'debit' => $entry['debit'],
                    'credit' => $entry['credit'],
                    'description' => $entry['description'] ?? null,
                ]);
            }

            return $transaction;
        });
    }

    private function generateReceiptNumber(): string
    {
        // Simple generation logic, can be improved
        return 'TRX-' . time();
    }
}
