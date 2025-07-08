<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public bool $showModal = false;

    // --- Properties สำหรับผูกกับฟิลด์ในฟอร์ม ---
    public string $sku = '';
    public string $name = '';
    public string $description = '';
    public $selling_price = 0;
    public int $quantity = 0;
    public $category_id = '';
    public $newImage;
    public $existingImage = null;

    public ?Product $editing = null;

    public function rules()
    {
        return [
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($this->editing?->id),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'newImage' => 'nullable|image|max:2048',
        ];
    }

    #[On('open-product-form')]
    public function open($productId = null): void
    {
        $this->reset();
        $this->resetErrorBag();

        if ($productId) {
            $this->editing = Product::findOrFail($productId);
            $this->fill($this->editing->toArray());
            $this->existingImage = $this->editing->image;
        }

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset();
    }

    public function save(): void
    {
        $validatedData = $this->validate();

        $imageToStore = $validatedData['newImage'] ?? null;
        unset($validatedData['newImage']);

        if ($imageToStore) {
            if ($this->editing && $this->editing->image) {
                Storage::disk('public')->delete($this->editing->image);
            }
            $validatedData['image'] = $imageToStore->store('products', 'public');
        }

        if ($this->editing) {
            $this->editing->update($validatedData);
            session()->flash('success', 'อัปเดตสินค้าเรียบร้อยแล้ว');
        } else {
            Product::create($validatedData);
            session()->flash('success', 'เพิ่มสินค้าเรียบร้อยแล้ว');
        }

        // ส่ง Event บอก Component แม่ว่ามีการบันทึกข้อมูลแล้ว ให้ทำการ refresh
        $this->dispatch('product-saved');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.products.product-form', [
            'categories' => Category::all(),
        ]);
    }
}