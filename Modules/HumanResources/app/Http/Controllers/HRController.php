<?php

namespace Modules\HumanResources\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\HumanResources\Models\Employee;
use Modules\HumanResources\Models\Department;
use Modules\HumanResources\Models\Leave;

class HRController extends Controller
{
    public function index(): View
    {
        $companyId = auth()->user()->company_id;

        // Statistics
        $totalEmployees = Employee::where('company_id', $companyId)->count();
        $activeEmployees = Employee::where('company_id', $companyId)
            ->where('status', 'active')
            ->count();
        $totalDepartments = Department::where('company_id', $companyId)
            ->where('is_active', true)
            ->count();
        $pendingLeaves = Leave::whereHas('employee', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('status', 'pending')->count();

        // Recent Employees (last 5)
        $recentEmployees = Employee::where('company_id', $companyId)
            ->with(['department', 'user'])
            ->latest('hire_date')
            ->take(5)
            ->get();

        // Leave Statistics
        $leaveStats = [
            'pending' => Leave::whereHas('employee', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('status', 'pending')->count(),
            'approved' => Leave::whereHas('employee', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('status', 'approved')->count(),
            'rejected' => Leave::whereHas('employee', function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })->where('status', 'rejected')->count(),
        ];

        // Department Distribution
        $departmentStats = Department::where('company_id', $companyId)
            ->where('is_active', true)
            ->withCount('employees')
            ->orderBy('employees_count', 'desc')
            ->take(5)
            ->get();

        return view('humanresources::index', compact(
            'totalEmployees',
            'activeEmployees',
            'totalDepartments',
            'pendingLeaves',
            'recentEmployees',
            'leaveStats',
            'departmentStats'
        ));
    }
}
