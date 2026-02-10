<?php

namespace Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\InvoiceItem;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\LedgerEntry;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\FiscalPeriod;
use Modules\CRM\Models\Contact;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Warehouse;

class DemoInvoiceSeeder extends Seeder
{
    public function run(int $companyId = 1)
    {
        $this->command->info("Creating Demo Invoices and Journal Entries for Company ID: $companyId");

        // 1. Get Dependencies
        $contact = Contact::where('company_id', $companyId)->where('type', 'customer')->first();
        $product = Product::where('company_id', $companyId)->first();
        $warehouse = Warehouse::where('company_id', $companyId)->where('code', 'WH-' . $companyId . '-01')->first() ?? Warehouse::where('company_id', $companyId)->first();
        
        // Use 120 (Alicilar) and 600 (Yurtici Satislar) from TDHP
        $accountReceivable = Account::where('company_id', $companyId)->where('code', '120')->first();
        $accountSales = Account::where('company_id', $companyId)->where('code', '600')->first();
        $accountVat = Account::where('company_id', $companyId)->where('code', '391')->first();

        // Check for fiscal period
        $fiscalPeriod = FiscalPeriod::where('company_id', $companyId)
            ->where('status', 'open')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if (!$contact || !$product || !$warehouse || !$accountReceivable || !$accountSales || !$fiscalPeriod) {
            $this->command->warn("Missing dependencies for Invoices (Contact: " . ($contact ? 'OK' : 'FAIL') . 
                ", Product: " . ($product ? 'OK' : 'FAIL') . 
                ", Warehouse: " . ($warehouse ? 'OK' : 'FAIL') . 
                ", Accounts: " . ($accountReceivable && $accountSales ? 'OK' : 'FAIL') . 
                ", FiscalPeriod: " . ($fiscalPeriod ? 'OK' : 'FAIL') . "). Skipping Invoice creation.");
            return;
        }

        $totalAmount = round($product->sale_price * 1.20, 2);
        $taxAmount = round($product->sale_price * 0.20, 2);
        $subtotal = round($product->sale_price, 2);

        // 2. Create Invoice
        $invoice = Invoice::create([
            'company_id' => $companyId,
            'contact_id' => $contact->id,
            'invoice_number' => 'FAT-' . date('Y') . '-0001',
            'issue_date' => now()->subDays(2),
            'due_date' => now()->addDays(15),
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
            'status' => 'sent',
            'notes' => 'Sistem test faturasıdır.'
        ]);

        // 3. Create Items
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'description' => $product->name . ' Satışı',
            'quantity' => 1,
            'unit_price' => $product->sale_price,
            'tax_rate' => 20,
            'total' => $totalAmount
        ]);

        // 4. Stock Movement
        if ($product->stock_track) {
            StockMovement::create([
                'company_id' => $companyId,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'quantity' => -1,
                'type' => 'sale',
                'reference' => 'Fatura #' . $invoice->invoice_number,
            ]);
        }

        // 5. Accounting Transaction (Header)
        $transaction = Transaction::create([
            'company_id' => $companyId,
            'fiscal_period_id' => $fiscalPeriod->id,
            'type' => 'regular',
            'receipt_number' => 'FIS-' . time(),
            'date' => $invoice->issue_date,
            'description' => 'Satış Faturası Tahakkuku #' . $invoice->invoice_number,
            'is_approved' => true,
        ]);

        // 6. Ledger Entries (Double Entry)
        
        // Debit: Customer Account (120)
        LedgerEntry::create([
            'company_id' => $companyId,
            'transaction_id' => $transaction->id,
            'account_id' => $accountReceivable->id,
            'debit' => $totalAmount,
            'credit' => 0,
            'description' => $contact->name . ' - Fatura Tahakkuku'
        ]);

        // Credit: Sales Account (600)
        LedgerEntry::create([
            'company_id' => $companyId,
            'transaction_id' => $transaction->id,
            'account_id' => $accountSales->id,
            'debit' => 0,
            'credit' => $subtotal,
            'description' => 'Yurtiçi Satış Geliri'
        ]);

        // Credit: VAT Account (391) if exists
        if ($accountVat) {
            LedgerEntry::create([
                'company_id' => $companyId,
                'transaction_id' => $transaction->id,
                'account_id' => $accountVat->id,
                'debit' => 0,
                'credit' => $taxAmount,
                'description' => 'Hesaplanan KDV'
            ]);
        }

        $invoice->update(['transaction_id' => $transaction->id]);
        $contact->increment('current_balance', $totalAmount);

        $this->command->info("Demo Invoice and Journal Entry created: " . $invoice->invoice_number);
    }
}
