<?php

namespace Modules\HumanResources\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = \Spatie\Permission\Models\Permission::latest()->paginate(10);
        return view('humanresources::permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('humanresources::permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web'
        ]);

        return redirect()->route('hr.permissions.index')->with('success', 'Yetki başarıyla oluşturuldu.');
    }

    public function edit($id)
    {
        $permission = \Spatie\Permission\Models\Permission::findOrFail($id);
        return view('humanresources::permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = \Spatie\Permission\Models\Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        $permission->update([
            'name' => $validated['name']
        ]);

        return redirect()->route('hr.permissions.index')->with('success', 'Yetki başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $permission = \Spatie\Permission\Models\Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('hr.permissions.index')->with('success', 'Yetki başarıyla silindi.');
    }
}
