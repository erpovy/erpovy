<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$keys = ['logo_collapsed', 'logo_expanded', 'login_background'];
foreach ($keys as $key) {
    $setting = Setting::where('key', $key)->first();
    if ($setting && (str_contains($setting->value, '127.0.0.1') || str_contains($setting->value, 'http'))) {
        $oldValue = $setting->value;
        // Strip everything up to and including /storage/
        if (preg_match('/storage\/(.*)$/', $oldValue, $matches)) {
            $setting->value = 'storage/' . $matches[1];
            $setting->save();
            echo "Updated $key: $oldValue -> {$setting->value}\n";
        }
    }
}
