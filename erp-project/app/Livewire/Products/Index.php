<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Properties for Modals and Search
    public bool $showModal = false;
    public bool $showDeleteModal = false;

    #[Url(as: 's', keep: true)]
    public string $search = '';

    // Properties for form fields
    public string $sku = '';
    public string $name = '';
    public string $description = '';
    public $selling_price = 0;
    public int $quantity = 0;
    public $category_id = '';

    // Properties for handling state
    public ?Product $editing = null;
    public ?Product $deleting = null;

    // Dynamic validation rules
    public function rules()
    {
        return [
            'sku' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('products')->ignore($this->editing?->id),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function openModal(): void
    {
        $this->reset();
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editing) {
            $this->editing->update($validated);
            session()->flash('success', 'อัปเดตสินค้าเรียบร้อยแล้ว');
        } else {
            Product::create($validated);
            session()->flash('success', 'เพิ่มสินค้าเรียบร้อยแล้ว');
        }

        $this->closeModal();
    }

    public function edit(Product $product): void
    {
        $this->editing = $product;
        $this->fill($product); // ใช้ fill เพื่อเติมข้อมูลทั้งหมดจาก model
        $this->resetErrorBag();
        $this->showModal = true;
    }

    public function confirmDelete(Product $product): void
    {
        $this->deleting = $product;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deleting) {
            $this->deleting->delete();
            session()->flash('success', 'ลบสินค้าเรียบร้อยแล้ว');
        }
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $products = Product::with('category')
            ->where(fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('sku', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.products.index', [
            'products' => $products,
            'categories' => Category::all(),
        ])->layout('layouts.app');
    }
}