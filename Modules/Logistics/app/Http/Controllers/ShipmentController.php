<?php

namespace Modules\Logistics\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Logistics\Models\Shipment;
use Modules\CRM\Models\Contact;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::where('company_id', auth()->user()->company_id)
            ->with('contact')
            ->latest()
            ->paginate(15);
            
        return view('logistics::shipments.index', compact('shipments'));
    }

    public function create()
    {
        $company = auth()->user()->company;
        $settings = $company->settings['logistics'] ?? [];
        $contacts = Contact::where('company_id', $company->id)->get();
        
        return view('logistics::shipments.create', compact('contacts', 'settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'tracking_number' => 'required|string|max:50|unique:logistics_shipments',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'status' => 'required|in:pending,in_transit,delivered,cancelled',
            'weight_kg' => 'nullable|numeric|min:0',
            'volume_m3' => 'nullable|numeric|min:0',
            'estimated_delivery' => 'nullable|date',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        
        Shipment::create($validated);

        return redirect()->route('logistics.shipments.index')
            ->with('success', 'Sevkiyat başarıyla oluşturuldu.');
    }

    public function edit(Shipment $shipment)
    {
        $contacts = Contact::where('company_id', auth()->user()->company_id)->get();
        return view('logistics::shipments.edit', compact('shipment', 'contacts'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'tracking_number' => 'required|string|max:50|unique:logistics_shipments,tracking_number,' . $shipment->id,
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'status' => 'required|in:pending,in_transit,delivered,cancelled',
            'weight_kg' => 'nullable|numeric|min:0',
            'volume_m3' => 'nullable|numeric|min:0',
            'estimated_delivery' => 'nullable|date',
        ]);

        $shipment->update($validated);

        return redirect()->route('logistics.shipments.index')
            ->with('success', 'Sevkiyat başarıyla güncellendi.');
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return redirect()->route('logistics.shipments.index')
            ->with('success', 'Sevkiyat başarıyla silindi.');
    }

    public function track(Request $request, $number = null)
    {
        $trackingNumber = $number ?: $request->get('number');
        $shipment = null;

        if ($trackingNumber) {
            $shipment = Shipment::where('tracking_number', $trackingNumber)
                ->with(['contact'])
                ->first();
        }

        return view('logistics::shipments.track', compact('shipment', 'trackingNumber'));
    }
}
