<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Purchase Order Details')]
class Show extends Component
{
    public PurchaseOrder $purchaseOrder;

    public function mount(PurchaseOrder $purchaseOrder)
    {
        // Eager load relationships เพื่อประสิทธิภาพที่ดีขึ้น
        // เพิ่มการโหลด goodsReceipts.createdBy เพื่อแสดงประวัติการรับของ
        $this->purchaseOrder = $purchaseOrder->load(['supplier', 'items.product', 'goodsReceipts.createdBy']);
    }

    public function render()
    {
        return view('livewire.purchase-orders.show');
    }
}