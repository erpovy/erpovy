<?php

namespace Modules\Manufacturing\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Manufacturing\Models\MaintenanceRecord;
use Modules\Manufacturing\Models\WorkStation;
use App\Http\Controllers\Controller;

class MaintenanceController extends Controller
{
    public function index()
    {
        $scope = MaintenanceRecord::where('company_id', auth()->user()->company_id);
        
        $stats = [
            'planned' => (clone $scope)->where('status', 'planned')->count(),
            'in_process' => (clone $scope)->where('status', 'in_process')->count(),
            'completed' => (clone $scope)->where('status', 'completed')->count(),
            'total_cost' => (clone $scope)->sum('cost'),
        ];

        $records = $scope->with('workStation')
            ->orderByRaw("FIELD(status, 'in_process', 'planned', 'completed')")
            ->latest('start_date')
            ->paginate(10);
            
        $stations = WorkStation::where('company_id', auth()->user()->company_id)->get();

        return view('manufacturing::maintenance.index', compact('records', 'stations', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'work_station_id' => 'required|exists:work_stations,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:preventive,corrective,emergency',
            'priority' => 'required|in:low,medium,high',
            'start_date' => 'required|date',
        ]);

        MaintenanceRecord::create([
            'company_id' => auth()->user()->company_id,
            'work_station_id' => $request->work_station_id,
            'title' => $request->title,
            'type' => $request->type,
            'status' => 'planned',
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'description' => $request->description,
        ]);

        return redirect()->route('manufacturing.maintenance.index')->with('success', 'Bakım kaydı oluşturuldu.');
    }

    public function update(Request $request, $id)
    {
        $record = MaintenanceRecord::where('company_id', auth()->user()->company_id)->findOrFail($id);

        if ($request->action == 'complete') {
            $record->update([
                'status' => 'completed',
                'end_date' => now(),
                'cost' => $request->cost ?? 0,
                'technician_name' => $request->technician_name,
                'description' => $request->description ?? $record->description,
            ]);
        } elseif ($request->action == 'start') {
            $record->update(['status' => 'in_process']);
        }

        return back()->with('success', 'Bakım kaydı güncellendi.');
    }
}
