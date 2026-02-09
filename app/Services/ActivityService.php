<?php

namespace App\Services;

use Modules\Accounting\Models\Invoice;
use Modules\CRM\Models\Contact;
use Modules\Inventory\Models\Product;
use Illuminate\Support\Collection;

class ActivityService
{
    /**
     * Get consolidated activity feed from diffrent modules.
     *
     * @param int $limit
     * @return Collection
     */
    public function getActivities(int $limit = 10): Collection
    {
        $activities = collect();

        // 1. New Invoices
        $invoices = Invoice::where('company_id', auth()->user()->company_id)
            ->latest('created_at')
            ->take($limit)
            ->get()
            ->map(function ($invoice) {
            return [
                'type' => 'invoice',
                'icon' => 'receipt',
                'color' => 'blue',
                'text' => "Fatura #{$invoice->invoice_number} oluşturuldu",
                'description' => $invoice->contact ? $invoice->contact->name : null,
                'link' => route('accounting.invoices.show', $invoice->id),
                'time_raw' => $invoice->created_at,
                'time' => $invoice->created_at->diffForHumans(),
            ];
        });

        // 2. New Contacts
        $contacts = Contact::where('company_id', auth()->user()->company_id)
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($contact) {
            return [
                'type' => 'contact',
                'icon' => 'person_add',
                'color' => 'purple',
                'text' => "Yeni müşteri: {$contact->name}",
                'description' => $contact->type === 'company' ? 'Firma' : 'Şahıs',
                'link' => route('crm.contacts.show', $contact->id),
                'time_raw' => $contact->created_at,
                'time' => $contact->created_at->diffForHumans(),
            ];
        });

        // 3. New Products/Stock
        // Product uses BelongsToCompany trait which likely adds global scope, 
        // but adding explicit check ensures safety.
        $products = Product::where('company_id', auth()->user()->company_id)
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($product) {
            return [
                'type' => 'product',
                'icon' => 'inventory',
                'color' => 'orange',
                'text' => "Yeni ürün eklendi: {$product->name}",
                'description' => "Stok: {$product->stock}",
                'link' => route('inventory.products.edit', $product->id),
                'time_raw' => $product->created_at,
                'time' => $product->created_at->diffForHumans(),
            ];
        });
        
        if (class_exists(\Modules\Accounting\Models\Transaction::class)) {
            $transactions = \Modules\Accounting\Models\Transaction::where('company_id', auth()->user()->company_id)
                ->latest()
                ->take($limit)
                ->get()
                ->map(function ($transaction) {
                 return [
                    'type' => 'payment',
                    'icon' => 'payments',
                    'color' => 'green',
                    'text' => "Muhasebe işlemi: " . ($transaction->description ?? 'Fiş No: ' . $transaction->receipt_number),
                    'description' => number_format($transaction->total_amount ?? 0, 2) . ' ₺',
                    'link' => route('accounting.transactions.show', $transaction->id),
                    'time_raw' => $transaction->created_at,
                    'time' => $transaction->created_at->diffForHumans(),
                ];
            });
            $activities = $activities->merge($transactions);
        }

        // Merge all and sort by time
        return $activities->merge($invoices)
            ->merge($contacts)
            ->merge($products)
            ->sortByDesc('time_raw')
            ->values()
            ->take($limit);
    }
}
