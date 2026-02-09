<?php

namespace Modules\HumanResources\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('company_id', auth()->user()->company_id)
            ->with(['roles', 'employee'])
            ->latest()
            ->paginate(10);
            
        return view('humanresources::users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'SuperAdmin')->get(); // Hide SuperAdmin role
        return view('humanresources::users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company_id' => auth()->user()->company_id,
        ]);

        // Fix: Set permission team id for role assignment
        setPermissionsTeamId($user->company_id);
        $user->assignRole($validated['role']);

        return redirect()->route('hr.users.index')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function edit(User $user)
    {
        // Prevent editing users from other companies
        if ($user->company_id != auth()->user()->company_id) {
            abort(403);
        }

        $roles = Role::where('name', '!=', 'SuperAdmin')->get();
        return view('humanresources::users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->company_id != auth()->user()->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Fix: Set permission team id for role assignment
        setPermissionsTeamId($user->company_id);
        $user->syncRoles([$validated['role']]);

        // Update linked employee
        if ($user->employee) {
            $user->employee->update([
                'department' => $request->input('department'),
                'position' => $request->input('position'),
            ]);
        }

        return redirect()->route('hr.users.index')->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    public function destroy(User $user)
    {
        if ($user->company_id != auth()->user()->company_id) {
            abort(403);
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendinizi silemezsiniz.');
        }

        $user->delete();

        return redirect()->route('hr.users.index')->with('success', 'Kullanıcı başarıyla silindi.');
    }
}
