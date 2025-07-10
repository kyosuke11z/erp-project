<?php

namespace App\Observers;

use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderObserver
{
    /**
     * Handle the PurchaseOrder "updating" event.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return void
     */
    public function updating(PurchaseOrder $purchaseOrder): void
    {
        // ตรวจสอบว่ามีการเปลี่ยนแปลงสถานะ และสถานะใหม่คือ 'received' หรือไม่
        if ($purchaseOrder->isDirty('status') && $purchaseOrder->status === 'received') {
            
            // ใช้ Transaction เพื่อความปลอดภัยของข้อมูล
            // หากมีข้อผิดพลาดระหว่างทาง การเปลี่ยนแปลงทั้งหมดจะถูกยกเลิก
            DB::transaction(function () use ($purchaseOrder) {
                // วนลูปรายการสินค้าทั้งหมดในใบสั่งซื้อนี้
                foreach ($purchaseOrder->items as $item) {
                    // อัปเดตสต็อกสินค้า
                    // การใช้ increment จะปลอดภัยกว่าการดึงค่ามาบวกแล้วเซฟเอง (prevents race conditions)
                    $item->product()->increment('quantity', $item->quantity);
                }
            });
        }
    }
}

