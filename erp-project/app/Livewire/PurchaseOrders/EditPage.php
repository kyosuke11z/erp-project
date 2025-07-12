<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('แก้ไขใบสั่งซื้อ')]
class EditPage extends Component
{
    public PurchaseOrder $purchaseOrder;

    // Properties สำหรับฟอร์ม
    public $supplier_id = '';
    public $order_date;
    public $expected_delivery_date;
    public $notes = '';
    public $status = '';

    // Properties สำหรับรายการสินค้า
    public array $orderItems = [];
    public Collection $allProducts;
    public Collection $allSuppliers;

    // ยอดรวม
    public $total_amount = 0;

    // Validation Rules
    protected function rules()
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
            'orderItems' => 'required|array|min:1',
            'orderItems.*.product_id' => 'required|exists:products,id',
            'orderItems.*.quantity' => 'required|integer|min:1',
            'orderItems.*.price' => 'required|numeric|min:0',
        ];
    }

    // ข้อความสำหรับ Validation
    protected $messages = [
        'supplier_id.required' => 'กรุณาเลือกซัพพลายเออร์',
        'order_date.required' => 'กรุณาระบุวันที่สั่งซื้อ',
        'status.required' => 'กรุณาเลือกสถานะ',
        'orderItems.min' => 'กรุณาเพิ่มรายการสินค้าอย่างน้อย 1 รายการ',
    ];

    // เมธอด Mount จะทำงานเมื่อ Component ถูกสร้างขึ้น
    public function mount(PurchaseOrder $purchaseOrder)
    {
        // คอมเมนต์: ตรวจสอบสิทธิ์การแก้ไขโดยใช้ Policy
        $this->authorize('update', $purchaseOrder);

        // คอมเมนต์: ป้องกันการแก้ไขใบสั่งซื้อที่ไม่ได้อยู่ในสถานะ 'pending'
        if ($purchaseOrder->status !== 'pending') {
            session()->flash('error', 'ไม่สามารถแก้ไขใบสั่งซื้อที่ไม่ได้อยู่ในสถานะรอดำเนินการได้');
            return $this->redirectRoute('purchase-orders.show', ['purchaseOrder' => $purchaseOrder->id], navigate: true);
        }

        $this->purchaseOrder = $purchaseOrder->load('items');
        $this->allSuppliers = Supplier::orderBy('name')->get();
        $this->allProducts = Product::orderBy('name')->get();

        // ดึงข้อมูลเดิมมาใส่ในฟอร์ม
        $this->supplier_id = $this->purchaseOrder->supplier_id;
        $this->order_date = $this->purchaseOrder->order_date->format('Y-m-d');
        $this->expected_delivery_date = $this->purchaseOrder->expected_delivery_date?->format('Y-m-d');
        $this->notes = $this->purchaseOrder->notes;
        $this->status = $this->purchaseOrder->status;
        $this->total_amount = $this->purchaseOrder->total_amount;

        foreach ($this->purchaseOrder->items as $item) {
            $this->orderItems[] = [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->quantity * $item->price,
            ];
        }
    }

    // เพิ่มรายการสินค้าใหม่
    public function addItem()
    {
        $this->orderItems[] = ['product_id' => '', 'quantity' => 1, 'price' => 0, 'total' => 0];
    }

    // ลบรายการสินค้า
    public function removeItem($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
        $this->updateTotals();
    }

    // ทำงานเมื่อมีการเปลี่ยนแปลงใน orderItems
    public function updatedOrderItems($value, $key)
    {
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        if ($field === 'product_id' && !empty($this->orderItems[$index]['product_id'])) {
            // คอมเมนต์: ปรับปรุงให้ค้นหาจาก Collection ที่โหลดมาแล้วเพื่อประสิทธิภาพที่ดีกว่า
            $product = $this->allProducts->firstWhere('id', $this->orderItems[$index]['product_id']);
            if ($product) {
                // คอมเมนต์: แก้ไขให้ดึงราคาทุน (purchase_price) สำหรับใบสั่งซื้อ
                $this->orderItems[$index]['price'] = $product->purchase_price ?? 0;
            }
        }

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

    // บันทึกการเปลี่ยนแปลง
    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $this->purchaseOrder->update([
                'supplier_id' => $this->supplier_id,
                'order_date' => $this->order_date,
                'expected_delivery_date' => $this->expected_delivery_date,
                'notes' => $this->notes,
                'status' => $this->status,
                'total_amount' => $this->total_amount,
            ]);

            // ลบรายการเดิมทั้งหมดแล้วสร้างใหม่
            $this->purchaseOrder->items()->delete();
            $this->purchaseOrder->items()->createMany($this->orderItems);
        });

        session()->flash('success', 'อัปเดตใบสั่งซื้อสำเร็จแล้ว');
        return $this->redirectRoute('purchase-orders.show', ['purchaseOrder' => $this->purchaseOrder->id], navigate: true);
    }

    public function render()
    {
        return view('livewire.purchase-orders.edit-page');
    }
}