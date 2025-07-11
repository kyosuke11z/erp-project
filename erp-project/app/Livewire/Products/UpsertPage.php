<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
class UpsertPage extends Component
{
    public Product $product;

    public string $sku = '';
    public string $name = '';
    public ?string $description = null;
    public float $selling_price = 0;
    public int $quantity = 0;
    public ?int $category_id = null;
    // คอมเมนต์: เพิ่ม property สำหรับ min_stock_level
    public int $min_stock_level = 5;

    public bool $isEditing = false;

    public function mount($productId = null)
    {
        // คอมเมนต์: เปลี่ยนมาใช้การค้นหาข้อมูลด้วยตนเองเพื่อแก้ปัญหา Route Conflict
        if ($productId) {
            // Edit Mode: ค้นหาสินค้าจาก ID ที่ได้รับมา
            $this->product = Product::findOrFail($productId);
            $this->isEditing = true;
            // คอมเมนต์: แก้ไข bug โดยเปลี่ยนจาก $product เป็น $this->product เพื่อให้ fill() ทำงานกับข้อมูลที่ถูกต้อง
            $this->fill($this->product->toArray());
        } else {
            // Create Mode: สร้าง Product object ใหม่
            $this->product = new Product();
        }
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string|max:255|unique:products,sku,' . ($this->isEditing ? $this->product->id : ''),
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            // คอมเมนต์: เพิ่ม validation rule
            'min_stock_level' => 'required|integer|min:0',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $this->product->update($validated);
            session()->flash('success', 'อัปเดตข้อมูลสินค้าสำเร็จ');
        } else {
            Product::create($validated);
            session()->flash('success', 'สร้างสินค้าใหม่สำเร็จ');
        }

        return $this->redirect('/products', navigate: true);
    }

    #[Title('จัดการสินค้า')]
    public function render()
    {
        $categories = Category::all();
        return view('livewire.products.upsert-page', [
            'categories' => $categories,
        ]);
    }
}