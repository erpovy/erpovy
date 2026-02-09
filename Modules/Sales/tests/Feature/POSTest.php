<?php

namespace Modules\Sales\Tests\Feature;

use Tests\TestCase;
use Modules\Inventory\Models\Product;
use Modules\CRM\Models\Contact;
use Modules\Accounting\Models\FiscalPeriod;
use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

class POSTest extends TestCase
{
    // use RefreshDatabase; // Use carefully if you have a real DB

    public function test_pos_checkout_creates_invoice_and_transaction()
    {
        $company = Company::first() ?? Company::factory()->create();
        $user = User::where('company_id', $company->id)->first() ?? User::factory()->create(['company_id' => $company->id]);
        
        $fiscalPeriod = FiscalPeriod::where('company_id', $company->id)->where('status', 'open')->first() ?? FiscalPeriod::create([
            'company_id' => $company->id,
            'name' => 'Test Period',
            'start_date' => now()->startOfYear(),
            'end_date' => now()->endOfYear(),
            'status' => 'open'
        ]);

        $product = Product::where('company_id', $company->id)->first() ?? Product::create([
            'company_id' => $company->id,
            'code' => 'TEST-001',
            'name' => 'Test Product',
            'sale_price' => 100,
            'vat_rate' => 20,
            'stock_track' => true
        ]);

        $contact = Contact::where('company_id', $company->id)->where('type', 'customer')->first() ?? Contact::create([
            'company_id' => $company->id,
            'type' => 'customer',
            'name' => 'Test Customer'
        ]);

        $response = $this->actingAs($user)->postJson(route('sales.pos.checkout'), [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2
                ]
            ],
            'contact_id' => $contact->id,
            'payment_method' => 'cash'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('invoices', [
            'company_id' => $company->id,
            'contact_id' => $contact->id,
            'status' => 'paid'
        ]);

        $this->assertDatabaseHas('transactions', [
            'company_id' => $company->id,
            'fiscal_period_id' => $fiscalPeriod->id
        ]);
    }
}
