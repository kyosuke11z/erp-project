<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CreatePage extends Component
{
    // Properties สำหรับฟอร์ม
    public $supplier_id = '';
    public $order_date;
    public $expected_delivery_date;
    public $notes = '';

    // Properties สำหรับรายการสินค้า
    public $orderItems = [];
    public $allProducts = [];
    public $allSuppliers = [];

    // ยอดรวม
    public $total_amount = 0;

    // Validation Rules
    protected function rules()
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string|max:1000',
            'orderItems' => 'required|array|min:1',
            'orderItems.*.product_id' => 'required|exists:products,id',
            'orderItems.*.quantity' => 'required|integer|min:1',
            'orderItems.*.price' => 'required|numeric|min:0',
            'orderItems.*.total' => 'required|numeric|min:0',
        ];
    }

    // ข้อความสำหรับ Validation
    protected $messages = [
        'supplier_id.required' => 'กรุณาเลือกซัพพลายเออร์',
        'order_date.required' => 'กรุณาระบุวันที่สั่งซื้อ',
        'orderItems.min' => 'กรุณาเพิ่มรายการสินค้าอย่างน้อย 1 รายการ',
        'orderItems.*.product_id.required' => 'กรุณาเลือกสินค้า',
        'orderItems.*.quantity.required' => 'กรุณาระบุจำนวน',
        'orderItems.*.quantity.min' => 'จำนวนต้องมีค่าอย่างน้อย 1',
        'orderItems.*.price.required' => 'กรุณาระบุราคา',
    ];

    // เมธอด Mount จะทำงานเมื่อ Component ถูกสร้างขึ้น
    public function mount()
    {
        $this->order_date = now()->format('Y-m-d');
        $this->allSuppliers = Supplier::orderBy('name')->get();
        $this->allProducts = Product::orderBy('name')->get();
        $this->addItem(); // เริ่มต้นด้วยรายการสินค้าว่าง 1 แถว
    }

    // เพิ่มรายการสินค้าใหม่
    public function addItem()
    {
        $this->orderItems[] = [
            'product_id' => '',
            'quantity' => 1,
            'price' => 0,
            'total' => 0,
        ];
    }

    // ลบรายการสินค้า
    public function removeItem($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems); // จัดเรียง index ใหม่
        $this->updateTotals();
    }

    // ทำงานเมื่อมีการเปลี่ยนแปลงใน orderItems
    public function updatedOrderItems($value, $key)
    {
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        // ถ้ามีการเลือกสินค้า ให้ดึงราคาตั้งต้นมาใส่
        if ($field === 'product_id' && $this->orderItems[$index]['product_id']) {
            // ปรับปรุง: ค้นหาสินค้าจาก Collection ที่โหลดมาแล้วเพื่อลดการ Query ฐานข้อมูล
            $product = $this->allProducts->firstWhere('id', $this->orderItems[$index]['product_id']);
            if ($product) {
                // แก้ไขให้ดึงราคาจาก purchase_price และใส่ค่า default เป็น 0 หากไม่มี
                $this->orderItems[$index]['price'] = (float) ($product->purchase_price ?? 0);
            }
        }

        // คำนวณยอดรวมของแถวนั้นๆ
        $quantity = (int)($this->orderItems[$index]['quantity'] ?? 1);
        $price = (float)($this->orderItems[$index]['price'] ?? 0);
        $this->orderItems[$index]['total'] = $quantity * $price;

        $this->updateTotals();
    }

    // คำนวณยอดรวมทั้งหมด
    public function updateTotals()
    {
        $this->total_amount = collect($this->orderItems)->sum('total');
    }

    // บันทึกใบสั่งซื้อ
    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            // 1. สร้าง PO โดยยังไม่ใส่ po_number
            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $this->supplier_id,
                'order_date' => $this->order_date,
                'expected_delivery_date' => $this->expected_delivery_date,
                'notes' => $this->notes,
                'total_amount' => $this->total_amount,
                'status' => 'pending',
                'po_number' => 'TEMP', // ใส่ค่าชั่วคราวเพื่อไม่ให้ validation error
            ]);

            // 2. สร้าง po_number จาก ID ที่ไม่ซ้ำกันแน่นอน แล้วบันทึก
            $purchaseOrder->po_number = 'PO-' . str_pad($purchaseOrder->id, 5, '0', STR_PAD_LEFT);
            $purchaseOrder->save();

            $purchaseOrder->items()->createMany($this->orderItems);
        });

        session()->flash('success', 'สร้างใบสั่งซื้อสำเร็จแล้ว');
        return $this->redirectRoute('purchase-orders.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.purchase-orders.create-page');
    }
}