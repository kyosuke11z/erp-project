<?php

namespace App\Livewire\Sales;

use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.app')]
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
        $query = SalesOrder::with('customer')
            // บังคับให้เลือกคอลัมน์ที่จำเป็นทั้งหมดมาอย่างชัดเจน
            // เพื่อป้องกันปัญหาจาก Global Scope หรือ Trait ที่อาจซ่อนคอลัมน์บางตัว
            ->select([
                'sales_orders.id',
                'sales_orders.order_number',
                'sales_orders.customer_id',
                'sales_orders.order_date',
                'sales_orders.total_amount',
                'sales_orders.status',
            ])
            ->when($this->search, function ($query) {
                // ปรับปรุงการค้นหาให้รองรับ order_number และชื่อลูกค้า
                $query->where(function ($subQuery) {
                    $subQuery->where('order_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest('sales_orders.created_at'); // ระบุชื่อตารางเพื่อความชัดเจน

        $salesOrders = $query->paginate(10);
        return view('livewire.sales.index-page', compact('salesOrders'));
    }
}
