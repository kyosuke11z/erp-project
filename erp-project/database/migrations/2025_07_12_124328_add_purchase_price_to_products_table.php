<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // คอมเมนต์: เพิ่มการตรวจสอบก่อนว่าคอลัมน์นี้ยังไม่มีอยู่จริง ๆ
        if (!Schema::hasColumn('products', 'purchase_price')) {
            Schema::table('products', function (Blueprint $table) {
                // เพิ่มคอลัมน์สำหรับราคาทุน (Purchase Price) หลังคอลัมน์ราคาขาย
                $table->decimal('purchase_price', 10, 2)->default(0.00)->after('selling_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // คอมเมนต์: เพิ่มการตรวจสอบก่อนว่าคอลัมน์นี้มีอยู่จริง ๆ ถึงจะลบ
        if (Schema::hasColumn('products', 'purchase_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('purchase_price');
            });
        }
    }
};