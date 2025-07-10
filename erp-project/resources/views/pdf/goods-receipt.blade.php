<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ใบรับสินค้า - {{ $goodsReceipt->receipt_number }}</title>
    <style>
        /* Ensure you have a Thai font like 'sarabun' configured in config/pdf.php */
        body {
            font-family: 'sarabun', sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .details-grid { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .details-grid td { padding: 5px; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th, .items-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .footer-table { width: 100%; margin-top: 50px; border-collapse: collapse; }
        .footer-table td { width: 50%; text-align: center; padding-top: 40px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>ใบรับสินค้า (Goods Receipt Note)</h1>
    </div>

    <table class="details-grid">
        <tr>
            <td style="width: 50%;">
                <strong>เลขที่ใบรับสินค้า:</strong> {{ $goodsReceipt->receipt_number }}<br>
                <strong>วันที่รับ:</strong> {{ $goodsReceipt->receipt_date }}<br>
                <strong>ผู้บันทึก:</strong> {{ $goodsReceipt->createdBy->name ?? 'N/A' }}
            </td>
            <td style="width: 50%;">
                <strong>ซัพพลายเออร์:</strong> {{ $goodsReceipt->purchaseOrder->supplier->name }}<br>
                <strong>อ้างอิงใบสั่งซื้อ:</strong> {{ $goodsReceipt->purchaseOrder->po_number }}<br>
            </td>
        </tr>
        @if($goodsReceipt->notes)
        <tr>
            <td colspan="2" style="padding-top: 10px;">
                <strong>หมายเหตุ:</strong> {{ $goodsReceipt->notes }}
            </td>
        </tr>
        @endif
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 20%;">รหัสสินค้า (SKU)</th>
                <th>รายการสินค้า</th>
                <th class="text-right" style="width: 25%;">จำนวนที่ได้รับ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($goodsReceipt->items as $item)
                <tr>
                    <td>{{ $item->product->sku }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-right">{{ number_format($item->quantity_received) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td>
                <p>.................................................</p>
                <p>ผู้ส่งสินค้า (Supplier)</p>
            </td>
            <td>
                <p>.................................................</p>
                <p>ผู้รับสินค้า (Receiver)</p>
            </td>
        </tr>
    </table>

</body>
</html>