<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use App\Models\PurchaseOrder;
use App\Models\SupplierReturn;
use App\Observers\SupplierReturnObserver;
use App\Observers\PurchaseOrderObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // เมธอด register ควรจะว่างไว้สำหรับโปรเจกต์นี้
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // กำหนดให้ Pagination ใช้สไตล์ของ Tailwind CSS ทั่วทั้งแอปพลิเคชัน
        Paginator::useTailwind();
        // ลงทะเบียน Observer สำหรับ PurchaseOrder
        PurchaseOrder::observe(PurchaseOrderObserver::class);
        // SupplierReturn::observe(SupplierReturnObserver::class); // คอมเมนต์: ย้าย Logic การตัดสต็อกไปไว้ใน Component โดยตรงเพื่อแก้ปัญหา Timing
    }
}
