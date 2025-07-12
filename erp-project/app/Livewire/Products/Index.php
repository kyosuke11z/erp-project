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
