<?php

namespace Modules\Logistics\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = auth()->user()->company;
        $settings = $company->settings['logistics'] ?? [];
        
        return view('logistics::settings.index', compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tracking_prefix' => 'nullable|string|max:10',
            'default_carrier' => 'nullable|string|max:100',
            'auto_generate_tracking' => 'nullable|boolean',
            'default_origin' => 'nullable|string|max:255',
        ]);

        $company = auth()->user()->company;
        $allSettings = $company->settings ?? [];
        $allSettings['logistics'] = [
            'tracking_prefix' => $validated['tracking_prefix'] ?? 'TRK-',
            'default_carrier' => $validated['default_carrier'] ?? '',
            'auto_generate_tracking' => $request->has('auto_generate_tracking'),
            'default_origin' => $validated['default_origin'] ?? '',
        ];

        $company->update(['settings' => $allSettings]);

        return redirect()->route('logistics.settings.index')
            ->with('success', 'Lojistik ayarları başarıyla güncellendi.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return redirect()->route('logistics.settings.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return redirect()->route('logistics.settings.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        return $this->store($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
