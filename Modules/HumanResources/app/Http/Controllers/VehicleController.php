<?php

namespace Modules\HumanResources\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HumanResources\Models\Employee;
use Modules\HumanResources\Models\Vehicle;
use Modules\HumanResources\Models\VehicleExpense;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['employee', 'expenses'])
            ->where('company_id', auth()->user()->company_id)
            ->get();

        return view('humanresources::fleet.index', compact('vehicles'));
    }

    public function create()
    {
        $employees = Employee::where('company_id', auth()->user()->company_id)
            ->where('status', 'active')
            ->get();

        return view('humanresources::fleet.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20',
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'employee_id' => 'nullable|exists:employees,id',
            'status' => 'required|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        Vehicle::create($validated);

        return redirect()->route('hr.fleet.index')->with('success', 'Araç başarıyla eklendi.');
    }

    public function edit(Vehicle $vehicle)
    {
        if ($vehicle->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $employees = Employee::where('company_id', auth()->user()->company_id)->get();

        return view('humanresources::fleet.edit', compact('vehicle', 'employees'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'plate_number' => 'required|string|max:20',
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer',
            'employee_id' => 'nullable|exists:employees,id',
            'status' => 'required|string',
        ]);

        $vehicle->update($validated);

        return redirect()->route('hr.fleet.index')->with('success', 'Araç bilgileri güncellendi.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $vehicle->delete();
        return redirect()->route('hr.fleet.index')->with('success', 'Araç silindi.');
    }

    public function storeExpense(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        
        $vehicle->expenses()->create($validated);

        return redirect()->back()->with('success', 'Masraf eklendi.');
    }
}
