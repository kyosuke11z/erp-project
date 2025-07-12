<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('สร้างสินค้าใหม่')]
class CreatePage extends Component
{
    // Properties สำหรับผูกกับฟอร์ม
    public string $name = '';
    public string $sku = '';
    public ?string $description = '';
    public int $quantity = 0;
    public float $selling_price = 0.00;
    public float $purchase_price = 0.00; // <-- เพิ่ม Property สำหรับราคาทุน
    public ?int $category_id = null;
    public ?int $min_stock_level = 10;

    public Collection $categories;

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0', // <-- เพิ่มกฎสำหรับตรวจสอบราคาทุน
            'category_id' => 'required|exists:categories,id',
            'min_stock_level' => 'required|integer|min:0',
        ];
    }

    public function save()
    {
        // ตรวจสอบข้อมูลและบันทึก โดยจะรวม purchase_price ไปด้วย
        $validated = $this->validate();

        Product::create($validated);

        session()->flash('success', 'เพิ่มสินค้าเรียบร้อยแล้ว');
        return $this->redirectRoute('products.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.products.create-page');
    }
}