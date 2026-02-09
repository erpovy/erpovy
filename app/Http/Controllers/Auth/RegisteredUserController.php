<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create Company
        $company = \App\Models\Company::create([
            'name' => $request->company_name,
            'status' => 'active',
            'settings' => [
                'modules' => ['Accounting', 'CRM', 'Inventory'], // Default active modules
            ],
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Set the team (company) for permission context
        setPermissionsTeamId($company->id);
        
        // Create default roles for the new company
        // We use forceCreate because company_id is not in the default Spatie Role fillable array
        $adminRole = \Spatie\Permission\Models\Role::forceCreate(['name' => 'Admin', 'company_id' => $company->id, 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::forceCreate(['name' => 'Accountant', 'company_id' => $company->id, 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::forceCreate(['name' => 'USER', 'company_id' => $company->id, 'guard_name' => 'web']);

        // Assign Admin role
        $user->assignRole($adminRole);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
