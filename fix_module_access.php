<?php

use App\Models\Company;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$logFile = __DIR__ . '/debug_output.txt';
$output = "";

try {
    $company = Company::first();
    if (!$company) {
        $output .= "Error: No company found.\n";
    } else {
        $output .= "Company Found: " . $company->name . "\n";
        
        $settings = $company->settings;
        $output .= "Current Settings Type: " . gettype($settings) . "\n";
        
        if (is_string($settings)) {
            $settings = json_decode($settings, true) ?? [];
        }
        
        $modules = $settings['modules'] ?? [];
        $output .= "Current Modules: " . implode(', ', $modules) . "\n";
        
        if (!in_array('FixedAssets', $modules)) {
            $modules[] = 'FixedAssets';
            $settings['modules'] = $modules;
            $company->settings = $settings;
            $company->save();
            $output .= "SUCCESS: 'FixedAssets' added to modules.\n";
        } else {
            $output .= "INFO: 'FixedAssets' already exists.\n";
        }
        
        // Verify save
        $company->refresh();
        $finalModules = $company->settings['modules'] ?? [];
        $output .= "Final Modules: " . implode(', ', $finalModules) . "\n";
    }
} catch (\Exception $e) {
    $output .= "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

file_put_contents($logFile, $output);
echo "Script finished. Check debug_output.txt.\n";
