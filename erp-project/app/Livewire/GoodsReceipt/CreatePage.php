<?php

namespace App\Livewire\GoodsReceipt;

use App\Models\GoodsReceiptItem;
use App\Models\GoodsReceipt;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Goods Receipt')]
class CreatePage extends Component
{
    public PurchaseOrder $purchaseOrder;
    public array $receiptItems = [];
    public string $receipt_date;
    public ?string $notes = null;

    public function mount(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder->load('items.product');
        $this->receipt_date = now()->format('Y-m-d');

        // ปรับปรุงประสิทธิภาพ: ดึงข้อมูลจำนวนที่รับแล้วทั้งหมดใน PO นี้มาเตรียมไว้ในครั้งเดียว
        $receivedQuantities = GoodsReceiptItem::whereIn(
                'goods_receipt_id',
                $this->purchaseOrder->goodsReceipts()->pluck('id')
            )
            ->select('product_id', DB::raw('SUM(quantity_received) as total_received'))
            ->groupBy('product_id')
            ->pluck('total_received', 'product_id');

        foreach ($this->purchaseOrder->items as $item) {
            // ใช้ข้อมูลที่ดึงมาแล้ว ลดการ query ใน loop
            $quantityOutstanding = $item->quantity - $receivedQuantities->get($item->product_id, 0);

            $this->receiptItems[] = [
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'sku' => $item->product->sku,
                'quantity_ordered' => $item->quantity,
                'quantity_outstanding' => $quantityOutstanding,
                'quantity_received' => $quantityOutstanding > 0 ? $quantityOutstanding : 0,
            ];
        }
    }

    public function saveReceipt()
    {
        $this->validate([
            'receipt_date' => 'required|date',
            'receiptItems.*.quantity_received' => 'required|integer|min:0',
        ]);

        DB::transaction(function () {
            // 1. สร้าง Goods Receipt หลัก
            // เปลี่ยนจากการ create() เป็นการ new instance เพื่อให้เราสามารถบันทึกและรับ ID กลับมาก่อนได้
            $goodsReceipt = new GoodsReceipt([
                'purchase_order_id' => $this->purchaseOrder->id,
                'receipt_date' => $this->receipt_date,
                'notes' => $this->notes,
                'created_by' => Auth::id(),
            ]);
            $goodsReceipt->save(); // บันทึกครั้งแรกเพื่อรับ ID

            // สร้าง receipt_number จาก ID ที่ไม่ซ้ำกันแน่นอน แล้วบันทึกอีกครั้ง
            $goodsReceipt->receipt_number = 'GR-' . str_pad($goodsReceipt->id, 6, '0', STR_PAD_LEFT);
            $goodsReceipt->save();

            $totalOrdered = 0;
            $totalReceivedIncludingThis = 0;

            // 2. สร้างรายการรับของ และอัปเดตสต็อก
            foreach ($this->receiptItems as $index => $itemData) {
                if ($itemData['quantity_received'] > 0) {
                    $goodsReceipt->items()->create([
                        'product_id' => $itemData['product_id'],
                        'quantity_received' => $itemData['quantity_received'],
                    ]);
                    Product::find($itemData['product_id'])->increment('quantity', $itemData['quantity_received']);
                }
                $totalOrdered += $itemData['quantity_ordered'];
                $totalReceivedIncludingThis += ($this->purchaseOrder->items[$index]->quantity - $itemData['quantity_outstanding']) + $itemData['quantity_received'];
            }

            // 3. อัปเดตสถานะ PO
            if ($totalReceivedIncludingThis >= $totalOrdered) {
                $this->purchaseOrder->update(['status' => 'received']);
            } else {
                $this->purchaseOrder->update(['status' => 'partially_received']);
            }

            session()->flash('success', 'บันทึกการรับสินค้าและอัปเดตสต็อกเรียบร้อยแล้ว');
            $this->redirectRoute('purchase-orders.show', ['purchaseOrder' => $this->purchaseOrder->id]);
        });
    }

    public function render()
    {
        return view('livewire.goods-receipt.create-page');
    }
}