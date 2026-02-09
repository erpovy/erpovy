<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stok Analiz Raporu</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #f0f0f0;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        .risk-critical { color: #dc2626; font-weight: bold; }
        .risk-high { color: #ea580c; }
        .risk-medium { color: #ca8a04; }
        .risk-low { color: #16a34a; }
        .abc-a { background-color: #fee2e2; }
        .abc-b { background-color: #fef3c7; }
        .abc-c { background-color: #dcfce7; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>STOK ANALİZ RAPORU</h1>
        <p>Rapor Tarihi: {{ now()->format('d.m.Y H:i') }}</p>
        <p>Toplam Ürün: {{ count($products) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Ürün Kodu</th>
                <th>Ürün Adı</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Min</th>
                <th>Max</th>
                <th>ABC</th>
                <th>Günlük Sat.</th>
                <th>Devir</th>
                <th>Gün</th>
                <th>Risk</th>
                <th>Durum</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product['code'] }}</td>
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['category'] }}</td>
                    <td style="text-align: right;">{{ $product['current_stock'] }}</td>
                    <td style="text-align: right;">{{ $product['min_stock'] }}</td>
                    <td style="text-align: right;">{{ $product['max_stock'] }}</td>
                    <td class="abc-{{ strtolower($product['abc_class'] ?? 'c') }}" style="text-align: center;">
                        {{ $product['abc_class'] ?? '-' }}
                    </td>
                    <td style="text-align: right;">{{ $product['daily_avg_sales'] }}</td>
                    <td style="text-align: right;">{{ $product['turnover'] }}x</td>
                    <td style="text-align: right;">{{ $product['days_of_stock'] }}</td>
                    <td style="text-align: center;" class="
                        @if($product['stockout_risk'] >= 80) risk-critical
                        @elseif($product['stockout_risk'] >= 60) risk-high
                        @elseif($product['stockout_risk'] >= 40) risk-medium
                        @else risk-low
                        @endif
                    ">
                        {{ $product['stockout_risk'] }}
                    </td>
                    <td>{{ $product['status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Erpovy X1M - Gelişmiş Stok Yönetim Sistemi</p>
        <p>Bu rapor otomatik olarak oluşturulmuştur.</p>
    </div>
</body>
</html>
