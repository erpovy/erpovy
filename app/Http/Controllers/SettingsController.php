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
        $logoExpanded = \App\Models\Setting::get('logo_expanded');
        $loginBackground = \App\Models\Setting::get('login_background');

        return view('settings.index', compact('cacheSize', 'logoCollapsed', 'logoExpanded', 'loginBackground'));
    }

    /**
     * Update appearance settings.
     */
    public function updateAppearance(Request $request)
    {
        Log::info('SettingsController: updateAppearance called', [
            'user' => auth()->id(),
            'is_super_admin' => auth()->user()->is_super_admin,
            'has_logo_collapsed' => $request->hasFile('logo_collapsed'),
            'has_logo_expanded' => $request->hasFile('logo_expanded'),
            'files' => array_keys($request->allFiles())
        ]);

        // Security Check: Only SuperAdmin
        if (!auth()->user()->is_super_admin) {
            Log::warning('SettingsController: Unauthorized access attempt');
            abort(403, 'Bu işlemi yapmaya yetkiniz yok.');
        }

        $request->validate([
            'logo_collapsed' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml|max:2048',
            'logo_expanded' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml|max:2048',
            'login_background' => 'nullable|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,video/mp4,video/webm|max:20480',
        ], [], [
            'logo_collapsed' => 'Menü Kapalı Logo',
            'logo_expanded' => 'Menü Açık Logo',
            'login_background' => 'Giriş Ekranı Arkaplanı',
        ]);

        // Remove Login Background
        if ($request->has('remove_login_background') && $request->remove_login_background == '1') {
            \App\Models\Setting::updateOrCreate(
                ['key' => 'login_background'],
                ['value' => null]
            );
        }

        if ($request->hasFile('logo_collapsed')) {
            $path = $request->file('logo_collapsed')->store('logos', 'public');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'logo_collapsed'],
                ['value' => 'storage/' . $path]
            );
        }

        if ($request->hasFile('logo_expanded')) {
            $path = $request->file('logo_expanded')->store('logos', 'public');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'logo_expanded'],
                ['value' => 'storage/' . $path]
            );
        }

        if ($request->hasFile('login_background')) {
            $path = $request->file('login_background')->store('backgrounds', 'public');
            \App\Models\Setting::updateOrCreate(
                ['key' => 'login_background'],
                ['value' => 'storage/' . $path]
            );
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
