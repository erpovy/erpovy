<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class VersionService
{
    protected static $versionFile = 'version.json';

    public static function getVersionInfo()
    {
        $path = base_path(static::$versionFile);
        if (File::exists($path)) {
            return json_decode(File::get($path), true);
        }
        return ['version' => 'v1.0.0', 'last_updated' => now()->toDateTimeString()];
    }

    public static function incrementVersion($type = 'patch', $description = '')
    {
        $info = static::getVersionInfo();
        $version = ltrim($info['version'], 'v');
        $parts = explode('.', $version);

        switch ($type) {
            case 'major':
                $parts[0]++;
                $parts[1] = 0;
                $parts[2] = 0;
                break;
            case 'minor':
                $parts[1]++;
                $parts[2] = 0;
                break;
            case 'patch':
            default:
                $parts[2]++;
                break;
        }

        $newVersion = 'v' . implode('.', $parts);
        $info['version'] = $newVersion;
        $info['last_updated'] = now()->toDateTimeString();
        if ($description) {
            $info['description'] = $description;
        }

        File::put(base_path(static::$versionFile), json_encode($info, JSON_PRETTY_PRINT));

        return $newVersion;
    }
}
