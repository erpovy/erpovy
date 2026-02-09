<?php

namespace Modules\SuperAdmin\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MetricService
{
    public static function getDatabaseSize()
    {
        $connection = config('database.default');
        
        if ($connection === 'sqlite') {
            $path = config('database.connections.sqlite.database');
            if (File::exists($path)) {
                $size = File::size($path);
                return static::formatBytes($size);
            }
        }

        if ($connection === 'mysql') {
            $dbName = config('database.connections.mysql.database');
            $query = "SELECT SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema = ?";
            $result = DB::select($query, [$dbName]);
            return static::formatBytes($result[0]->size ?? 0);
        }

        return '0 KB';
    }

    public static function getCacheSize()
    {
        $paths = [
            storage_path('framework/cache'),
            storage_path('framework/views'),
            storage_path('framework/testing'),
            storage_path('framework/sessions'), // Optional: include sessions if file driver
            base_path('bootstrap/cache'),
        ];

        $size = 0;

        foreach ($paths as $path) {
            if (File::exists($path)) {
                $size += static::getDirectorySize($path);
            }
        }

        return static::formatBytes($size);
    }

    private static function getDirectorySize($path)
    {
        $size = 0;
        foreach (File::allFiles($path) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }

    public static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
