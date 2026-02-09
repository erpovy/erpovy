<?php

use Modules\HumanResources\Models\Vehicle;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$vehicle = Vehicle::find(1);
if ($vehicle) {
    echo "Vehicle found. Old Company ID: " . var_export($vehicle->company_id, true) . "\n";
    
    // Manually update
    $vehicle->company_id = 1;
    $vehicle->save();
    
    // Refresh and check
    $vehicle->refresh();
    echo "Vehicle updated. New Company ID: " . $vehicle->company_id . "\n";
} else {
    echo "Vehicle with ID 1 not found.\n";
}
