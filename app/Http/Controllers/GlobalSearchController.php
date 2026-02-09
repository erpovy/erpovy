<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CRM\Models\Contact;
use Modules\Accounting\Models\Invoice;
use Modules\Inventory\Models\Product;
use Illuminate\Support\Collection;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = new Collection();

        // 1. Search CRM Contacts & Companies
        if (class_exists(Contact::class)) {
            $contacts = Contact::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('tax_number', 'like', "%{$query}%")
                ->take(5)
                ->get();

            foreach ($contacts as $contact) {
                $results->push([
                    'type' => 'CRM',
                    'title' => $contact->name,
                    'description' => $contact->type === 'company' ? 'Firma - ' . $contact->email : 'Kişi - ' . $contact->email,
                    'url' => route('crm.contacts.show', $contact->id),
                    'icon' => $contact->type === 'company' ? 'corporate_fare' : 'person'
                ]);
            }
        }

        // 2. Search Accounting Invoices
        if (class_exists(Invoice::class)) {
            $invoices = Invoice::with('contact')
                ->where('invoice_number', 'like', "%{$query}%")
                ->orWhereHas('contact', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->take(5)
                ->get();

            foreach ($invoices as $invoice) {
                $results->push([
                    'type' => 'Muhasebe',
                    'title' => $invoice->invoice_number,
                    'description' => 'Fatura - ' . ($invoice->contact ? $invoice->contact->name : 'Bilinmeyen'),
                    'url' => route('accounting.invoices.show', $invoice->id),
                    'icon' => 'receipt_long'
                ]);
            }
        }

        // 3. Search Inventory Products
        if (class_exists(Product::class)) {
            $products = Product::where('name', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%")
                ->take(5)
                ->get();

            foreach ($products as $product) {
                $results->push([
                    'type' => 'Stok',
                    'title' => $product->name,
                    'description' => 'Kod: ' . $product->code . ' - Stok: ' . $product->stock,
                    'url' => route('inventory.products.edit', $product->id),
                    'icon' => 'inventory_2'
                ]);
            }
        }

        return response()->json($results);
    }
}
