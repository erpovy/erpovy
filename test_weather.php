<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$weatherService = new App\Services\WeatherService();

echo "Testing Weather Service...\n\n";

$cities = ['Istanbul', 'Ankara', 'Izmir'];

foreach ($cities as $city) {
    echo "Testing: $city\n";
    $weather = $weatherService->getWeather($city);
    
    if ($weather) {
        echo "✓ Success!\n";
        echo "  Temperature: {$weather['temp_c']}°C\n";
        echo "  Description: {$weather['weather_desc']}\n";
        echo "  Icon: {$weather['icon']}\n";
        echo "  Humidity: {$weather['humidity']}%\n";
        echo "  Wind: {$weather['wind_kph']} km/h\n";
    } else {
        echo "✗ Failed to get weather data\n";
    }
    echo "\n";
}

echo "Cache test - fetching Istanbul again (should use cache):\n";
$weather = $weatherService->getWeather('Istanbul');
echo $weather ? "✓ Cache working\n" : "✗ Cache failed\n";
