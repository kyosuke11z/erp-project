<?php

namespace App\Livewire\Finance;

use App\Exports\FinancialTransactionsExport;
use App\Models\FinancialTransaction;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.app')] // คอมเมนต์: ย้ายการกำหนด layout มาเป็น Attribute เพื่อความชัดเจนและเป็นมาตรฐานเดียวกับ Component อื่น
class FinancialReport extends Component
{
    use WithPagination;

    // กำหนดรูปแบบ Pagination ให้เข้ากับ Tailwind CSS
    protected $paginationTheme = 'tailwind';

    // Properties สำหรับรับค่าจากฟอร์มกรองข้อมูล
    public string $startDate;
    public string $endDate;

    /**
     * เมธอด mount() จะถูกเรียกเมื่อ component ถูกสร้างขึ้นครั้งแรก
     * ใช้สำหรับกำหนดค่าเริ่มต้นต่างๆ
     */
    public function mount(): void
    {
        // กำหนดค่าเริ่มต้นสำหรับตัวกรองวันที่ เป็นเดือนปัจจุบัน
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

    /**
     * เมธอด render() จะทำงานทุกครั้งที่มีการอัปเดตค่าใน component
     * และจะส่งข้อมูลไปแสดงผลที่ View
     */
    public function render()
    {
        // สร้าง query builder สำหรับกรองข้อมูลตามช่วงวันที่ที่เลือก
        // การใช้ when() ช่วยให้โค้ดสะอาดขึ้น และจะเพิ่มเงื่อนไขเมื่อค่าไม่ว่างเท่านั้น
        $query = FinancialTransaction::query()
            ->when($this->startDate, fn ($q) => $q->where('transaction_date', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->where('transaction_date', '<=', $this->endDate));

        // คำนวณยอดรวมรายรับจาก query ที่กรองแล้ว
        $totalIncome = (clone $query)->where('type', 'income')->sum('amount');

        // คำนวณยอดรวมรายจ่ายจาก query ที่กรองแล้ว
        $totalExpense = (clone $query)->where('type', 'expense')->sum('amount');

        // ดึงข้อมูลธุรกรรมทั้งหมดตามเงื่อนไข พร้อมแบ่งหน้า (paginate)
        $transactions = (clone $query)->latest('transaction_date')->paginate(15);

        // ส่งข้อมูลไปยัง view
        return view('livewire.finance.financial-report', [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $totalIncome - $totalExpense, // คำนวณยอดคงเหลือ
            'transactions' => $transactions,
        ]);
    }

    /**
     * คอมเมนต์: เมธอดสำหรับ Export ข้อมูลเป็นไฟล์ Excel
     */
    public function exportExcel()
    {
        // สร้างชื่อไฟล์พร้อมวันที่ปัจจุบัน
        $fileName = 'financial-report-' . Carbon::now()->format('Y-m-d') . '.xlsx';

        // เรียกใช้งาน Export class ที่สร้างไว้ และส่งค่าวันที่ไปให้
        return Excel::download(new FinancialTransactionsExport($this->startDate, $this->endDate), $fileName);
    }
}
