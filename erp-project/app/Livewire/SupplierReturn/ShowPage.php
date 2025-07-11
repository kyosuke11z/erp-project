<?php

namespace App\Livewire\SupplierReturn;

use App\Models\SupplierReturn;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('รายละเอียดใบคืนสินค้า')]
class ShowPage extends Component
{
    public SupplierReturn $supplierReturn;

    public function mount(SupplierReturn $supplierReturn): void
    {
        $this->supplierReturn = $supplierReturn->load([
            'items.product',
            'goodsReceipt.purchaseOrder.supplier',
            'createdBy' // คอมเมนต์: ลบการโหลด relationship ที่ไม่จำเป็นและไม่มีอยู่จริงออกไป
        ]);
    }

    public function render()
    {
        return view('livewire.supplier-return.show-page');
    }
}