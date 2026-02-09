<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    private const CACHE_DURATION = 3600; // 1 hour
    private const GEO_API_URL = 'https://geocoding-api.open-meteo.com/v1/search';
    private const WEATHER_API_URL = 'https://api.open-meteo.com/v1/forecast';
    
    /**
     * Get weather data for a given city using Open-Meteo (No Key)
     *
     * @param string $city
     * @return array|null
     */
    public function getWeather(string $city): ?array
    {
        if (empty($city)) {
            return null;
        }

        $cacheKey = 'weather_openmeteo_' . strtolower(str_replace(' ', '_', $city));

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($city) {
            try {
                // Step 1: Geocoding (City -> Lat/Lon)
                $coords = $this->getCoordinates($city);
                
                if (!$coords) {
                    Log::warning("WeatherService: Coordinates not found for city: $city");
                    return $this->getFallbackWeather($city);
                }

                // Step 2: Get Weather
                $response = Http::withoutVerifying()->timeout(5)->get(self::WEATHER_API_URL, [
                    'latitude' => $coords['lat'],
                    'longitude' => $coords['lon'],
                    'current' => 'temperature_2m,relative_humidity_2m,is_day,weather_code,wind_speed_10m,wind_direction_10m',
                    'timezone' => 'auto',
                ]);

                if (!$response->successful()) {
                    Log::error("WeatherService: API request failed. Status: " . $response->status() . " Body: " . $response->body());
                    return $this->getFallbackWeather($city);
                }

                $data = $response->json();
                return $this->formatWeatherData($city, $data);

            } catch (\Exception $e) {
                Log::error('Weather service error', [
                    'city' => $city,
                    'error' => $e->getMessage(),
                ]);
                return $this->getFallbackWeather($city);
            }
        });
    }

    private function getCoordinates(string $city): ?array
    {
        try {
            $response = Http::withoutVerifying()->timeout(3)->get(self::GEO_API_URL, [
                'name' => $city,
                'count' => 1,
                'language' => 'tr',
                'format' => 'json'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['results'][0])) {
                    Log::info("WeatherService: Coordinates found for $city: " . json_encode($data['results'][0]));
                    return [
                        'lat' => $data['results'][0]['latitude'],
                        'lon' => $data['results'][0]['longitude']
                    ];
                }
            }
            Log::warning("WeatherService: Geocoding failed or empty result for $city. Status: " . $response->status());
        } catch (\Exception $e) {
             Log::warning("Geocoding failed for $city: " . $e->getMessage());
        }
        return null;
    }

    /**
     * Get fallback weather data when API is unavailable (Smart Mock)
     */
    private function getFallbackWeather(string $city): array
    {
        // Simple mock fallback to prevent crash
        return [
            'city' => $city,
            'temp_c' => 18, // Default safe value
            'humidity' => 50,
            'wind_speed_kmph' => 10,
            'weather_desc' => 'Bilinmiyor',
            'weather_code' => 0,
            'icon' => 'wb_cloudy',
            'is_mock' => true
        ];
    }

    private function formatWeatherData(string $city, array $data): array
    {
        $current = $data['current'];
        $code = $current['weather_code'];
        $isDay = $current['is_day'] === 1;

        return [
            'city' => $city,
            'temp_c' => round($current['temperature_2m']),
            'temp_f' => round(($current['temperature_2m'] * 9/5) + 32),
            'humidity' => $current['relative_humidity_2m'],
            'wind_speed_kmph' => round($current['wind_speed_10m']),
            'weather_desc' => $this->getWeatherDescription($code),
            'weather_code' => $code,
            'icon' => $this->getWeatherIcon($code, $isDay),
        ];
    }

    private function getWeatherDescription(int $code): string
    {
        // WMO Weather interpretation codes (WW)
        return match ($code) {
            0 => 'Açık',
            1, 2, 3 => 'Parçalı Bulutlu',
            45, 48 => 'Sisli',
            51, 53, 55 => 'Çisenti',
            56, 57 => 'Dondurucu Çisenti',
            61, 63, 65 => 'Yağmurlu',
            66, 67 => 'Dondurucu Yağmur',
            71, 73, 75 => 'Kar Yağışlı',
            77 => 'Kar Taneleri',
            80, 81, 82 => 'Sağanak Yağışlı',
            85, 86 => 'Kar Sağanağı',
            95 => 'Fırtına',
            96, 99 => 'Dolu & Fırtına',
            default => 'Bilinmiyor'
        };
    }

    private function getWeatherIcon(int $code, bool $isDay): string
    {
        return match (true) {
            $code === 0 => $isDay ? 'wb_sunny' : 'nightlight',
            in_array($code, [1, 2, 3]) => 'partly_cloudy_day',
            in_array($code, [45, 48]) => 'foggy',
            in_array($code, [51, 53, 55, 56, 57, 61, 63, 65, 66, 67, 80, 81, 82]) => 'rainy',
            in_array($code, [71, 73, 75, 77, 85, 86]) => 'weather_snowy',
            in_array($code, [95, 96, 99]) => 'thunderstorm',
            default => 'wb_cloudy',
        };
    }
}

