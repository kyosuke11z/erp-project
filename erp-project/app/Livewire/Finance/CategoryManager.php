<?php

namespace App\Livewire\Finance;

use App\Models\FinanceCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('layouts.app')]
class CategoryManager extends Component
{
    use WithPagination;

    #[Rule('required|string|max:255|unique:finance_categories,name')]
    public $name = '';

    #[Rule('required|in:income,expense')]
    public $type = 'expense';

    #[Rule('nullable|string')]
    public $description = '';

    public function saveCategory()
    {
        $this->validate();

        FinanceCategory::create([
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
        ]);

        session()->flash('success', 'สร้างหมวดหมู่การเงินสำเร็จ');
        $this->reset();
    }

    public function render()
    {
        $categories = FinanceCategory::latest()->paginate(10);
        return view('livewire.finance.category-manager', [
            'categories' => $categories,
        ]);
    }
}