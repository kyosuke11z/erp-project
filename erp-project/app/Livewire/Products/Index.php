<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    // ใช้งาน Trait สำหรับการแบ่งหน้าและการอัปโหลดไฟล์
    use WithPagination;

    // --- Properties สำหรับจัดการสถานะของ UI ---
    public bool $showDeleteModal = false;

    #[Url(as: 's', keep: true)]
    public string $search = '';

    // --- Properties สำหรับจัดการสถานะของข้อมูล (กำลังแก้ไข, กำลังลบ) ---
    public ?Product $deleting = null;
    
    /**
     * ดักฟัง event 'product-saved' จาก component ลูก (ProductForm)
     * การมีเมธอดนี้อยู่ (แม้จะว่างเปล่า) จะทำให้ Livewire ทำการ re-render component นี้ใหม่
     * ซึ่งจะดึงข้อมูลสินค้าล่าสุดมาแสดงผลโดยอัตโนมัติ
     */
    #[On('product-saved')]
    public function refreshProductList(): void
    {
        // ไม่ต้องทำอะไรในนี้ แค่มีไว้เพื่อรับ event
    }
    /**
     * ส่ง Event เพื่อเปิดฟอร์มสำหรับสร้างสินค้าใหม่
     */
    public function create(): void
    {
        $this->dispatch('open-product-form');
    }

    /**
     * เปิด Modal เพื่อยืนยันการลบสินค้า
     */
    public function confirmDelete(Product $product): void
    {
        $this->deleting = $product;
        $this->showDeleteModal = true;
    }

    /**
     * ทำการลบสินค้า (Soft Delete)
     */
    public function delete(): void
    {
        if ($this->deleting) {
            $this->deleting->delete();
            session()->flash('success', 'ลบสินค้าเรียบร้อยแล้ว');
        }
        $this->showDeleteModal = false;
    }

    /**
     * ส่ง Event เพื่อเปิดฟอร์มสำหรับแก้ไขสินค้า
     */
    public function edit(Product $product): void
    {
        $this->dispatch('open-product-form', productId: $product->id);
    }


    /**
     * Render component เพื่อแสดงผล
     */
    public function render()
    {
        // ดึงข้อมูลสินค้าพร้อมกับข้อมูลหมวดหมู่ที่เกี่ยวข้อง
        $products = Product::with('category')
            ->where(fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('sku', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        // ส่งข้อมูลไปยัง view
        return view('livewire.products.index', ['products' => $products])->layout('layouts.app');
    }
}
