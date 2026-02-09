<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fatura #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
        }
        .invoice-title {
            float: right;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
        }
        .details-box {
            width: 100%;
            margin-bottom: 30px;
        }
        .client-info {
            width: 50%;
            float: left;
        }
        .invoice-info {
            width: 40%;
            float: right;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #ddd;
            text-align: left;
            padding: 10px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            color: #666;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            width: 40%;
            float: right;
        }
        .totals-row {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .totals-row.grand {
            border-bottom: 2px solid #333;
            font-size: 14px;
            font-weight: bold;
            color: #4F46E5;
            margin-top: 10px;
            padding-top: 10px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">Erpovy X1M</div>
            <div class="invoice-title">SATIS FATURASI</div>
            <div style="clear: both;"></div>
        </div>

        <!-- Details -->
        <div class="details-box">
            <div class="client-info">
                <strong>SAYIN:</strong><br>
                {{ $invoice->contact->name }}<br>
                {{ $invoice->contact->address ?? 'Adres bilgisi yok' }}<br>
                {{ $invoice->contact->tax_number ? 'Vergi No: ' . $invoice->contact->tax_number : '' }}
            </div>
            <div class="invoice-info">
                <strong>Fatura Bilgileri</strong><br>
                No: #{{ $invoice->invoice_number }}<br>
                Tarih: {{ \Carbon\Carbon::parse($invoice->issue_date)->format('d.m.Y') }}<br>
                Vade: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d.m.Y') }}
            </div>
            <div style="clear: both;"></div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 40%">Açıklama</th>
                    <th class="text-right">Miktar</th>
                    <th class="text-right">Birim Fiyat</th>
                    <th class="text-right">KDV %</th>
                    <th class="text-right">Tutar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>
                            {{ $item->description }}
                            @if($item->product)
                                <br><small style="color: #999;">{{ $item->product->code }}</small>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2) }} ₺</td>
                        <td class="text-right">%{{ number_format($item->tax_rate, 0) }}</td>
                        <td class="text-right">{{ number_format($item->total, 2) }} ₺</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-row">
                <span class="label">Ara Toplam:</span>
                <span style="float: right;">{{ number_format($invoice->total_amount - $invoice->tax_amount, 2) }} ₺</span>
                <div style="clear: both;"></div>
            </div>
            <div class="totals-row">
                <span class="label">KDV Toplam:</span>
                <span style="float: right;">{{ number_format($invoice->tax_amount, 2) }} ₺</span>
                <div style="clear: both;"></div>
            </div>
            <div class="totals-row grand">
                <span class="label">GENEL TOPLAM:</span>
                <span style="float: right;">{{ number_format($invoice->total_amount, 2) }} ₺</span>
                <div style="clear: both;"></div>
            </div>
        </div>
        <div style="clear: both;"></div>

        <!-- Footer -->
        <div class="footer">
            Bu belge elektronik ortamda oluşturulmuştur.
            <br>
            Erpovy X1M - Yeni Nesil ERP Sistemi
        </div>
    </div>
</body>
</html>
