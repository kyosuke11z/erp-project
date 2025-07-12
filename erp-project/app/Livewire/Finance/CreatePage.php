<?php

namespace App\Livewire\Finance;

use App\Models\FinanceCategory;
use App\Models\FinancialTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.app')] // คอมเมนต์: กำหนดให้ใช้ Layout หลักของแอป
class CreatePage extends Component
{
    // คอมเมนต์: Properties ของฟอร์มพร้อม Validation Rules
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

    public function mount()
    {
        $this->transaction_date = now()->format('Y-m-d');
        $this->loadCategories();
    }

    public function loadCategories(): void
    {
        $this->categories = FinanceCategory::where('type', $this->type)->get();
        if (!$this->categories->contains('id', $this->finance_category_id)) {
            $this->finance_category_id = null;
        }
    }

    public function updatedType(): void
    {
         // คอมเมนต์: เพิ่มการรีเซ็ตค่าหมวดหมู่ที่เลือกไว้ เมื่อมีการเปลี่ยนประเภท
        $this->finance_category_id = null;
    }

    public function save(): void
    {
        $this->validate();

        FinancialTransaction::create(
            array_merge($this->all(), ['user_id' => Auth::id()])
        );

        // คอมเมนต์: ส่งข้อความแจ้งเตือนกลับไปที่หน้า List
        session()->flash('success', 'บันทึกรายการเรียบร้อยแล้ว');

        // คอมเมนต์: กลับไปยังหน้าหลัก
        $this->redirect(route('finance.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.finance.create-page');
    }
}