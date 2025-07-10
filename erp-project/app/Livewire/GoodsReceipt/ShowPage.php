<?php

namespace App\Livewire\GoodsReceipt;

use App\Models\GoodsReceipt;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Goods Receipt Details')]
class ShowPage extends Component
{
    public GoodsReceipt $goodsReceipt;

    public function mount(GoodsReceipt $goodsReceipt)
    {
        // Eager load ข้อมูลที่เกี่ยวข้องทั้งหมดเพื่อประสิทธิภาพสูงสุด
        $this->goodsReceipt = $goodsReceipt->load(['purchaseOrder.supplier', 'items.product', 'createdBy']);
    }

    public function render()
    {
        return view('livewire.goods-receipt.show-page');
    }
}