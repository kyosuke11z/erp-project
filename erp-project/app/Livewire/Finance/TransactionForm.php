<?php

namespace App\Livewire\Finance;

use App\Models\FinanceCategory;
use App\Models\FinancialTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class TransactionForm extends Component
{
    public bool $showModal = false;
    public ?int $transactionId = null;

    // คอมเมนต์: กำหนด Rules สำหรับ Validation
    #[Rule('required|in:income,expense')]
    public string $type = 'expense';

    #[Rule('required|date')]
    public string $transaction_date;

    #[Rule('required|numeric|min:0')]
    public string $amount = '';

    #[Rule('required|exists:finance_categories,id')]
    public ?int $finance_category_id = null;

    #[Rule('nullable|string|max:1000')]
    public string $description = '';

    public Collection $categories;

    /**
     * คอมเมนต์: ฟังก์ชัน Mount จะถูกเรียกเมื่อ Component ถูกสร้างขึ้นครั้งแรก
     */
    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
        $this->loadCategories();
    }

    /**
     * คอมเมนต์: โหลดหมวดหมู่ตามประเภท (รายรับ/รายจ่าย) ที่เลือก
     */
    public function loadCategories(): void
    {
        $this->categories = FinanceCategory::where('type', $this->type)->get();
        // คอมเมนต์: ถ้า category ที่เลือกไว้เดิมไม่อยู่ใน list ใหม่ ให้ reset ค่า
        if (!$this->categories->contains('id', $this->finance_category_id)) {
            $this->finance_category_id = null;
        }
    }

    /**
     * คอมเมนต์: ฟังก์ชัน Hook ที่จะถูกเรียกเมื่อค่า $type เปลี่ยน
     */
    public function updatedType(): void
    {
        $this->loadCategories();
    }

    /**
     * คอมเมนต์: ฟังก์ชัน Listener รอรับ Event เพื่อเปิด Modal
     */
    #[On('open-transaction-form')]
    public function openModal(int $id = null): void
    {
        $this->reset(); // รีเซ็ตค่าในฟอร์มก่อน
        $this->mount(); // เรียก mount เพื่อตั้งค่าเริ่มต้น

        if ($id) {
            $transaction = FinancialTransaction::findOrFail($id);
            $this->transactionId = $transaction->id;
            $this->type = $transaction->type;
            $this->transaction_date = $transaction->transaction_date->format('Y-m-d');
            $this->amount = $transaction->amount;
            $this->description = $transaction->description;
            $this->loadCategories(); // โหลดหมวดหมู่ให้ตรงกับ type
            $this->finance_category_id = $transaction->finance_category_id;
        }

        $this->showModal = true;
    }

    /**
     * คอมเมนต์: บันทึกข้อมูล
     */
    public function save(): void
    {
        $this->validate();

        FinancialTransaction::updateOrCreate(
            ['id' => $this->transactionId],
            array_merge($this->all(), ['user_id' => Auth::id()])
        );

        $this->dispatch('transaction-saved'); // ส่ง Event บอก List ให้ refresh
        $this->showModal = false;
        // อาจจะเพิ่ม Toast notification ตรงนี้ในอนาคต
    }

    public function render()
    {
        return view('livewire.finance.transaction-form');
    }
}