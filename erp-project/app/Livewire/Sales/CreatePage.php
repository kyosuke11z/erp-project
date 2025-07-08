<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Collection;

class CreatePage extends Component
{
    // Form state
    public $customer_id = '';
    public $order_date;
    public $notes = '';
    public array $orderItems = [];

    public float $total_amount = 0;

    // Data for dropdowns
    public Collection $allCustomers;
    public Collection $allProducts;

    public function mount()
    {
        $this->allCustomers = Customer::orderBy('name')->get();
        $this->allProducts = Product::orderBy('name')->get();

        // Initialize form with default values
        $this->order_date = now()->format('Y-m-d');
        $this->addItem(); // Start with one empty item row for better user experience
    }

    protected function rules()
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'order_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'orderItems' => ['required', 'array', 'min:1'],
            'orderItems.*.product_id' => ['required', 'exists:products,id'],
            'orderItems.*.quantity' => [
                'required',
                'integer',
                'min:1',
                // Custom Rule เพื่อตรวจสอบสต็อกสินค้า
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $productId = $this->orderItems[$index]['product_id'] ?? null;

                    if ($productId) {
                        $product = Product::find($productId);
                        if ($product && $value > $product->quantity) {
                            $fail("สินค้า '{$product->name}' มีในคลังไม่พอ (มีอยู่: {$product->quantity})");
                        }
                    }
                },
            ],
        ];
    }

    protected function messages()
    {
        return [
            'orderItems.required' => 'ต้องมีรายการสินค้าอย่างน้อย 1 รายการ',
            'orderItems.min' => 'ต้องมีรายการสินค้าอย่างน้อย 1 รายการ',
            'orderItems.*.product_id.required' => 'กรุณาเลือกสินค้า',
            'orderItems.*.product_id.exists' => 'สินค้าที่เลือกไม่ถูกต้อง',
            'orderItems.*.quantity.required' => 'กรุณาระบุจำนวน',
            'orderItems.*.quantity.min' => 'จำนวนต้องเป็นอย่างน้อย 1',
        ];
    }

    public function updatedOrderItems($value, $key)
    {
        $path = explode('.', $key); // e.g., '0.product_id'
        $index = $path[0];
        $field = $path[1];

        if ($field === 'product_id' && !empty($value)) {
            $product = $this->allProducts->find($value);
            $this->orderItems[$index]['price'] = $product?->price ?? 0;
            $this->orderItems[$index]['stock'] = $product?->quantity ?? 0; // ดึงข้อมูลสต็อก
        }

        $this->calculateTotal();
    }

    public function addItem()
    {
        $this->orderItems[] = ['product_id' => '', 'quantity' => 1, 'price' => 0, 'stock' => null];
    }

    public function removeItem($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems); // Re-index array
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_amount = collect($this->orderItems)->sum(function ($item) {
            $quantity = is_numeric($item['quantity']) ? $item['quantity'] : 0;
            $price = is_numeric($item['price']) ? $item['price'] : 0;
            return $quantity * $price;
        });
    }

    public function save()
    {
        $this->validate();

        $salesOrder = DB::transaction(function () {
            // 1. สร้าง SalesOrder หลัก
            $order = SalesOrder::create([
                'customer_id' => $this->customer_id,
                'order_date' => $this->order_date,
                'status' => 'pending', // Default status
                'total_amount' => $this->total_amount,
                'notes' => $this->notes,
            ]);

            // 2. สร้างรายการสินค้า
            foreach ($this->orderItems as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                // 3. ตัดสต็อกสินค้า
                // ใช้ find() เพื่อความปลอดภัย แม้จะผ่าน validation มาแล้ว
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('quantity', $item['quantity']);
                }
            }
            return $order;
        });

        session()->flash('success', 'สร้างใบสั่งขาย #' . $salesOrder->id . ' และตัดสต็อกสินค้าเรียบร้อยแล้ว');
        return $this->redirect(route('sales.show', $salesOrder), navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.create-page')->layout('layouts.app');
    }
}
