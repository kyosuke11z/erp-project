<?php

namespace App\Exports;

use App\Models\FinancialTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Carbon;

class FinancialTransactionsExport implements FromQuery, WithHeadings, WithMapping
{
    protected string $startDate;
    protected string $endDate;

    /**
     * คอมเมนต์: Constructor รับค่าวันที่เริ่มต้นและสิ้นสุดสำหรับกรองข้อมูล
     */
    public function __construct(string $startDate, string $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * คอมเมนต์: กำหนด Query สำหรับดึงข้อมูลตามช่วงวันที่ที่ระบุ
     */
    public function query()
    {
        return FinancialTransaction::query()
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->latest('transaction_date');
    }

    /**
     * คอมเมนต์: กำหนดหัวข้อของแต่ละคอลัมน์ในไฟล์ Excel
     */
    public function headings(): array
    {
        return [
            'วันที่',
            'รายการ',
            'ประเภท',
            'จำนวนเงิน',
            'หมวดหมู่',
        ];
    }

    /**
     * คอมเมนต์: จัดรูปแบบข้อมูลของแต่ละแถวก่อนจะถูกเขียนลงไฟล์
     * @param FinancialTransaction $transaction
     */
    public function map($transaction): array
    {
        return [
            Carbon::parse($transaction->transaction_date)->format('d/m/Y'),
            $transaction->description,
            $transaction->type === 'income' ? 'รายรับ' : 'รายจ่าย',
            $transaction->amount,
            $transaction->category->name ?? 'ไม่มีหมวดหมู่', // ดึงชื่อหมวดหมู่มาแสดง
        ];
    }
}