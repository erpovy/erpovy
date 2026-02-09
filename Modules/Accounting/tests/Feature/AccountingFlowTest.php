<?php

namespace Modules\Accounting\Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\FiscalPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class AccountingFlowTest extends TestCase
{
    // use RefreshDatabase; // We won't use RefreshDatabase to keep the seeded data if possible, or we rely on logic.
    // Actually, for a clean test, RefreshDatabase is better, but since we have a persistent local DB, 
    // let's just create data on the fly and let it be deleted by transaction rollback or just clutter (it's local).
    // Better: Use database transactions for tests if supported.
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup initial data since we are refreshing database
        $this->seed(\Database\Seeders\DefaultCompanySeeder::class);
        $this->seed(\Modules\Accounting\Database\Seeders\AccountingTestSeeder::class);
    }

    public function test_admin_can_access_accounting_dashboard()
    {
        $user = User::where('email', 'admin@erpovy.com')->first();

        $response = $this->actingAs($user)->get(route('accounting.index'));
        
        $response->assertStatus(200);
        $response->assertSee('Muhasebe Fişleri');
    }

    public function test_admin_can_create_balanced_journal_entry()
    {
        $user = User::where('email', 'admin@erpovy.com')->first();
        $company = $user->company;
        
        $account1 = Account::where('code', '100')->first();
        $account2 = Account::where('code', '102')->first();

        $data = [
            'date' => '2025-01-15', // Matches seeded Fiscal Period (2025)
            'description' => 'Test Fişi Otomatik',
            'entries' => [
                [
                    'account_id' => $account1->id,
                    'debit' => 1000,
                    'credit' => 0,
                    'description' => 'Kasa Giriş'
                ],
                [
                    'account_id' => $account2->id,
                    'debit' => 0,
                    'credit' => 1000,
                    'description' => 'Banka Çıkış'
                ]
            ]
        ];

        $response = $this->actingAs($user)->post(route('accounting.transactions.store'), $data);

        // Assert redirect to index with success message
        $response->assertRedirect(route('accounting.transactions.index'));
        $response->assertSessionHas('success');

        // Assert Database Logic
        $this->assertDatabaseHas('transactions', [
            'company_id' => $company->id,
            'description' => 'Test Fişi Otomatik',
        ]);
        
        $this->assertDatabaseHas('ledger_entries', [
            'account_id' => $account1->id,
            'debit' => 1000,
        ]);
    }

    public function test_cannot_create_unbalanced_journal_entry()
    {
        $user = User::where('email', 'admin@erpovy.com')->first();
        
        $account1 = Account::where('code', '100')->first();
        $account2 = Account::where('code', '102')->first();

        $data = [
            'date' => now()->format('Y-m-d'),
            'description' => 'Hatalı Fiş',
            'entries' => [
                [
                    'account_id' => $account1->id,
                    'debit' => 1000,
                    'credit' => 0,
                ],
                [
                    'account_id' => $account2->id,
                    'debit' => 0,
                    'credit' => 500, // Unbalanced (Total 1000 vs 500)
                ]
            ]
        ];

        $response = $this->actingAs($user)->post(route('accounting.transactions.store'), $data);

        // Should return back with errors
        $response->assertSessionHasErrors(['msg']);
        $this->assertDatabaseMissing('transactions', ['description' => 'Hatalı Fiş']);
    }

}
