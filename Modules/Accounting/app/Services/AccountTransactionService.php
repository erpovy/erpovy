<?php

namespace Modules\Accounting\Services;

use Modules\Accounting\Models\AccountTransaction;
use Modules\Accounting\Models\Invoice;
use Modules\CRM\Models\Contact;
use Illuminate\Support\Facades\DB;

class AccountTransactionService
{
    /**
     * Cari hesap hareketi kaydet
     */
    public function recordTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            $contact = Contact::findOrFail($data['contact_id']);
            
            // Mevcut bakiyeyi hesapla
            $currentBalance = $this->calculateBalance($contact->id);
            
            // Yeni hareketi kaydet
            $transaction = AccountTransaction::create([
                'company_id' => $data['company_id'] ?? auth()->user()->company_id,
                'contact_id' => $data['contact_id'],
                'transaction_id' => $data['transaction_id'] ?? null,
                'invoice_id' => $data['invoice_id'] ?? null,
                'type' => $data['type'], // debit veya credit
                'amount' => $data['amount'],
                'description' => $data['description'] ?? null,
                'transaction_date' => $data['transaction_date'] ?? now(),
                'balance_after' => $this->calculateBalanceAfter($currentBalance, $data['type'], $data['amount']),
            ]);

            // Contact modelindeki current_balance'ı güncelle
            $contact->update([
                'current_balance' => $transaction->balance_after
            ]);

            return $transaction;
        });
    }

    /**
     * Faturadan otomatik cari hareket oluştur
     */
    public function syncFromInvoice(Invoice $invoice)
    {
        // Fatura için zaten hareket var mı kontrol et
        $existingTransaction = AccountTransaction::where('invoice_id', $invoice->id)->first();
        
        if ($existingTransaction) {
            return $existingTransaction;
        }

        // Fatura tipi: Satış faturası ise müşteri borçlanır (debit)
        // Alış faturası ise tedarikçi alacaklanır (credit)
        $type = $invoice->contact->type === 'customer' ? 'debit' : 'credit';

        return $this->recordTransaction([
            'company_id' => $invoice->company_id,
            'contact_id' => $invoice->contact_id,
            'invoice_id' => $invoice->id,
            'type' => $type,
            'amount' => $invoice->grand_total ?? $invoice->total_amount,
            'description' => "Fatura No: {$invoice->invoice_number}",
            'transaction_date' => $invoice->issue_date ?? now(),
        ]);
    }

    /**
     * Cari hesap ekstresi getir
     */
    public function getStatement($contactId, $startDate = null, $endDate = null)
    {
        $query = AccountTransaction::where('contact_id', $contactId)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('created_at', 'asc');

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        }

        return $query->get();
    }

    /**
     * Bakiye hesapla
     */
    public function calculateBalance($contactId)
    {
        $debits = AccountTransaction::where('contact_id', $contactId)
            ->where('type', 'debit')
            ->sum('amount');

        $credits = AccountTransaction::where('contact_id', $contactId)
            ->where('type', 'credit')
            ->sum('amount');

        return $debits - $credits;
    }

    /**
     * İşlem sonrası bakiye hesapla
     */
    protected function calculateBalanceAfter($currentBalance, $type, $amount)
    {
        if ($type === 'debit') {
            return $currentBalance + $amount;
        } else {
            return $currentBalance - $amount;
        }
    }

    /**
     * En yüksek borçlu müşterileri getir
     */
    public function getTopDebtors($limit = 5)
    {
        return Contact::where('type', 'customer')
            ->whereHas('accountTransactions')
            ->get()
            ->map(function ($contact) {
                $contact->calculated_balance = $this->calculateBalance($contact->id);
                return $contact;
            })
            ->filter(function ($contact) {
                return $contact->calculated_balance > 0; // Pozitif bakiye = borçlu
            })
            ->sortByDesc('calculated_balance')
            ->take($limit);
    }

    /**
     * En yüksek alacaklı tedarikçileri getir
     */
    public function getTopCreditors($limit = 5)
    {
        return Contact::where('type', 'vendor')
            ->whereHas('accountTransactions')
            ->get()
            ->map(function ($contact) {
                $contact->calculated_balance = $this->calculateBalance($contact->id);
                return $contact;
            })
            ->filter(function ($contact) {
                return $contact->calculated_balance < 0; // Negatif bakiye = alacaklı
            })
            ->sortBy('calculated_balance')
            ->take($limit);
    }
}
