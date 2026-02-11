<?php

use App\Models\Company;
use App\Models\User;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$companies = Company::all();
echo "Companies Count: " . $companies->count() . "\n";

foreach ($companies as $company) {
    echo "Company: " . $company->name . " (ID: " . $company->id . ")\n";
    $modules = $company->settings['modules'] ?? []; 
    // If it's stored as a JSON string, decode it
    if (is_string($modules)) {
        $modules = json_decode($modules, true) ?? [];
    }
    
    echo "Modules: " . implode(', ', $modules) . "\n";
    
    if (in_array('FixedAssets', $modules)) {
        echo "MATCH: 'FixedAssets' is ENABLED.\n";
    } else {
        echo "ISSUE: 'FixedAssets' NOT found.\n";
    }
    echo "--------------------------------------------------\n";
}

$user = User::first();
if ($user) {
    echo "First User: " . $user->name . " (ID: " . $user->id . ")\n";
    if ($user->company) {
         echo "User Company: " . $user->company->name . "\n";
    } else {
         echo "User Company: NONE\n";
    }
    try {
        echo "Has FixedAssets Access: " . ($user->hasModuleAccess('FixedAssets') ? 'YES' : 'NO') . "\n";
    } catch (\Exception $e) {
        echo "Error checking access: " . $e->getMessage() . "\n";
    }
}
