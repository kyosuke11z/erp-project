<?php

namespace App\Livewire\Sales;

use App\Models\SalesOrder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class OrderIndex extends Component
{
    use WithPagination;

 public string $search = '';

    /**
     * Reset pagination when search term is updated.
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete(SalesOrder $salesOrder)
    {
        // ป้องกันการลบออเดอร์ที่ไม่ได้อยู่ในสถานะ 'pending'
        if ($salesOrder->status !== 'pending') {
            session()->flash('error', 'ไม่สามารถลบใบสั่งขายที่ไม่ได้อยู่ในสถานะรอดำเนินการได้');
            return;
        }

        // ใช้ Transaction เพื่อให้แน่ใจว่าการลบข้อมูลสมบูรณ์
        // หากลบ items ไม่สำเร็จ การลบ order ก็จะไม่เกิดขึ้น
        DB::transaction(function () use ($salesOrder) {
            // 1. คืนสต็อกสินค้ากลับเข้าคลัง
            foreach ($salesOrder->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('quantity', $item->quantity);
                }
            }

            // 2. ลบรายการสินค้าที่เกี่ยวข้องก่อน
            $salesOrder->items()->delete(); // ลบรายการสินค้าที่เกี่ยวข้องก่อน
            // 3. จากนั้นจึงลบใบสั่งขายหลัก
            $salesOrder->delete();
        });

        session()->flash('success', 'ลบใบสั่งขาย #' . $salesOrder->id . ' และคืนสต็อกสินค้าเรียบร้อยแล้ว');

        // Livewire จะทำการรีเฟรชหน้าให้อัตโนมัติ
    }

    public function render()
    {
        $salesOrders = SalesOrder::with('customer')->latest()->paginate(10);
        $salesOrders = SalesOrder::with('customer')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                     $subQuery->where('id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10);


        return view('livewire.sales.order-index', compact('salesOrders'))->layout('layouts.app');
    }
}
