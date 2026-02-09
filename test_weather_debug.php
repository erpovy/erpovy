<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing wttr.in API directly...\n\n";

try {
    $response = \Illuminate\Support\Facades\Http::timeout(10)->get('https://wttr.in/Istanbul', [
        'format' => 'j1',
    ]);
    
    echo "Status Code: " . $response->status() . "\n";
    echo "Successful: " . ($response->successful() ? 'Yes' : 'No') . "\n\n";
    
    if ($response->successful()) {
        $data = $response->json();
        echo "Response structure:\n";
        echo "- Has current_condition: " . (isset($data['current_condition']) ? 'Yes' : 'No') . "\n";
        
        if (isset($data['current_condition'][0])) {
            $current = $data['current_condition'][0];
            echo "\nCurrent condition data:\n";
            echo "  temp_C: " . ($current['temp_C'] ?? 'N/A') . "\n";
            echo "  temp_F: " . ($current['temp_F'] ?? 'N/A') . "\n";
            echo "  humidity: " . ($current['humidity'] ?? 'N/A') . "\n";
            echo "  weatherDesc: " . ($current['weatherDesc'][0]['value'] ?? 'N/A') . "\n";
            echo "  weatherCode: " . ($current['weatherCode'] ?? 'N/A') . "\n";
            echo "  windspeedKmph: " . ($current['windspeedKmph'] ?? 'N/A') . "\n";
        }
        
        echo "\n\nNow testing WeatherService...\n";
        $weatherService = new App\Services\WeatherService();
        $weather = $weatherService->getWeather('Istanbul');
        
        if ($weather) {
            echo "✓ WeatherService Success!\n";
            print_r($weather);
        } else {
            echo "✗ WeatherService returned null\n";
        }
    } else {
        echo "Error: " . $response->body() . "\n";
    }
    
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
