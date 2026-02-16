<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Modules\Accounting\Services\GibPortalService;
use App\Models\Company;

$company = Company::where('gib_username', '86006118')->first();
if (!$company) {
    $company = Company::latest()->first();
}

echo "Testing GIB Login for: " . $company->name . " (User: " . $company->gib_username . ")\n";

try {
    $service = new GibPortalService($company);
    $token = $service->login();
    echo "SUCCESS! Token: " . $token . "\n";
} catch (\Exception $e) {
    echo "FAILURE: " . $e->getMessage() . "\n";
}

// Detaylı debug için yanıtı göreliv
$baseUrl = 'https://earsivportal.efatura.gov.tr';
$response = \Illuminate\Support\Facades\Http::withoutVerifying()
    ->withHeaders([
        'Referer' => 'https://earsivportal.efatura.gov.tr/intragiris.html',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ])
    ->asForm()
    ->post($baseUrl . '/earsiv-services/assos-login', [
        'assoscmd' => 'anologin',
        'rtype' => 'json',
        'userid' => $company->gib_username,
        'sifre' => $company->gib_password,
        'sifre2' => $company->gib_password,
        'parola' => '1',
    ]);

echo "RAW RESPONSE: " . $response->body() . "\n";
