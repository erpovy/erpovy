<?php

namespace Modules\Sales\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Sales\Models\Quote;
use Modules\CRM\Models\Contact;

class DemoQuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $companyId = 1): void
    {
        $contact = Contact::where('company_id', $companyId)->where('type', 'customer')->first();

        if (!$contact) {
            $this->command->warn("No customer found for Sales Quote seeding. Skipping.");
            return;
        }

        Quote::create([
            'company_id' => $companyId,
            'contact_id' => $contact->id,
            'quote_number' => 'TEK-' . date('Y') . '-0001',
            'date' => now()->subDays(5),
            'expiry_date' => now()->addDays(10),
            'total_amount' => 50000.00,
            'tax_amount' => 10000.00,
            'status' => 'accepted',
            'notes' => 'Örnek onaylanmış satış teklifi.'
        ]);

        Quote::create([
            'company_id' => $companyId,
            'contact_id' => $contact->id,
            'quote_number' => 'TEK-' . date('Y') . '-0002',
            'date' => now(),
            'expiry_date' => now()->addDays(15),
            'total_amount' => 12500.00,
            'tax_amount' => 2500.00,
            'status' => 'sent',
            'notes' => 'Beklemedeki satış teklifi.'
        ]);

        $this->command->info("Sales Demo Quotes created for Company ID: $companyId");
    }
}
