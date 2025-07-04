<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Trash extends Component
{
    use WithPagination;

    public function restore(int $id): void
    {
        $product = Product::onlyTrashed()->find($id);
        if ($product) {
            $product->restore();
            session()->flash('success', 'กู้คืนสินค้าเรียบร้อยแล้ว');
        }
    }

    public function forceDelete(int $id): void
    {
        $product = Product::onlyTrashed()->find($id);
        if ($product) {
            $product->forceDelete();
            session()->flash('success', 'ลบสินค้าถาวรเรียบร้อยแล้ว');
        }
    }

    public function render()
    {
        $products = Product::onlyTrashed()->with('category')->latest('deleted_at')->paginate(10);

        return view('livewire.products.trash', [
            'products' => $products,
        ])->layout('layouts.app');
    }
}
