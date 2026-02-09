<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyManagementController extends Controller
{
    public function index()
    {
        $companies = Company::withCount('users')->latest()->paginate(10);
        return view('superadmin::companies.index', compact('companies'));
    }

    public function show(Company $company)
    {
        return view('superadmin::companies.show', compact('company'));
    }

    public function toggleModule(Request $request, Company $company)
    {
        $module = $request->input('module');
        $settings = $company->settings ?? [];
        
        $modules = $settings['modules'] ?? [];
        
        if (in_array($module, $modules)) {
            $modules = array_filter($modules, fn($m) => $m !== $module);
        } else {
            $modules[] = $module;
        }
        
        $settings['modules'] = array_values($modules);
        $company->settings = $settings;
        $company->save();
        return back()->with('success', "Modül durumu güncellendi.");
    }

    public function inspect(Company $company)
    {
        // Store original admin data to allow returning
        session([
            'is_inspecting' => true,
            'inspected_company_id' => $company->id,
            'inspected_company_name' => $company->name,
            'original_admin_id' => auth()->id()
        ]);

        return redirect()->route('dashboard')->with('info', "{$company->name} paneli gözetim modunda açıldı.");
    }

    public function updateLocation(Request $request, Company $company)
    {
        $validated = $request->validate([
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
        ]);

        $settings = $company->settings ?? [];
        $settings['country'] = $validated['country'];
        $settings['city'] = $validated['city'];
        
        $company->settings = $settings;
        $company->save();

        return back()->with('success', 'Konum ayarları güncellendi.');
    }

    public function edit(Company $company)
    {
        return view('superadmin::companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255|unique:companies,domain,' . $company->id,
            'status' => 'required|in:active,suspended',
        ]);

        $company->update($validated);

        return redirect()->route('superadmin.companies.index')->with('success', 'Şirket başarıyla güncellendi.');
    }

    public function destroy(Company $company)
    {
        // Prevent deletion if company has users
        if ($company->users()->exists()) {
            return back()->with('error', 'Bu şirkete ait kullanıcılar bulunduğu için silinemez.');
        }

        $company->delete();

        return redirect()->route('superadmin.companies.index')->with('success', 'Şirket başarıyla silindi.');
    }

    public function stopInspection()
    {
        session()->forget(['is_inspecting', 'inspected_company_id', 'inspected_company_name', 'original_admin_id']);

        return redirect()->route('superadmin.index')->with('success', 'Gözetim modu sonlandırıldı.');
    }
}
