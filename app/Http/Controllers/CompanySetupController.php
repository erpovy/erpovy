<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanySetupController extends Controller
{
    public function accounting()
    {
        $company = auth()->user()->company;
        
        // Ensure settings is valid array/object
        $settings = $company->settings ?? [];
        
        return view('setup.accounting', compact('company', 'settings'));
    }

    public function updateAccounting(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'tax_number' => 'required|string|max:20',
            'tax_office' => 'required|string|max:100',
            'mersis_no' => 'nullable|string|max:50',
            'trade_register_no' => 'nullable|string|max:50',
            'address' => 'required|string',
            'city' => 'required|string|max:50',
            'district' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $company = auth()->user()->company;
        $settings = $company->settings ?? [];

        // Update specific accounting fields
        $settings['company_details'] = $request->only([
            'title', 'tax_number', 'tax_office', 'mersis_no', 'trade_register_no',
            'address', 'city', 'district', 'phone', 'email', 'website'
        ]);
        
        // Handle Logo Upload
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
        $settings = $company->settings ?? [];
        
        return view('setup.invoice', compact('company', 'settings'));
    }

    public function updateInvoice(Request $request)
    {
        $request->validate([
            'gib_username' => 'nullable|string|max:255',
            'gib_password' => 'nullable|string|max:255',
        ]);

        $company = auth()->user()->company;
        $settings = $company->settings ?? [];

        // Save GIB Credentials to Company columns
        $company->gib_username = $request->gib_username;
        if ($request->filled('gib_password')) {
            $company->gib_password = $request->gib_password;
        }

        // Update invoice specific settings
        // $settings['invoice_settings'] = $request->only([
        //     'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 
        //     'smtp_encryption', 'mail_from_address', 'mail_from_name', 
        //     'invoice_template', 'pdf_template'
        // ]);

        $company->settings = $settings;
        $company->save();

        return back()->with('success', 'Fatura ve e-posta ayarları başarıyla güncellendi.');
    }
    public function crm()
    {
        $company = auth()->user()->company;
        $settings = $company->settings ?? [];
        
        return view('setup.crm', compact('company', 'settings'));
    }

    public function updateCrm(Request $request)
    {
        $request->validate([
            'default_pipeline_name' => 'nullable|string|max:255',
            'auto_assign_leads' => 'nullable|boolean',
            'lead_source_options' => 'nullable|string', // Comma separated
            'deal_stages' => 'nullable|string', // Comma separated
            'lost_reasons' => 'nullable|string', // NEW
        ]);

        $company = auth()->user()->company;
        $settings = $company->settings ?? [];

        // Update CRM specific settings
        $settings['crm_settings'] = $request->only([
            'default_pipeline_name', 'auto_assign_leads', 
            'lead_source_options', 'deal_stages', 'lost_reasons'
        ]);

        $company->settings = $settings;
        $company->save();

        return back()->with('success', 'CRM kurulum ayarları başarıyla güncellendi.');
    }
}
