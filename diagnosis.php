<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- ERPOVY SERVER DIAGNOSIS ---\n";
echo "Laravel Version: " . app()->version() . "\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "-------------------------------\n\n";

// 1. Check APP_URL
$appUrl = config('app.url');
echo "Config APP_URL: $appUrl\n";
echo "ENV APP_URL: " . env('APP_URL') . "\n";
echo "-------------------------------\n\n";

// 2. Check Paths
$basePath = base_path();
$publicPath = public_path();
$storagePath = storage_path('app/public');
echo "Base Path: $basePath\n";
echo "Public Path: $publicPath\n";
echo "Storage Path: $storagePath\n";
echo "-------------------------------\n\n";

// 3. Check Logo Settings in DB
$logoCollapsed = \App\Models\Setting::get('logo_collapsed');
echo "DB logo_collapsed: " . ($logoCollapsed ?: 'NULL') . "\n";
if ($logoCollapsed) {
    // Relative to what?
    $fullPath = public_path($logoCollapsed);
    echo "Expected Physical Path (via public_path): $fullPath\n";
    echo "File Exists there? " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
}
echo "-------------------------------\n\n";

// 4. Check Symlink
$storageLink = $publicPath . DIRECTORY_SEPARATOR . 'storage';
echo "Checking Symlink at: $storageLink\n";
if (file_exists($storageLink)) {
    if (is_link($storageLink)) {
        echo "Link exists and IS a symbolic link.\n";
        echo "Points to: " . readlink($storageLink) . "\n";
        echo "Does target exist? " . (file_exists(readlink($storageLink)) ? 'YES' : 'NO') . "\n";
    } else {
        echo "Link exists but IS NOT a symbolic link (it's a directory or file).\n";
        if (is_dir($storageLink)) {
            echo "It's a DIRECTORY. Contents:\n";
            print_r(scandir($storageLink));
        }
    }
} else {
    echo "Symlink does NOT exist.\n";
}
echo "-------------------------------\n\n";

// 5. Check actual storage content
echo "Checking contents of storage/app/public/logos:\n";
$logosDir = $storagePath . DIRECTORY_SEPARATOR . 'logos';
if (is_dir($logosDir)) {
    $files = array_diff(scandir($logosDir), ['.', '..']);
    print_r($files);
} else {
    echo "Directory does NOT exist: $logosDir\n";
}
echo "\n--- END DIAGNOSIS ---\n";
