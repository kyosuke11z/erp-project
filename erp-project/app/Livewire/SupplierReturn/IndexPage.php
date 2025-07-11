<?php

namespace App\Livewire\SupplierReturn;

use App\Models\SupplierReturn;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('รายการใบคืนสินค้า')]
class IndexPage extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $returns = SupplierReturn::with(['goodsReceipt', 'createdBy'])
            ->when($this->search, function ($query) {
                $query->where('return_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('goodsReceipt', function ($subQuery) {
                        $subQuery->where('receipt_number', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest('return_date')
            ->paginate(15);

        return view('livewire.supplier-return.index-page', [
            'returns' => $returns,
        ]);
    }
}