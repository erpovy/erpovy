<?php

namespace Modules\Accounting\Services;

use Modules\Accounting\Models\CashBankAccount;
use Modules\Accounting\Models\CashBankTransaction;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\LedgerEntry;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\FiscalPeriod;
use Modules\Accounting\Services\AccountTransactionService;
use Modules\CRM\Models\Contact;
use Illuminate\Support\Facades\DB;

class CashBankService
{
    protected $accountTransactionService;

    public function __construct(AccountTransactionService $accountTransactionService)
    {
        $this->accountTransactionService = $accountTransactionService;
    }

    /**
     * Tahsilat kaydı (Müşteriden para alma)
     */
    public function recordCollection(array $data)
    {
        return DB::transaction(function () use ($data) {
            $companyId = auth()->user()->company_id;
            $account = CashBankAccount::findOrFail($data['cash_bank_account_id']);
            $contact = Contact::findOrFail($data['contact_id']);

            // 1. Kasa/Banka hareketini kaydet
            $cashBankTransaction = CashBankTransaction::create([
                'company_id' => $companyId,
                'cash_bank_account_id' => $account->id,
                'contact_id' => $contact->id,
                'type' => 'income',
                'method' => $data['method'],
                'amount' => $data['amount'],
                'transaction_date' => $data['transaction_date'],
                'description' => $data['description'] ?? "Tahsilat - {$contact->name}",
                'reference_number' => $data['reference_number'] ?? null,
                'balance_after' => $account->current_balance + $data['amount'],
            ]);

            // 2. Kasa/Banka bakiyesini güncelle
            $account->increment('current_balance', $data['amount']);

            // 3. Cari hesap hareketi oluştur (müşteri alacaklanır - borcu azalır)
            $this->accountTransactionService->recordTransaction([
                'company_id' => $companyId,
                'contact_id' => $contact->id,
                'type' => 'credit',
                'amount' => $data['amount'],
                'description' => "Tahsilat - {$this->getMethodLabel($data['method'])}",
                'transaction_date' => $data['transaction_date'],
            ]);

            // 4. Muhasebe fişi oluştur
            $fiscalPeriod = FiscalPeriod::where('company_id', $companyId)
                ->where('status', 'open')
                ->first();

            if ($fiscalPeriod) {
                $transaction = Transaction::create([
                    'company_id' => $companyId,
                    'fiscal_period_id' => $fiscalPeriod->id,
                    'type' => 'regular',
                    'receipt_number' => 'THS-' . strtoupper(uniqid()),
                    'date' => $data['transaction_date'],
                    'description' => "Tahsilat: {$contact->name}",
                    'is_approved' => true,
                ]);

                // Borç: Kasa/Banka (100 veya 102)
                $cashBankAccountCode = $account->type === 'cash' ? '100' : '102';
                $cashBankLedgerAccount = Account::where('company_id', $companyId)
                    ->where('code', $cashBankAccountCode)
                    ->first() ?? Account::where('code', $cashBankAccountCode)->first();

                LedgerEntry::create([
                    'company_id' => $companyId,
                    'transaction_id' => $transaction->id,
                    'account_id' => $cashBankLedgerAccount->id,
                    'debit' => $data['amount'],
                    'credit' => 0,
                    'description' => 'Tahsilat',
                ]);

                // Alacak: Alıcılar (120)
                $receivablesAccount = Account::where('company_id', $companyId)
                    ->where('code', '120')
                    ->first() ?? Account::where('code', '120')->first();

                LedgerEntry::create([
                    'company_id' => $companyId,
                    'transaction_id' => $transaction->id,
                    'account_id' => $receivablesAccount->id,
                    'debit' => 0,
                    'credit' => $data['amount'],
                    'description' => "Tahsilat - {$contact->name}",
                ]);

                // Link transaction to cash bank transaction
                $cashBankTransaction->update(['transaction_id' => $transaction->id]);
            }

            return $cashBankTransaction;
        });
    }

    /**
     * Ödeme kaydı (Tedarikçiye para verme)
     */
    public function recordPayment(array $data)
    {
        return DB::transaction(function () use ($data) {
            $companyId = auth()->user()->company_id;
            $account = CashBankAccount::findOrFail($data['cash_bank_account_id']);
            $contact = Contact::findOrFail($data['contact_id']);

            // Bakiye kontrolü
            if ($account->current_balance < $data['amount']) {
                throw new \Exception('Yetersiz bakiye!');
            }

            // 1. Kasa/Banka hareketini kaydet
            $cashBankTransaction = CashBankTransaction::create([
                'company_id' => $companyId,
                'cash_bank_account_id' => $account->id,
                'contact_id' => $contact->id,
                'type' => 'expense',
                'method' => $data['method'],
                'amount' => $data['amount'],
                'transaction_date' => $data['transaction_date'],
                'description' => $data['description'] ?? "Ödeme - {$contact->name}",
                'reference_number' => $data['reference_number'] ?? null,
                'balance_after' => $account->current_balance - $data['amount'],
            ]);

            // 2. Kasa/Banka bakiyesini güncelle
            $account->decrement('current_balance', $data['amount']);

            // 3. Cari hesap hareketi oluştur (tedarikçi borçlanır - alacağı azalır)
            $this->accountTransactionService->recordTransaction([
                'company_id' => $companyId,
                'contact_id' => $contact->id,
                'type' => 'debit',
                'amount' => $data['amount'],
                'description' => "Ödeme - {$this->getMethodLabel($data['method'])}",
                'transaction_date' => $data['transaction_date'],
            ]);

            // 4. Muhasebe fişi oluştur
            $fiscalPeriod = FiscalPeriod::where('company_id', $companyId)
                ->where('status', 'open')
                ->first();

            if ($fiscalPeriod) {
                $transaction = Transaction::create([
                    'company_id' => $companyId,
                    'fiscal_period_id' => $fiscalPeriod->id,
                    'type' => 'regular',
                    'receipt_number' => 'ODM-' . strtoupper(uniqid()),
                    'date' => $data['transaction_date'],
                    'description' => "Ödeme: {$contact->name}",
                    'is_approved' => true,
                ]);

                // Borç: Satıcılar (320)
                $payablesAccount = Account::where('company_id', $companyId)
                    ->where('code', '320')
                    ->first() ?? Account::where('code', '320')->first();

                LedgerEntry::create([
                    'company_id' => $companyId,
                    'transaction_id' => $transaction->id,
                    'account_id' => $payablesAccount->id,
                    'debit' => $data['amount'],
                    'credit' => 0,
                    'description' => "Ödeme - {$contact->name}",
                ]);

                // Alacak: Kasa/Banka (100 veya 102)
                $cashBankAccountCode = $account->type === 'cash' ? '100' : '102';
                $cashBankLedgerAccount = Account::where('company_id', $companyId)
                    ->where('code', $cashBankAccountCode)
                    ->first() ?? Account::where('code', $cashBankAccountCode)->first();

                LedgerEntry::create([
                    'company_id' => $companyId,
                    'transaction_id' => $transaction->id,
                    'account_id' => $cashBankLedgerAccount->id,
                    'debit' => 0,
                    'credit' => $data['amount'],
                    'description' => 'Ödeme',
                ]);

                // Link transaction
                $cashBankTransaction->update(['transaction_id' => $transaction->id]);
            }

            return $cashBankTransaction;
        });
    }

    /**
     * Virman kaydı (Hesaplar arası transfer)
     */
    public function recordTransfer(array $data)
    {
        return DB::transaction(function () use ($data) {
            $companyId = auth()->user()->company_id;
            $sourceAccount = CashBankAccount::findOrFail($data['source_account_id']);
            $targetAccount = CashBankAccount::findOrFail($data['target_account_id']);

            // Bakiye kontrolü
            if ($sourceAccount->current_balance < $data['amount']) {
                throw new \Exception('Kaynak hesapta yetersiz bakiye!');
            }

            // 1. Kaynak hesaptan çıkış
            $sourceTransaction = CashBankTransaction::create([
                'company_id' => $companyId,
                'cash_bank_account_id' => $sourceAccount->id,
                'type' => 'transfer',
                'method' => 'transfer',
                'amount' => $data['amount'],
                'transaction_date' => $data['transaction_date'],
                'description' => "Virman - {$targetAccount->name}'e",
                'target_account_id' => $targetAccount->id,
                'balance_after' => $sourceAccount->current_balance - $data['amount'],
            ]);

            $sourceAccount->decrement('current_balance', $data['amount']);

            // 2. Hedef hesaba giriş
            $targetTransaction = CashBankTransaction::create([
                'company_id' => $companyId,
                'cash_bank_account_id' => $targetAccount->id,
                'type' => 'transfer',
                'method' => 'transfer',
                'amount' => $data['amount'],
                'transaction_date' => $data['transaction_date'],
                'description' => "Virman - {$sourceAccount->name}'den",
                'target_account_id' => $sourceAccount->id,
                'balance_after' => $targetAccount->current_balance + $data['amount'],
            ]);

            $targetAccount->increment('current_balance', $data['amount']);

            // 3. Muhasebe fişi oluştur
            $fiscalPeriod = FiscalPeriod::where('company_id', $companyId)
                ->where('status', 'open')
                ->first();

            if ($fiscalPeriod) {
                $transaction = Transaction::create([
                    'company_id' => $companyId,
                    'fiscal_period_id' => $fiscalPeriod->id,
                    'type' => 'regular',
                    'receipt_number' => 'VRM-' . strtoupper(uniqid()),
                    'date' => $data['transaction_date'],
                    'description' => "Virman: {$sourceAccount->name} → {$targetAccount->name}",
                    'is_approved' => true,
                ]);

                // Borç: Hedef hesap
                $targetAccountCode = $targetAccount->type === 'cash' ? '100' : '102';
                $targetLedgerAccount = Account::where('company_id', $companyId)
                    ->where('code', $targetAccountCode)
                    ->first() ?? Account::where('code', $targetAccountCode)->first();

                LedgerEntry::create([
                    'company_id' => $companyId,
                    'transaction_id' => $transaction->id,
                    'account_id' => $targetLedgerAccount->id,
                    'debit' => $data['amount'],
                    'credit' => 0,
                    'description' => "Virman - {$targetAccount->name}",
                ]);

                // Alacak: Kaynak hesap
                $sourceAccountCode = $sourceAccount->type === 'cash' ? '100' : '102';
                $sourceLedgerAccount = Account::where('company_id', $companyId)
                    ->where('code', $sourceAccountCode)
                    ->first() ?? Account::where('code', $sourceAccountCode)->first();

                LedgerEntry::create([
                    'company_id' => $companyId,
                    'transaction_id' => $transaction->id,
                    'account_id' => $sourceLedgerAccount->id,
                    'debit' => 0,
                    'credit' => $data['amount'],
                    'description' => "Virman - {$sourceAccount->name}",
                ]);

                // Link transactions
                $sourceTransaction->update(['transaction_id' => $transaction->id]);
                $targetTransaction->update(['transaction_id' => $transaction->id]);
            }

            return ['source' => $sourceTransaction, 'target' => $targetTransaction];
        });
    }

    /**
     * Genel gider/gelir kaydı (Cari olmayan)
     */
    public function recordGeneralTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            $companyId = auth()->user()->company_id;
            $account = CashBankAccount::findOrFail($data['cash_bank_account_id']);

            // Gider ise bakiye kontrolü
            if ($data['type'] === 'expense' && $account->current_balance < $data['amount']) {
                throw new \Exception('Yetersiz bakiye!');
            }

            // Bakiye hesapla
            $balanceChange = $data['type'] === 'income' ? $data['amount'] : -$data['amount'];

            // 1. Kasa/Banka hareketini kaydet
            $cashBankTransaction = CashBankTransaction::create([
                'company_id' => $companyId,
                'cash_bank_account_id' => $account->id,
                'type' => $data['type'],
                'method' => $data['method'] ?? 'cash',
                'amount' => $data['amount'],
                'transaction_date' => $data['transaction_date'],
                'description' => $data['description'],
                'reference_number' => $data['reference_number'] ?? null,
                'balance_after' => $account->current_balance + $balanceChange,
            ]);

            // 2. Bakiye güncelle
            if ($data['type'] === 'income') {
                $account->increment('current_balance', $data['amount']);
            } else {
                $account->decrement('current_balance', $data['amount']);
            }

            return $cashBankTransaction;
        });
    }

    /**
     * Helper: Ödeme yöntemi label
     */
    private function getMethodLabel($method): string
    {
        return match($method) {
            'cash' => 'Nakit',
            'transfer' => 'Havale/EFT',
            'credit_card' => 'Kredi Kartı',
            'check' => 'Çek',
            'other' => 'Diğer',
            default => $method,
        };
    }
}
