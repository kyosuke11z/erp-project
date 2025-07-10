<?php

namespace App\Livewire\PurchaseOrders;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.app')]
#[Title('Purchase Orders')]
class Index extends Component
{
    use WithPagination;

    // บอกให้ Livewire ใช้ Pagination View ของ Tailwind
    protected $paginationTheme = 'tailwind';

    #[Url(as: 'status', keep: true)]
    public string $statusFilter = ''; // Property สำหรับรับค่าจาก Filter

    #[Url(as: 's', keep: true)]
    public string $search = ''; // ตัวแปรสำหรับเก็บคำค้นหา

    /**
     * Reset page when searching or filtering to avoid pagination issues.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    /**
     * ลบข้อมูลใบสั่งซื้อ (Soft Delete)
     */
    public function delete(PurchaseOrder $purchaseOrder)
    {
        // ตรวจสอบสิทธิ์โดยใช้ Policy: ผู้ใช้คนนี้สามารถ 'delete' ใบสั่งซื้อใบนี้ได้หรือไม่
        // $this->authorize('delete', $purchaseOrder); // Uncomment if you have a policy

        $purchaseOrder->delete();
        session()->flash('success', 'ลบใบสั่งซื้อ #' . $purchaseOrder->po_number . ' เรียบร้อยแล้ว');
    }

    /**
     * Render component และดึงข้อมูลมาแสดง.
     */
    public function render()
    {
        $query = PurchaseOrder::with('supplier')
            ->when($this->search, fn($q) => $q->where(fn($sub) => $sub->where('po_number', 'like', "%{$this->search}%")->orWhereHas('supplier', fn($sq) => $sq->where('name', 'like', "%{$this->search}%"))))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter));

        $purchaseOrders = $query->latest()->paginate(10);

        return view('livewire.purchase-orders.index', [
            'purchaseOrders' => $purchaseOrders,
        ]);
    }
}