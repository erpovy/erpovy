<?php

namespace Tests\Unit;

use Tests\TestCase;
use Modules\Accounting\Services\GibPortalService;
use App\Models\Company;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GibPortalServiceTest extends TestCase
{
    public function test_login_sends_correct_parameters_and_receives_token()
    {
        // 1. Mock Data Setup
        $company = new Company([
            'gib_username' => '86006118',
            'gib_password' => '275272',
        ]);
        
        $mockToken = 'mock-session-token-12345';

        // 2. Mock HTTP calls
        Http::fake([
            '*/intragiris.html' => Http::response('login page html', 200),
            '*/earsiv-services/assos-login' => Http::response([
                'token' => $mockToken,
                'redirectUrl' => 'index.jsp'
            ], 200)
        ]);

        // 3. Execution
        $service = new GibPortalService($company);
        $token = $service->login();

        // 4. Assertions
        $this->assertEquals($mockToken, $token);
        
        // Verify POST call parameters
        Http::assertSent(function ($request) {
            return $request->url() == 'https://earsivportal.efatura.gov.tr/earsiv-services/assos-login' &&
                   $request['assoscmd'] == 'anologin' &&
                   $request['userid'] == '86006118' &&
                   $request['sifre'] == '275272' &&
                   $request['sifre2'] == '275272';
        });

        echo "Test SUCCESS: Login logic verified with mocks.\n";
    }
}
