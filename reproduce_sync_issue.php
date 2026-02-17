<?php

use Modules\Inventory\Models\Product;
use Modules\Ecommerce\Models\EcommerceMapping;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$companyId = 21; // The one from the platform
$sku = 'TEST-SKU-123';

echo "Attempting to create product for company $companyId...\n";

try {
    $productData = [
        'name' => 'Test Product From Script',
        'sale_price' => 100,
        'description' => 'Test description',
        'stock_track' => true,
    ];

    $product = Product::create(array_merge($productData, [
        'company_id' => $companyId,
        'code' => $sku,
        'is_active' => true,
    ]));

    echo "Product created with ID: " . $product->id . "\n";

    $mapping = EcommerceMapping::create([
        'company_id' => $companyId,
        'ecommerce_platform_id' => 1,
        'mappable_id' => $product->id,
        'mappable_type' => Product::class,
        'external_id' => 99999,
        'remote_data' => ['test' => true],
    ]);

    echo "Mapping created with ID: " . $mapping->id . "\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack Trace: " . $e->getTraceAsString() . "\n";
}
