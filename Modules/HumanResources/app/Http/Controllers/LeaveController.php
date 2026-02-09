<?php

namespace Modules\HumanResources\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HumanResources\Models\Employee;
use Modules\HumanResources\Models\Leave;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource (Calendar).
     */
    public function index()
    {
        $leaves = Leave::with('employee')
            ->where('company_id', auth()->user()->company_id)
            ->get()
            ->map(function ($leave) {
                return [
                    'id' => $leave->id,
                    'title' => ($leave->employee?->full_name ?? 'Silinmiş Personel') . ' - ' . $leave->type,
                    'start' => $leave->start_date->format('Y-m-d'),
                    'end' => $leave->end_date->format('Y-m-d'),
                    'type' => $leave->type,
                    'status' => $leave->status,
                    'employee_name' => $leave->employee?->full_name ?? 'Silinmiş Personel',
                    'description' => $leave->description,
                ];
            });

        $employees = Employee::where('company_id', auth()->user()->company_id)
            ->where('status', 'active')
            ->get();

        return view('humanresources::leaves.calendar', compact('leaves', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['status'] = 'approved'; // Auto-approve for now or based on role

        Leave::create($validated);

        return redirect()->route('hr.leaves.index')->with('success', 'İzin kaydı oluşturuldu.');
    }
}
