<?php

namespace App\Livewire\Finance;

use App\Models\FinancialTransaction;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class TransactionList extends Component
{
    // คอมเมนต์: เรียกใช้ Trait สำหรับการแบ่งหน้า (Pagination)
    use WithPagination;

    // คอมเมนต์: Properties สำหรับผูกกับข้อมูลใน View (ตัวกรอง)
    public string $search = '';
    public string $filterType = ''; // 'income', 'expense', หรือ '' สำหรับทั้งหมด
    public string $startDate = '';
    public string $endDate = '';

    /**
     * คอมเมนต์: Listener รอรับ event 'transaction-saved' จากฟอร์ม
     * ไม่ต้องใส่โค้ดข้างใน เพราะ Livewire จะ re-render component นี้ให้อัตโนมัติ
     */
    #[On('transaction-saved')]
    public function refreshList(): void
    {
    }

    /**
     * คอมเมนต์: ฟังก์ชันนี้จะถูกเรียกเมื่อ component ถูก render หรือมีการอัปเดต
     * ทำหน้าที่ดึงข้อมูลและส่งไปยัง View
     */
    public function render()
    {
        // คอมเมนต์: เริ่มต้น Query ข้อมูลโดยดึงความสัมพันธ์ 'category' และ 'user' มาด้วยเพื่อลด N+1 problem
        $query = FinancialTransaction::with(['category', 'user'])
            // คอมเมนต์: กรองตามคำค้นหาใน description
            ->when($this->search, fn ($q) => $q->where('description', 'like', '%' . $this->search . '%'))
            // คอมเมนต์: กรองตามประเภท (รายรับ/รายจ่าย)
            ->when($this->filterType, fn ($q) => $q->where('type', $this->filterType))
            // คอมเมนต์: กรองตามวันที่เริ่มต้น
            ->when($this->startDate, fn ($q) => $q->whereDate('transaction_date', '>=', $this->startDate))
            // คอมเมนต์: กรองตามวันที่สิ้นสุด
            ->when($this->endDate, fn ($q) => $q->whereDate('transaction_date', '<=', $this->endDate))
            // คอมเมนต์: เรียงลำดับจากวันที่ล่าสุดไปเก่าสุด
            ->latest('transaction_date');

        return view('livewire.finance.transaction-list', [
            'transactions' => $query->paginate(15), // แบ่งหน้าแสดงผลทีละ 15 รายการ
        ]);
    }

    /**
     * คอมเมนต์: ฟังก์ชัน Hook ที่จะถูกเรียกทุกครั้งที่ค่า search, filterType, startDate, endDate มีการเปลี่ยนแปลง
     * เพื่อรีเซ็ตหน้า Pagination กลับไปที่หน้า 1
     */
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'filterType', 'startDate', 'endDate'])) {
            $this->resetPage();
        }
    }
}