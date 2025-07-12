<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // คอมเมนต์: สร้างตารางสำหรับเก็บหมวดหมู่ของรายการทางการเงิน
        Schema::create('finance_categories', function (Blueprint $table) {
            $table->id(); // รหัสอ้างอิงหลัก
            $table->string('name'); // ชื่อหมวดหมู่
            $table->enum('type', ['income', 'expense']); // ประเภทของหมวดหมู่ (รายรับ, รายจ่าย)
            $table->timestamps(); // วันที่สร้างและแก้ไข
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finance_categories');
    }
};