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
    public function run()
    {
        // 1. Ensure Dependencies
        $contact = Contact::firstOrCreate(
            ['email' => 'demo@musteri.com'],
            [
                'company_id' => 1,
                'name' => 'Demo Müşteri A.Ş.',
                'type' => 'customer',
                'phone' => '05550000000',
                'is_active' => true
            ]
        );

        $product = Product::firstOrCreate(
            ['code' => 'DEMO-001'],
            [
                'company_id' => 1,
                'name' => 'Demo Ürün (Laptop)',
                'type' => 'good',
                'sale_price' => 25000.00,
                'purchase_price' => 20000.00,
                'stock_track' => true,
                'tax_rate' => 20
            ]
        );

        $warehouse = Warehouse::firstOrCreate(
            ['company_id' => 1],
            ['name' => 'Merkez Depo', 'code' => 'MAIN']
        );
        
        // Ensure stock exists for the demo
        StockMovement::create([
            'company_id' => 1,
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 10,
            'type' => 'adjustment',
            'reference' => 'Demo Stok Girişi'
        ]);

        $account = Account::where('code', '120')->first() ?? Account::first();

        // 2. Create Invoice
        $invoice = Invoice::create([
            'company_id' => 1,
            'contact_id' => $contact->id,
            'invoice_number' => 'INV-' . date('Ymd') . '-001',
            'issue_date' => now(),
            'due_date' => now()->addDays(7),
            'total_amount' => 30000.00, // 25000 + 20% VAT
            'tax_amount' => 5000.00,
            'status' => 'sent',
        ]);

        // 3. Create Items
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'description' => 'Demo Ürün Satışı',
            'quantity' => 1,
            'unit_price' => 25000.00,
            'tax_rate' => 20,
            'total' => 30000.00
        ]);

        // 4. Deduct Stock
        StockMovement::create([
            'company_id' => 1,
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => -1,
            'type' => 'sale',
            'reference' => 'Fatura #' . $invoice->invoice_number,
        ]);

        // 5. Create Transaction
        $transaction = Transaction::create([
            'company_id' => 1,
            'account_id' => $account->id,
            'type' => 'income',
            'amount' => 30000.00,
            'date' => now(),
            'description' => 'Fatura Satışı #' . $invoice->invoice_number,
            'reference' => $invoice->invoice_number,
        ]);

        $invoice->update(['transaction_id' => $transaction->id]);
        $contact->increment('current_balance', 30000.00);

        $this->command->info('Demo Fatura Kesildi: ' . $invoice->invoice_number);
    }
}
