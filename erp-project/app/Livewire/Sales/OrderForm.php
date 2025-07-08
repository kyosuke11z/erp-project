<?php

namespace App\Livewire\Sales;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Rule;

class OrderForm extends Component
{
    // Properties สำหรับผูกกับฟอร์ม
    #[Rule('required|exists:customers,id', message: 'กรุณาเลือกลูกค้า')]
    public $customerId;

    #[Rule('required|date')]
    public $orderDate;

    #[Rule('required|in:pending,completed,cancelled')]
    public $status = 'pending'; // กำหนดค่าเริ่มต้นเป็น 'รอดำเนินการ'

    // Properties สำหรับการเพิ่มสินค้า
    public $selectedProductId;
    public $products = [];
    public $customers = [];
#[Rule([
        'orderItems' => 'required|array|min:1',
        'orderItems.*.quantity' => 'required|integer|min:1',
    ], message: [
        'orderItems.min' => 'กรุณาเพิ่มรายการสินค้าอย่างน้อย 1 รายการ',
        'orderItems.*.quantity.min' => 'จำนวนต้องมากกว่า 0',
    ])]
    public $orderItems = [];

    /**
     * เมธอด mount() จะทำงานเมื่อ Component ถูกสร้างขึ้น
     * ใช้สำหรับดึงข้อมูลเริ่มต้น
     */
    public function mount()
    {
        $this->customers = Customer::all();
        $this->products = Product::where('quantity', '>', 0)->get(); // ดึงเฉพาะสินค้าที่มีในสต็อก
        $this->orderDate = now()->format('Y-m-d');
    }

    /**
     * เพิ่มสินค้าที่เลือกลงในรายการสั่งซื้อ
     */
    public function addProduct()
    {
        $this->validate([
            'selectedProductId' => 'required|exists:products,id'
        ], [
            'selectedProductId.required' => 'กรุณาเลือกสินค้าก่อนกดเพิ่ม'
        ]);

        $product = Product::find($this->selectedProductId);

        // ตรวจสอบว่ามีสินค้านี้ในรายการแล้วหรือยัง
        $existingItemIndex = collect($this->orderItems)->search(function ($item) {
            return $item['product_id'] == $this->selectedProductId;
        });

        if ($existingItemIndex !== false) {
            // ถ้ามีแล้ว ให้เพิ่มจำนวน
            $this->orderItems[$existingItemIndex]['quantity']++;
        } else {
            // ถ้ายังไม่มี ให้เพิ่มเป็นรายการใหม่
            $this->orderItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
            ];
        }

        // ล้างค่าที่เลือกไว้
        $this->reset('selectedProductId');
    }

    /**
     * ลบสินค้าออกจากรายการสั่งซื้อ
     */
    public function removeProduct($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems); // จัดเรียง index ใหม่
    }

    /**
     * บันทึกคำสั่งขาย
     */
    public function save()
    {
        // Validate ข้อมูลทั้งหมดในฟอร์ม
        $this->validate();

        // คำนวณยอดรวม
        $totalAmount = collect($this->orderItems)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        // ใช้ Transaction เพื่อความปลอดภัยของข้อมูล
        DB::transaction(function () use ($totalAmount) {
            // สร้าง SalesOrder
            $salesOrder = SalesOrder::create([
                'customer_id' => $this->customerId,
                'order_date' => $this->orderDate,
                'status' => $this->status, // บันทึกสถานะ
                'total_amount' => $totalAmount,
            ]);

            // เตรียมข้อมูล items สำหรับบันทึก
            $itemsToSave = collect($this->orderItems)->map(function ($item) {
                return [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ];
            })->all();

            // สร้าง SalesOrderItem ทีเดียวทั้งหมด
            $salesOrder->items()->createMany($itemsToSave);
        });

        // กลับไปหน้ารายการ พร้อมข้อความแจ้งเตือน
        return redirect()->route('sales.index')->with('success', 'สร้างคำสั่งขายเรียบร้อยแล้ว');
    }

    public function render()
    {
        return view('livewire.sales.order-form')->layout('layouts.app');
    }
}
