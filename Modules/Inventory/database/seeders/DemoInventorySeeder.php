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
    public function run(): void
    {
        // 1. Create Default Categories
        $categories = [
            ['name' => 'Elektronik'],
            ['name' => 'Ofis Malzemeleri'],
            ['name' => 'Hizmet'],
            ['name' => 'Lojistik'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['name' => $cat['name']], array_merge($cat, ['company_id' => 1]));
        }

        // 2. Create Default Units
        $units = [
            ['name' => 'Adet', 'short_name' => 'AD'],
            ['name' => 'Paket', 'short_name' => 'PK'],
            ['name' => 'Saat', 'short_name' => 'SA'],
            ['name' => 'Kilogram', 'short_name' => 'KG'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(['short_name' => $unit['short_name']], array_merge($unit, ['company_id' => 1]));
        }

        // 3. Create Default Product Types
        $types = [
            ['name' => 'Ticari Mal'],
            ['name' => 'Hizmet'],
            ['name' => 'Hammadde'],
        ];

        foreach ($types as $type) {
            ProductType::updateOrCreate(['name' => $type['name']], array_merge($type, ['company_id' => 1]));
        }

        // 4. Create Default Warehouse
        $warehouse = Warehouse::updateOrCreate(
            ['name' => 'Ana Depo'],
            [
                'company_id' => 1,
                'code' => 'WH01',
                'address' => 'Merkez Ofis Depo Katı',
                'is_active' => true
            ]
        );

        // 5. Create Demo Products
        $catElektronik = Category::where('name', 'Elektronik')->first();
        $catOfis = Category::where('name', 'Ofis Malzemeleri')->first();
        $unitAdet = Unit::where('short_name', 'AD')->first();
        $typeMal = ProductType::where('name', 'Ticari Mal')->first();

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
            Product::updateOrCreate(['code' => $prod['code']], array_merge($prod, ['company_id' => 1]));
        }

        $this->command->info('Inventory Demo Data created successfully.');
    }
}
