<?php

namespace Modules\HumanResources\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        // Fetch roles with their associated department
        // Note: We need to define the relationship on the Role model extension or just query it manually if not extended.
        // Assuming standard Spatie model, we might need a workaround or extend it.
        // For now, let's just join or lazy load if we extended it. 
        // Since we didn't extend Spatie Role model yet, we can't use 'with'.
        // Let's just user query builder or a simple loop for now to avoid complexity of extending Spatie package model right now.
        // Actually, easiest is to just get them.
        $roles = \Spatie\Permission\Models\Role::where('name', '!=', 'SuperAdmin')
            ->orderBy('department_id') // Group by department logic
            ->latest()
            ->paginate(10);
            
        // We will need to manually fetch departments for display in index if we want to show it column.
        $departments = \Modules\HumanResources\Models\Department::all()->keyBy('id');
        
        return view('humanresources::roles.index', compact('roles', 'departments'));
    }

    public function create()
    {
        $departments = \Modules\HumanResources\Models\Department::all();
        return view('humanresources::roles.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $role = \Spatie\Permission\Models\Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web'
        ]);
        
        // Manually update department_id since standard create might not allow it if fillable not set (Spatie Role guuarded is usually * but let's be safe)
        // Spatie Role standard model is guarded = []. So we can pass it if we add it to array safely. 
        // But better:
        if (!empty($validated['department_id'])) {
            $role->department_id = $validated['department_id'];
            $role->save();
        }

        return redirect()->route('hr.roles.index')->with('success', 'Rol başarıyla oluşturuldu.');
    }

    public function edit(\Spatie\Permission\Models\Role $role)
    {
        if ($role->name === 'SuperAdmin') {
            abort(403, 'Süper Admin rolü düzenlenemez.');
        }
        $departments = \Modules\HumanResources\Models\Department::all();
        return view('humanresources::roles.edit', compact('role', 'departments'));
    }

    public function update(Request $request, \Spatie\Permission\Models\Role $role)
    {
        if ($role->name === 'SuperAdmin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $role->update(['name' => $validated['name']]);
        
        if (isset($validated['department_id'])) {
            $role->department_id = $validated['department_id'];
            $role->save();
        } else {
             $role->department_id = null;
             $role->save();
        }

        return redirect()->route('hr.roles.index')->with('success', 'Rol başarıyla güncellendi.');
    }

    public function destroy(\Spatie\Permission\Models\Role $role)
    {
        if ($role->name === 'SuperAdmin') {
            abort(403, 'Süper Admin rolü silinemez.');
        }

        $role->delete();

        return redirect()->route('hr.roles.index')->with('success', 'Rol başarıyla silindi.');
    }
    public function show(\Spatie\Permission\Models\Role $role)
    {
        $departments = \Modules\HumanResources\Models\Department::all()->keyBy('id');
        return view('humanresources::roles.show', compact('role', 'departments'));
    }
}
