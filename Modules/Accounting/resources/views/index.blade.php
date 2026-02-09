@extends('accounting::layouts.master')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Muhasebe Fişleri</h1>
        
        <div class="bg-white shadow-md rounded my-6">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Tarih</th>
                        <th class="py-3 px-6 text-left">Fiş No</th>
                        <th class="py-3 px-6 text-left">Açıklama</th>
                        <th class="py-3 px-6 text-right">Borç</th>
                        <th class="py-3 px-6 text-right">Alacak</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse($transactions as $transaction)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $transaction->date->format('d.m.Y') }}</td>
                            <td class="py-3 px-6 text-left">{{ $transaction->receipt_number }}</td>
                            <td class="py-3 px-6 text-left">{{ $transaction->description }}</td>
                            <td class="py-3 px-6 text-right">{{ number_format($transaction->total_debit, 2) }}</td>
                            <td class="py-3 px-6 text-right">{{ number_format($transaction->total_credit, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-3 px-6 text-center">Kayıt bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="p-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection
