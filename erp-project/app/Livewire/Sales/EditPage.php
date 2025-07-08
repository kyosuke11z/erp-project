<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Collection;

class EditPage extends Component
{
    public SalesOrder $salesOrder;

    // Form state
    #[Rule(['required', 'exists:customers,id'])]
    public $customer_id;

    #[Rule(['required', 'date'])]
    public $order_date;

    #[Rule(['nullable', 'string'])]
    public $notes;

    #[Rule([
        'orderItems' => ['required', 'array', 'min:1'],
        'orderItems.*.product_id' => ['required', 'exists:products,id'],
        'orderItems.*.quantity' => ['required', 'integer', 'min:1'],
    ], message: [
        'orderItems.required' => 'ต้องมีรายการสินค้าอย่างน้อย 1 รายการ',
        'orderItems.min' => 'ต้องมีรายการสินค้าอย่างน้อย 1 รายการ',
        'orderItems.*.product_id.required' => 'กรุณาเลือกสินค้า',
        'orderItems.*.product_id.exists' => 'สินค้าที่เลือกไม่ถูกต้อง',
        'orderItems.*.quantity.required' => 'กรุณาระบุจำนวน',
        'orderItems.*.quantity.min' => 'จำนวนต้องเป็นอย่างน้อย 1',
    ])]
    public array $orderItems = [];

    public float $total_amount = 0;

    // Data for dropdowns
    public Collection $allCustomers;
    public Collection $allProducts;

    public function mount(SalesOrder $salesOrder)
    {
        // ป้องกันไม่ให้แก้ไขออเดอร์ที่ไม่ได้อยู่ในสถานะ pending
        if ($salesOrder->status !== 'pending') {
            session()->flash('error', 'ไม่สามารถแก้ไขใบสั่งขายที่ไม่ได้อยู่ในสถานะรอดำเนินการได้');
            $this->redirect(route('sales.show', $salesOrder), navigate: true);
        }

        $salesOrder->load('items.product'); // Eager load products for items
        $this->salesOrder = $salesOrder;
        $this->allCustomers = Customer::orderBy('name')->get();
        $this->allProducts = Product::orderBy('name')->get();

        // Initialize form state from the existing order
        $this->customer_id = $salesOrder->customer_id;
        $this->order_date = $salesOrder->order_date->format('Y-m-d');
        $this->notes = $salesOrder->notes;

        foreach ($salesOrder->items as $item) {
            $this->orderItems[] = [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'stock' => $item->product?->quantity ?? 0, // ดึงข้อมูลสต็อกตอนโหลด
            ];
        }

        $this->calculateTotal();
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

        DB::transaction(function () {
            // 1. อัปเดตข้อมูลหลักของ SalesOrder
            $this->salesOrder->update([
                'customer_id' => $this->customer_id,
                'order_date' => $this->order_date,
                'notes' => $this->notes,
                'total_amount' => $this->total_amount,
            ]);

            // 2. Sync รายการสินค้า (ลบของเก่าทิ้ง แล้วสร้างใหม่ทั้งหมด)
            $this->salesOrder->items()->delete();

            foreach ($this->orderItems as $item) {
                $this->salesOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }
        });

        session()->flash('success', 'บันทึกการเปลี่ยนแปลงใบสั่งขาย #' . $this->salesOrder->id . ' เรียบร้อยแล้ว');
        return $this->redirect(route('sales.show', $this->salesOrder), navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.edit-page')->layout('layouts.app');
    }
}