<?php

namespace Modules\HumanResources\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HumanResources\Models\Department;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return view('humanresources::departments.index', compact('departments'));
    }

    public function create()
    {
        return view('humanresources::departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('departments')->where(fn ($query) => $query->where('company_id', auth()->user()->company_id))
            ],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        Department::create($validated);

        return redirect()->route('hr.departments.index')
            ->with('success', 'Departman başarıyla oluşturuldu.');
    }

    public function edit(Department $department)
    {
        return view('humanresources::departments.create', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('departments')->where(fn ($query) => $query->where('company_id', auth()->user()->company_id))->ignore($department->id)
            ],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $department->update($validated);

        return redirect()->route('hr.departments.index')
            ->with('success', 'Departman başarıyla güncellendi.');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->exists()) {
            return back()->with('error', 'Bu departmana bağlı personeller olduğu için silinemez.');
        }

        $department->delete();

        return redirect()->route('hr.departments.index')
            ->with('success', 'Departman başarıyla silindi.');
    }
}
