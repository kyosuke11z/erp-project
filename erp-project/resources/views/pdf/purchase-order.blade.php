<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ใบสั่งซื้อ (Purchase Order) - {{ $purchaseOrder->po_number }}</title>
    <style>
        /* ใช้ฟอนต์ Sarabun ที่เราตั้งค่าไว้ใน mPDF */
        body {
            font-family: 'sarabun', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header, .footer {
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .details-grid {
            width: 100%;
            margin-top: 20px;
        }
        .details-grid td {
            vertical-align: top;
            padding: 5px;
        }
        .supplier-info {
            width: 50%;
        }
        .po-info {
            width: 50%;
            text-align: right;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            margin-top: 20px;
            width: 100%;
        }
        .total-section td {
            padding: 5px;
        }
        .notes {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <table class="details-grid">
            <tr>
                <td>
                    <h1>ใบสั่งซื้อ / Purchase Order</h1>
                    <strong>บริษัทของคุณ</strong><br>
                    ที่อยู่บริษัท<br>
                    เบอร์โทรศัพท์, อีเมล
                </td>
                <td class="po-info">
                    <h2>เลขที่: {{ $purchaseOrder->po_number }}</h2>
                    <p><strong>วันที่สั่งซื้อ:</strong> {{ $purchaseOrder->order_date->format('d/m/Y') }}</p>
                    @if($purchaseOrder->expected_delivery_date)
                        <p><strong>วันที่คาดว่าจะได้รับ:</strong> {{ $purchaseOrder->expected_delivery_date->format('d/m/Y') }}</p>
                    @endif
                </td>
            </tr>
        </table>

        <hr>

        <table class="details-grid">
            <tr>
                <td class="supplier-info">
                    <strong>ซัพพลายเออร์:</strong><br>
                    <strong>{{ $purchaseOrder->supplier->name }}</strong><br>
                    {{ $purchaseOrder->supplier->address }}<br>
                    @if($purchaseOrder->supplier->contact_person)
                        ผู้ติดต่อ: {{ $purchaseOrder->supplier->contact_person }}<br>
                    @endif
                    @if($purchaseOrder->supplier->phone)
                        โทร: {{ $purchaseOrder->supplier->phone }}<br>
                    @endif
                    @if($purchaseOrder->supplier->email)
                        อีเมล: {{ $purchaseOrder->supplier->email }}
                    @endif
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 45%;">รายการสินค้า</th>
                    <th style="width: 15%;" class="text-right">จำนวน</th>
                    <th style="width: 15%;" class="text-right">ราคาต่อหน่วย</th>
                    <th style="width: 20%;" class="text-right">ราคารวม</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrder->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->product->name }}
                        @if($item->product->description)
                            <br><small style="color: #555;">{{ $item->product->description }}</small>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($item->quantity, 0) }}</td>
                    <td class="text-right">{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="total-section">
            <tr>
                <td style="width: 70%;"></td>
                <td style="width: 15%;" class="text-right"><strong>ยอดรวมสุทธิ</strong></td>
                <td style="width: 15%;" class="text-right"><strong>{{ number_format($purchaseOrder->total_amount, 2) }} บาท</strong></td>
            </tr>
        </table>

        @if($purchaseOrder->notes)
        <div class="notes">
            <strong>หมายเหตุ:</strong>
            <p>{{ $purchaseOrder->notes }}</p>
        </div>
        @endif

    </div>
</body>
</html>

