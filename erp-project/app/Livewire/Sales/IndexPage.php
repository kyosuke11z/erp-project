<?php

namespace App\Livewire\Sales;

use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPage extends Component
{
    use WithPagination;

    public string $search = '';

    public function cancelOrder(int $orderId)
    {
        try {
            DB::transaction(function () use ($orderId) {
                $order = SalesOrder::with('items.product')->findOrFail($orderId);

                if (in_array($order->status, ['completed', 'cancelled'])) {
                    session()->flash('error', 'ไม่สามารถยกเลิกออเดอร์ที่เสร็จสิ้นหรือถูกยกเลิกไปแล้วได้');
                    return;
                }

                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity', $item->quantity);
                    }
                }

                $order->update(['status' => 'cancelled']);

                session()->flash('success', "ยกเลิกออเดอร์ #{$orderId} และคืนสต็อกสินค้าเรียบร้อยแล้ว");
            });
        } catch (\Exception $e) {
            session()->flash('error', 'เกิดข้อผิดพลาดในการยกเลิกออเดอร์: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $salesOrders = SalesOrder::with('customer')
            ->when($this->search, function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.sales.index-page', ['salesOrders' => $salesOrders])->layout('layouts.app');
    }
}

