<?php

namespace Modules\HumanResources\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HumanResources\Models\Employee;
use Modules\HumanResources\Models\Department;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::where('company_id', auth()->user()->company_id)
            ->latest()
            ->paginate(10);
            
        return view('humanresources::employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('company_id', auth()->user()->company_id)->where('is_active', true)->get();
        return view('humanresources::employees.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,terminated',
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, &$validated) {
                if ($request->has('create_user')) {
                     $validatedUser = $request->validate([
                        'password' => 'required|string|min:8',
                        'role' => 'nullable|string|exists:roles,name',
                     ]);
        
                     if (empty($validated['email'])) {
                         throw \Illuminate\Validation\ValidationException::withMessages(['email' => 'Kullanıcı hesabı oluşturmak için E-posta adresi zorunludur.']);
                     }
        
                     // Check if user exists globally (ignoring company scope)
                     if (\App\Models\User::withoutGlobalScopes()->where('email', $validated['email'])->exists()) {
                          throw \Illuminate\Validation\ValidationException::withMessages(['email' => 'Bu E-posta adresi ile kayıtlı bir kullanıcı zaten var.']);
                     }
        
                     $user = \App\Models\User::create([
                         'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                         'email' => $validated['email'],
                         'password' => \Illuminate\Support\Facades\Hash::make($validatedUser['password']),
                         'company_id' => auth()->user()->company_id,
                     ]);
                     
                     // Assign Role
                     setPermissionsTeamId($user->company_id);
                     $user->assignRole($request->input('role', 'User'));
        
                     $validated['user_id'] = $user->id;
                }
        
                Employee::create($validated);
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->with('error', 'Bir hata oluştu: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('hr.employees.index')
            ->with('success', 'Çalışan başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        if ($employee->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $departments = Department::where('company_id', auth()->user()->company_id)->where('is_active', true)->get();

        return view('humanresources::employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        if ($employee->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:100',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,terminated',
        ]);

        $employee->update($validated);

        return redirect()->route('hr.employees.index')
            ->with('success', 'Çalışan bilgileri güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        if ($employee->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $employee->delete();

        return redirect()->route('hr.employees.index')
            ->with('success', 'Çalışan silindi.');
    }
}
