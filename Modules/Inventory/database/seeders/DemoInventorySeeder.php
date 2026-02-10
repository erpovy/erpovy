<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Unit;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductType;

class DemoInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $companyId = 1): void
    {
        // 1. Create Default Categories
        $categories = [
            ['name' => 'Elektronik'],
            ['name' => 'Ofis Malzemeleri'],
            ['name' => 'Hizmetler'],
            ['name' => 'Lojistik'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['name' => $cat['name'], 'company_id' => $companyId], $cat);
        }

        // 2. Create Default Units
        $units = [
            ['name' => 'Adet', 'symbol' => 'AD'],
            ['name' => 'Paket', 'symbol' => 'PK'],
            ['name' => 'Saat', 'symbol' => 'SA'],
            ['name' => 'Kilogram', 'symbol' => 'KG'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(['symbol' => $unit['symbol'], 'company_id' => $companyId], $unit);
        }

        // 3. Create Default Warehouse
        Warehouse::updateOrCreate(
            ['name' => 'Ana Depo', 'company_id' => $companyId],
            [
                'code' => 'WH01',
                'address' => 'Merkez Ofis Depo Katı',
                'is_active' => true
            ]
        );

        // 4. Create Demo Products
        $catElektronik = Category::where('name', 'Elektronik')->where('company_id', $companyId)->first();
        $catOfis = Category::where('name', 'Ofis Malzemeleri')->where('company_id', $companyId)->first();
        $unitAdet = Unit::where('symbol', 'AD')->where('company_id', $companyId)->first();
        $typeMal = ProductType::where('code', 'good')->first(); // Global type

        $products = [
            [
                'name' => 'MacBook Pro 14" M3',
                'code' => 'MBP-14-M3',
                'sale_price' => 74500.00,
                'purchase_price' => 62000.00,
                'category_id' => $catElektronik->id ?? null,
                'unit_id' => $unitAdet->id ?? null,
                'product_type_id' => $typeMal->id ?? null,
                'vat_rate' => 20,
                'stock_track' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Dell Erpovy Kurumsal IPS Monitör',
                'code' => 'DELL-27-IPS',
                'sale_price' => 12500.00,
                'purchase_price' => 9500.00,
                'category_id' => $catElektronik->id ?? null,
                'unit_id' => $unitAdet->id ?? null,
                'product_type_id' => $typeMal->id ?? null,
                'vat_rate' => 20,
                'stock_track' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Ergonomik Ofis Koltuğu',
                'code' => 'OFIS-KOLT-01',
                'sale_price' => 4500.00,
                'purchase_price' => 3200.00,
                'category_id' => $catOfis->id ?? null,
                'unit_id' => $unitAdet->id ?? null,
                'product_type_id' => $typeMal->id ?? null,
                'vat_rate' => 10,
                'stock_track' => true,
                'is_active' => true,
            ],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['code' => $prod['code'], 'company_id' => $companyId], $prod);
        }

        $this->command->info("Inventory Demo Data created for Company ID: $companyId");
    }
}
