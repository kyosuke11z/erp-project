<?php

namespace App\Livewire\Sales;

use App\Models\SalesOrder;
use Livewire\Component;

class ShowPage extends Component
{
    public SalesOrder $salesOrder;

    public function mount(SalesOrder $salesOrder)
    {
        // โหลดข้อมูลที่จำเป็นมาล่วงหน้า (Eager Loading) เพื่อประสิทธิภาพที่ดีขึ้น
        $this->salesOrder = $salesOrder->load('customer', 'items.product');
    }

    /**
     * ยกเลิกคำสั่งขาย
     */
    public function cancelOrder()
    {
        // ตรวจสอบอีกครั้งเพื่อความปลอดภัยว่าสถานะเป็น 'pending'
        if ($this->salesOrder->status !== 'pending') {
            session()->flash('error', 'ไม่สามารถยกเลิกออเดอร์ที่ไม่ได้อยู่ในสถานะรอดำเนินการได้');
            return;
        }

        // เปลี่ยนสถานะเป็น 'cancelled' และบันทึก
        $this->salesOrder->status = 'cancelled';
        $this->salesOrder->save();

        // ส่งข้อความแจ้งเตือน
        session()->flash('success', 'ยกเลิกคำสั่งขาย #' . $this->salesOrder->id . ' เรียบร้อยแล้ว');
    }

    public function render()
    {
        return view('livewire.sales.show-page')->layout('layouts.app');
    }
}
