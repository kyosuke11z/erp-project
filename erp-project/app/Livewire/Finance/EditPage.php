<?php

namespace App\Livewire\Finance;

use App\Models\FinanceCategory;
use App\Models\FinancialTransaction;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('layouts.app')]
class EditPage extends Component
{
    public FinancialTransaction $transaction;

    // Form properties
    #[Rule('required|in:income,expense')]
    public string $type;

    #[Rule('required|date')]
    public string $transaction_date;

    #[Rule('required|numeric|min:0')]
    public string $amount;

    #[Rule('required|exists:finance_categories,id')]
    public ?int $finance_category_id;

    #[Rule('nullable|string|max:1000')]
    public string $description;

    public Collection $categories;

    public function mount(FinancialTransaction $transaction)
    {
        $this->transaction = $transaction;
        $this->type = $transaction->type;
        $this->transaction_date = $transaction->transaction_date->format('Y-m-d');
        $this->amount = $transaction->amount;
        $this->finance_category_id = $transaction->finance_category_id;
        $this->description = $transaction->description;

        $this->loadCategories();
    }

    public function loadCategories(): void
    {
        $this->categories = FinanceCategory::where('type', $this->type)->get();
    }

    public function updatedType(): void
    {
        $this->loadCategories();
        $this->finance_category_id = null; // Reset category when type changes
    }

    public function update(): void
    {
        $this->validate();
        $this->transaction->update($this->all());
        session()->flash('success', 'อัปเดตรายการเรียบร้อยแล้ว');
        $this->redirect(route('finance.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.finance.edit-page');
    }
}
