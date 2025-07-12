<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('แก้ไขสินค้า')]
class EditPage extends Component
{
    public Product $product;

    // Properties for form binding
    public string $name = '';
    public string $sku = '';
    public ?string $description = '';
    public int $quantity = 0;
    public float $selling_price = 0.00;
    public float $purchase_price = 0.00;
    public ?int $category_id = null;
    public ?int $min_stock_level = 10;

    public Collection $categories;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->categories = Category::orderBy('name')->get();

        // Populate form fields with existing product data
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->description = $product->description;
        $this->quantity = $product->quantity;
        $this->selling_price = (float) $product->selling_price;
        $this->purchase_price = (float) $product->purchase_price;
        $this->category_id = $product->category_id;
        $this->min_stock_level = $product->min_stock_level;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            // Use Rule::unique to ignore the current product's SKU
            'sku' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($this->product->id)],
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'min_stock_level' => 'required|integer|min:0',
        ];
    }

    public function save()
    {
        $validated = $this->validate();
        $this->product->update($validated);
        session()->flash('success', 'อัปเดตสินค้าเรียบร้อยแล้ว');
        return $this->redirectRoute('products.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.products.edit-page');
    }
}