<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Livewire\Livewire;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\PurchaseOrder;
use App\Models\SupplierReturn;
use App\Observers\SupplierReturnObserver;
use App\Observers\PurchaseOrderObserver;
use App\Observers\SalesOrderObserver;
use App\Observers\SalesOrderItemObserver;


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
        // คอมเมนต์: ลงทะเบียน Model Observers
        // Observer จะทำงานอัตโนมัติเมื่อมี event เกิดขึ้นกับ Model (เช่น created, updated)
        SalesOrder::observe(SalesOrderObserver::class);
        SalesOrderItem::observe(SalesOrderItemObserver::class);
        PurchaseOrder::observe(PurchaseOrderObserver::class);
        // คอมเมนต์: เพิ่ม Macro 'thaidate' ให้กับ Carbon เพื่อแปลงวันที่เป็นรูปแบบไทยพร้อมปี พ.ศ.
        // ทำให้เราสามารถเรียกใช้ ->thaidate() ได้ทั่วทั้งโปรเจกต์
        Carbon::macro('thaidate', function ($format) {
            /** @var Carbon $this */
            $date = $this->locale('th_TH'); // กำหนด Locale เป็นภาษาไทย
            $thaiYear = $date->year + 543; // แปลง ค.ศ. เป็น พ.ศ.

            // แทนที่ 'Y' ใน format string ด้วยปี พ.ศ.
            $formatWithThaiYear = str_replace('Y', $thaiYear, $format);

            // ใช้ translatedFormat เพื่อให้ชื่อเดือนและอื่นๆ เป็นภาษาไทย
            return $date->translatedFormat($formatWithThaiYear);
        });
    }
}
