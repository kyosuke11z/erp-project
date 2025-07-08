<?php

namespace App\Livewire\Sales;

use App\Models\SalesOrder;
use Livewire\Component;
use Livewire\Attributes\Rule;

class OrderShow extends Component
{
    public SalesOrder $salesOrder;

    #[Rule(['required', 'string', 'min:3'])]
    public string $newComment = '';

    public function mount(SalesOrder $salesOrder)
    {
        $this->salesOrder = $salesOrder;
        // Eager load all necessary relationships for efficiency
        $this->salesOrder->load('customer', 'items.product', 'comments.user');
    }

    public function addComment()
    {
        $this->validate();

        $this->salesOrder->comments()->create([
            'body' => $this->newComment,
            'user_id' => auth()->id(),
        ]);

        $this->reset('newComment');
        $this->salesOrder->load('comments.user'); // Refresh the comments list
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

        session()->flash('success', 'ยกเลิกคำสั่งขาย #' . $this->salesOrder->order_number . ' เรียบร้อยแล้ว');
    }

    public function render()
    {
        return view('livewire.sales.order-show')->layout('layouts.app');
    }
}
