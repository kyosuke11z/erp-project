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
            // เพิ่มคอลัมน์ order_number หลังคอลัมน์ id
            // nullable() -> สำหรับข้อมูลเก่าที่ยังไม่มีค่านี้
            // unique() -> ป้องกันการสร้างเลขที่เอกสารซ้ำกันในระดับฐานข้อมูล
            $table->string('order_number')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            // คำสั่งสำหรับตอนที่ต้องการ rollback migration
            $table->dropColumn('order_number');
        });
    }
};
