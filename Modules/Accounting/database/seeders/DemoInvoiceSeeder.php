<?php

namespace Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\InvoiceItem;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\Account;
use Modules\CRM\Models\Contact;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Warehouse;

class DemoInvoiceSeeder extends Seeder
{
    public function run(int $companyId = 1)
    {
        $this->command->info("Creating Demo Invoices for Company ID: $companyId");

        // 1. Get Dependencies
        $contact = Contact::where('company_id', $companyId)->where('type', 'customer')->first();
        $product = Product::where('company_id', $companyId)->first();
        $warehouse = Warehouse::where('company_id', $companyId)->where('code', 'WH-' . $companyId . '-01')->first() ?? Warehouse::where('company_id', $companyId)->first();
        $account = Account::where('company_id', $companyId)->where('code', '120')->first() ?? Account::where('company_id', $companyId)->first();

        if (!$contact || !$product || !$warehouse || !$account) {
            $this->command->warn("Missing dependencies for Invoices (Contact: " . ($contact ? 'OK' : 'FAIL') . 
                ", Product: " . ($product ? 'OK' : 'FAIL') . 
                ", Warehouse: " . ($warehouse ? 'OK' : 'FAIL') . 
                ", Account: " . ($account ? 'OK' : 'FAIL') . "). Skipping Invoice creation.");
            return;
        }

        // 2. Create Invoice
        $invoice = Invoice::create([
            'company_id' => $companyId,
            'contact_id' => $contact->id,
            'invoice_number' => 'FAT-' . date('Y') . '-0001',
            'issue_date' => now()->subDays(2),
            'due_date' => now()->addDays(15),
            'total_amount' => $product->sale_price * 1.20, // Incl VAT
            'tax_amount' => $product->sale_price * 0.20,
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
            'total' => $product->sale_price * 1.20
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

        // 5. Accounting Transaction
        $transaction = Transaction::create([
            'company_id' => $companyId,
            'account_id' => $account->id,
            'type' => 'income',
            'amount' => $invoice->total_amount,
            'date' => $invoice->issue_date,
            'description' => 'Satış Faturası Tahakkuku #' . $invoice->invoice_number,
            'reference' => $invoice->invoice_number,
        ]);

        $invoice->update(['transaction_id' => $transaction->id]);
        $contact->increment('current_balance', $invoice->total_amount);

        $this->command->info("Demo Invoice created: " . $invoice->invoice_number);
    }
}
