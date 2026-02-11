<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Security Check: Only SuperAdmin
        if (!auth()->user()->is_super_admin) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $cacheSize = \Modules\SuperAdmin\Services\MetricService::getCacheSize();
        $logoCollapsed = \App\Models\Setting::get('logo_collapsed');
        $logoLight = \App\Models\Setting::get('logo_light');
        $logoDark = \App\Models\Setting::get('logo_dark');
        $loginBackground = \App\Models\Setting::get('login_background');

        return view('settings.index', compact('cacheSize', 'logoCollapsed', 'logoLight', 'logoDark', 'loginBackground'));
    }

    /**
     * Update appearance settings.
     */
    public function updateAppearance(Request $request)
    {
        // Security Check: Only SuperAdmin
        if (!auth()->user()->is_super_admin) {
            Log::warning('SettingsController: Unauthorized access attempt');
            abort(403, 'Bu işlemi yapmaya yetkiniz yok.');
        }

        // Check if any file was actually sent in the request
        if ($request->isMethod('post') && empty($request->allFiles()) && !$request->has('remove_login_background')) {
            return back()->with('error', 'Sunucuya hiçbir dosya ulaşmadı. Dosya boyutu sunucu limitlerini (upload_max_filesize veya post_max_size) aşıyor olabilir.');
        }

        $request->validate([
            'logo_collapsed' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml|max:2048',
            'logo_light' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml|max:2048',
            'logo_dark' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml|max:2048',
            'login_background' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,video/mp4,video/webm|max:20480',
        ], [], [
            'logo_collapsed' => 'Menü Kapalı Logo (Favicon)',
            'logo_light' => 'Aydınlık Tema Logosu',
            'logo_dark' => 'Karanlık Tema Logosu',
            'login_background' => 'Giriş Ekranı Arkaplanı',
        ]);

        $updatedCount = 0;

        // Remove Login Background
        if ($request->has('remove_login_background') && $request->remove_login_background == '1') {
            \App\Models\Setting::updateOrCreate(
                ['key' => 'login_background'],
                ['value' => null]
            );
            $updatedCount++;
        }

        if ($request->hasFile('logo_collapsed')) {
            $path = $request->file('logo_collapsed')->store('logos', 'public');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'logo_collapsed'],
                ['value' => 'storage/' . $path]
            );
            $updatedCount++;
        }

        if ($request->hasFile('logo_light')) {
            $path = $request->file('logo_light')->store('logos', 'public');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'logo_light'],
                ['value' => 'storage/' . $path]
            );
            $updatedCount++;
        }

        if ($request->hasFile('logo_dark')) {
            $path = $request->file('logo_dark')->store('logos', 'public');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'logo_dark'],
                ['value' => 'storage/' . $path]
            );
            $updatedCount++;
        }

        if ($request->hasFile('login_background')) {
            $path = $request->file('login_background')->store('backgrounds', 'public');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'login_background'],
                ['value' => 'storage/' . $path]
            );
            $updatedCount++;
        }

        if ($updatedCount === 0) {
            return back()->with('error', 'Herhangi bir değişiklik yapılmadı. Lütfen yüklemek için bir dosya seçtiğinizden emin olun.');
        }

        return back()->with('success', 'Görünüm ayarları başarıyla güncellendi.');
    }

    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        // Security Check: Only SuperAdmin
        if (!auth()->user()->is_super_admin) {
            abort(403, 'Bu işlemi yapmaya yetkiniz yok.');
        }

        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            
            return back()->with('success', 'Sistem önbelleği başarıyla temizlendi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Önbellek temizlenirken bir hata oluştu: ' . $e->getMessage());
        }
    }
}
