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
        Schema::table('sales_orders', function (Blueprint $table) {
            // เพิ่มคอลัมน์ paid_at เพื่อเก็บวันที่และเวลาที่ชำระเงิน
            $table->timestamp('paid_at')->nullable()->after('status'); // คอมเมนต์: เพิ่มคอลัมน์นี้เท่านั้น เนื่องจาก status มีอยู่แล้ว
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            // คอมเมนต์: ลบคอลัมน์ที่เพิ่มเข้าไปใน migration นี้เท่านั้น
            $table->dropColumn('paid_at');
        });
    }
};
