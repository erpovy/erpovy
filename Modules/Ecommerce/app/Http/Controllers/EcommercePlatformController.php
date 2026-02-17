<?php

namespace Modules\Ecommerce\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Ecommerce\Models\EcommercePlatform;
use Modules\Ecommerce\Services\WooCommerceService;

class EcommercePlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $platforms = EcommercePlatform::all();
        return view('ecommerce::platforms.index', compact('platforms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ecommerce::platforms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:woocommerce',
            'store_url' => 'required|url',
            'consumer_key' => 'required|string',
            'consumer_secret' => 'required|string',
            'status' => 'required|string|in:active,passive',
            'settings' => 'nullable|array',
        ]);

        if (isset($validated['settings']['sync_images'])) {
            $validated['settings']['sync_images'] = (bool) $validated['settings']['sync_images'];
        } else {
            $validated['settings']['sync_images'] = false;
        }

        EcommercePlatform::create($validated);

        return redirect()->route('ecommerce.platforms.index')
            ->with('success', 'Mağaza başarıyla eklendi.');
    }

    /**
     * Show the specified resource.
     */
    public function show(EcommercePlatform $platform)
    {
        return view('ecommerce::platforms.show', compact('platform'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EcommercePlatform $platform)
    {
        return view('ecommerce::platforms.edit', compact('platform'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EcommercePlatform $platform)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:woocommerce',
            'store_url' => 'required|url',
            'consumer_key' => 'nullable|string',
            'consumer_secret' => 'nullable|string',
            'status' => 'required|string|in:active,passive',
            'settings' => 'nullable|array',
        ]);

        if (isset($validated['settings']['sync_images'])) {
            $validated['settings']['sync_images'] = (bool) $validated['settings']['sync_images'];
        } else {
            $validated['settings']['sync_images'] = false;
        }

        if (empty($validated['consumer_key'])) unset($validated['consumer_key']);
        if (empty($validated['consumer_secret'])) unset($validated['consumer_secret']);

        $platform->update($validated);

        return redirect()->route('ecommerce.platforms.index')
            ->with('success', 'Mağaza başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EcommercePlatform $platform)
    {
        $platform->delete();
        return redirect()->route('ecommerce.platforms.index')
            ->with('success', 'Mağaza silindi.');
    }

    /**
     * Test connection to the platform.
     */
    public function testConnection(EcommercePlatform $platform)
    {
        $service = new WooCommerceService($platform);
        $success = $service->testConnection();

        if ($success) {
            return response()->json(['success' => true, 'message' => 'Bağlantı başarılı!']);
        }

        return response()->json(['success' => false, 'message' => 'Bağlantı hatası! Lütfen bilgileri kontrol edin.'], 400);
    }
}
