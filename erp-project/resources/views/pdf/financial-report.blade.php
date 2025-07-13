<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>รายงานสรุปรายรับ-รายจ่าย</title>
    <style>
        /* คอมเมนต์: mPDF จะดึงฟอนต์ 'thsarabunnew' จากที่ตั้งค่าไว้ใน config/pdf.php */
        body {
            font-family: 'thsarabunnew', sans-serif;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>รายงานสรุปรายรับ-รายจ่าย</h1>
    <p>ช่วงวันที่: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} ถึง {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>วันที่</th>
                <th>รายการ</th>
                <th>ประเภท</th>
                <th class="text-right">จำนวนเงิน</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</td>
                <td>{{ $transaction->description }}</td>
                <td>{{ $transaction->type === 'income' ? 'รายรับ' : 'รายจ่าย' }}</td>
                <td class="text-right">{{ number_format($transaction->amount, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right">รายรับรวม:</td>
                <td class="text-right">{{ number_format($totalIncome, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right">รายจ่ายรวม:</td>
                <td class="text-right">{{ number_format($totalExpense, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right">ยอดคงเหลือสุทธิ:</td>
                <td class="text-right">{{ number_format($netBalance, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>