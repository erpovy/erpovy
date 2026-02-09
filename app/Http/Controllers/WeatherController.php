<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getWeather()
    {
        $user = auth()->user();
        $company = $user->company;
        
        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        $city = $company->settings['city'] ?? 'Ankara';
        
        // Get weather data from WeatherService
        $weatherData = $this->weatherService->getWeather($city);

        if (!$weatherData) {
            return response()->json([
                'error' => 'Weather data not available'
            ], 503);
        }

        return response()->json($weatherData);
    }
}
