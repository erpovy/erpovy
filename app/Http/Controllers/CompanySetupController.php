<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanySetupController extends Controller
{
    public function accounting()
    {
        $company = auth()->user()->company;
        $settings = $company?->settings ?? [];
        return view('setup.accounting', compact('company', 'settings'));
    }

    public function updateAccounting(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'tax_number' => 'required|string|max:20',
            'tax_office' => 'required|string|max:100',
            'address' => 'required|string',
            'city' => 'required|string|max:50',
            'district' => 'required|string|max:50',
            'email' => 'required|email|max:255',
        ]);

        $company = auth()->user()->company;
        if (!$company) {
            return back()->with('error', 'Bir hata oluştu! Şirket kaydı bulunamadı.');
        }

        $settings = $company->settings ?? [];
        $settings['company_details'] = $request->only([
            'title', 'tax_number', 'tax_office', 'mersis_no', 'trade_register_no',
            'address', 'city', 'district', 'phone', 'email', 'website'
        ]);
        
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('company_logos', 'public');
            $settings['company_details']['logo'] = $path;
        }

        $company->settings = $settings;
        $company->save();

        return back()->with('success', 'Şirket bilgileri başarıyla güncellendi.');
    }

    public function invoice()
    {
        $company = auth()->user()->company;
        $settings = $company?->settings ?? [];
        return view('setup.invoice', compact('company', 'settings'));
    }

    public function updateInvoice(Request $request)
    {
        $request->validate([
            'gib_username' => 'nullable|string|max:255',
            'gib_password' => 'nullable|string|max:255',
        ]);

        $company = auth()->user()->company;
        if (!$company) {
            return back()->with('error', 'Bir hata oluştu! Şirket kaydı bulunamadı.');
        }

        $company->gib_username = $request->gib_username;
        if ($request->filled('gib_password')) {
            $company->gib_password = $request->gib_password;
        }

        $company->save();

        return back()->with('success', 'Fatura ve e-posta ayarları başarıyla güncellendi.');
    }

    public function crm()
    {
        $company = auth()->user()->company;
        $settings = $company?->settings ?? [];
        return view('setup.crm', compact('company', 'settings'));
    }

    public function updateCrm(Request $request)
    {
        $request->validate([
            'default_pipeline_name' => 'nullable|string|max:255',
        ]);

        $company = auth()->user()->company;
        if (!$company) {
            return back()->with('error', 'Bir hata oluştu! Şirket kaydı bulunamadı.');
        }

        $settings = $company->settings ?? [];
        $settings['crm_settings'] = $request->only([
            'default_pipeline_name', 'auto_assign_leads', 
            'lead_source_options', 'deal_stages', 'lost_reasons'
        ]);

        $company->settings = $settings;
        $company->save();

        return back()->with('success', 'CRM kurulum ayarları başarıyla güncellendi.');
    }
}
