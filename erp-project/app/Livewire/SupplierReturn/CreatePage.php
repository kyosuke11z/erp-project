<?php

namespace App\Livewire\SupplierReturn;

use Livewire\Component;
use App\Models\Product;
use App\Models\GoodsReceipt;
use App\Models\SupplierReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Create Supplier Return')]
class CreatePage extends Component
{
    public GoodsReceipt $goodsReceipt;

    public array $returnItems = [];
    public string $reason = '';
    public string $return_date;

    /**
     * คอมเมนต์: เมธอด mount ทำงานเมื่อ component ถูกโหลด
     * เตรียมข้อมูลเริ่มต้นสำหรับฟอร์ม
     */
    public function mount(GoodsReceipt $goodsReceipt): void
    {
        // คอมเมนต์: Eager load ความสัมพันธ์ทั้งหมดที่จำเป็น รวมถึงประวัติการคืนสินค้า
        $this->goodsReceipt = $goodsReceipt->load(['items.product', 'supplierReturns.items']);
        $this->return_date = now()->format('Y-m-d');

        // คอมเมนต์: สร้าง lookup map สำหรับจำนวนที่เคยคืนไปแล้ว เพื่อประสิทธิภาพสูงสุด
        $previouslyReturnedQuantities = $this->goodsReceipt->supplierReturns
            ->flatMap(fn ($return) => $return->items) // ดึงรายการคืนทั้งหมดจากทุกใบคืน
            ->groupBy('product_id') // จัดกลุ่มตามรหัสสินค้า
            ->map(fn ($items) => $items->sum('quantity')); // คำนวณผลรวมที่คืนไปแล้วของแต่ละสินค้า

        // คอมเมนต์: เตรียมข้อมูลสำหรับฟอร์ม โดยดึงจากรายการสินค้าในใบรับของ
        foreach ($this->goodsReceipt->items as $item) {
            if ($item->product) {
                $alreadyReturned = $previouslyReturnedQuantities->get($item->product_id, 0);
                $returnableQuantity = $item->quantity_received - $alreadyReturned;

                // คอมเมนต์: เพิ่มรายการเฉพาะสินค้าที่ยังสามารถคืนได้ (มีจำนวนเหลือให้คืน > 0)
                if ($returnableQuantity > 0) {
                    $this->returnItems[$item->id] = [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'sku' => $item->product->sku,
                        'quantity_received' => $item->quantity_received,       // จำนวนที่รับมาเดิม (สำหรับแสดงผล)
                        'quantity_already_returned' => $alreadyReturned,      // จำนวนที่เคยคืนไปแล้ว (สำหรับแสดงผล)
                        'quantity_returnable' => $returnableQuantity,         // จำนวนที่สามารถคืนได้สูงสุดในครั้งนี้
                        'quantity_to_return' => 0,                            // ค่าเริ่มต้นของจำนวนที่คืนคือ 0
                    ];
                }
            }
        }
    }

    /**
     * คอมเมนต์: เมธอดสำหรับบันทึกข้อมูลการคืนสินค้า
     */
    public function saveReturn(): void
    {
        $this->validate([
            'return_date' => 'required|date',
            'reason' => 'nullable|string|max:1000',
            'returnItems.*.quantity_to_return' => 'required|integer|min:0',
        ]);

        // คอมเมนต์: กรองเฉพาะรายการสินค้าที่มีการระบุจำนวนคืนมากกว่า 0
        $itemsToProcess = collect($this->returnItems)->filter(fn($item) => $item['quantity_to_return'] > 0);

        if ($itemsToProcess->isEmpty()) {
            $this->addError('general', 'กรุณาระบุจำนวนสินค้าที่ต้องการคืนอย่างน้อย 1 รายการ');
            return;
        }

        $hasErrors = false;
        // คอมเมนต์: ตรวจสอบว่าจำนวนที่คืนไม่เกินจำนวนที่ "สามารถคืนได้จริง"
        foreach ($itemsToProcess as $key => $item) {
            $returnableItem = $this->returnItems[$key]; // ดึงข้อมูล state ล่าสุดของ item นั้น
            if ($item['quantity_to_return'] > $returnableItem['quantity_returnable']) {
                $this->addError(
                    "returnItems.{$key}.quantity_to_return",
                    "คืนได้ไม่เกิน {$returnableItem['quantity_returnable']} ชิ้น (รับมา {$returnableItem['quantity_received']}, คืนแล้ว {$returnableItem['quantity_already_returned']})"
                );
                $hasErrors = true;
            }
        }

        // คอมเมนต์: ตรวจสอบเพิ่มเติมว่าจำนวนที่คืนไม่เกินสต็อกคงเหลือในปัจจุบัน
        foreach ($itemsToProcess as $key => $item) {
            $product = Product::find($item['product_id']);
            if ($product && $item['quantity_to_return'] > $product->quantity) {
                // เพิ่มข้อความแจ้งเตือนที่ชัดเจน
                $this->addError("returnItems.{$key}.quantity_to_return", "ไม่สามารถคืน '{$product->name}' เกินสต็อกที่มี (คงเหลือ: {$product->quantity})");
                $hasErrors = true;
            }
        }

        if ($hasErrors) {
            return;
        }

        DB::transaction(function () use ($itemsToProcess) {
            // คอมเมนต์: 1. สร้างใบคืนสินค้าหลัก (SupplierReturn)
            // โดยใส่เลขที่เอกสารเป็นค่าชั่วคราวไปก่อน เพื่อให้ได้ ID กลับมา
            $supplierReturn = SupplierReturn::create([
                'goods_receipt_id' => $this->goodsReceipt->id,
                'created_by' => auth()->id(),
                'return_number' => 'TEMP-' . uniqid(), // ใช้ค่าชั่วคราวที่ไม่ซ้ำกัน
                'return_date' => $this->return_date,
                'reason' => $this->reason,
                'status' => 'confirmed', // เพิ่มการกำหนดสถานะให้ชัดเจน
            ]);

            // คอมเมนต์: 2. สร้างเลขที่เอกสารที่ถูกต้องจาก ID และบันทึกอีกครั้ง
            $supplierReturn->return_number = 'SR-' . str_pad($supplierReturn->id, 6, '0', STR_PAD_LEFT);
            $supplierReturn->save();

            // คอมเมนต์: 3. วนลูปสร้างรายการสินค้าที่คืนและตัดสต็อกไปพร้อมกัน
            foreach ($itemsToProcess as $itemData) {
                // 2a. สร้างรายการสินค้าในใบคืน (SupplierReturnItem)
                $supplierReturn->items()->create([
                    'product_id' => $itemData['product_id'], 
                    'quantity' => $itemData['quantity_to_return']
                ]);
                
                // 2b. ค้นหาสินค้าและทำการลดสต็อก (decrement)
                $product = Product::lockForUpdate()->find($itemData['product_id']);
                if ($product) {
                    $product->decrement('quantity', $itemData['quantity_to_return']);
                }
            }
        });

        session()->flash('success', 'บันทึกการคืนสินค้าเรียบร้อยแล้ว และสต็อกได้ถูกปรับลดแล้ว');
        $this->redirectRoute('goods-receipt.show', ['goodsReceipt' => $this->goodsReceipt->id]);
    }

    public function render()
    {
        return view('livewire.supplier-return.create-page');
    }
}