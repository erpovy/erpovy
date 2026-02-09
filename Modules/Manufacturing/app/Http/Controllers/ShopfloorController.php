<?php

namespace Modules\Manufacturing\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Manufacturing\Models\WorkStation;
use App\Http\Controllers\Controller;

class ShopfloorController extends Controller
{
    public function index()
    {
        $scope = WorkStation::where('company_id', auth()->user()->company_id);
        
        $stats = [
            'total' => (clone $scope)->count(),
            'active' => (clone $scope)->where('status', 'active')->count(),
            'maintenance' => (clone $scope)->where('status', 'maintenance')->count(),
            'offline' => (clone $scope)->where('status', 'offline')->count(),
        ];

        $stations = $scope->latest()->paginate(12);

        return view('manufacturing::shopfloor.index', compact('stations', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:work_stations,code',
            'type' => 'required|in:machine,assembly,packaging,other',
            'status' => 'required|in:active,maintenance,offline',
            'capacity' => 'required|integer|min:0',
        ]);

        WorkStation::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'status' => $request->status,
            'capacity' => $request->capacity,
            'location' => $request->location,
            'hourly_rate' => $request->hourly_rate ?? 0,
        ]);

        return redirect()->route('manufacturing.shopfloor.index')->with('success', 'İş istasyonu başarıyla eklendi.');
    }
}
