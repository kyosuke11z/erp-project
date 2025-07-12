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
        // คอมเมนต์: สร้างตารางสำหรับเก็บรายการเคลื่อนไหวทางการเงิน (รายรับ-รายจ่าย)
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id(); // รหัสอ้างอิงหลัก
            $table->enum('type', ['income', 'expense']); // ประเภทรายการ (รายรับ, รายจ่าย)
            $table->date('transaction_date'); // วันที่เกิดรายการ
            $table->decimal('amount', 15, 2); // จำนวนเงิน
            $table->text('description')->nullable(); // คำอธิบายเพิ่มเติม
            $table->foreignId('finance_category_id')->constrained('finance_categories')->onDelete('cascade'); // เชื่อมกับตารางหมวดหมู่
            $table->nullableMorphs('related_model'); // คอมเมนต์: Polymorphic Relationship สำหรับเชื่อมกับโมเดลอื่น เช่น SalesOrder, PurchaseOrder
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ผู้ที่บันทึกรายการ
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
        Schema::dropIfExists('financial_transactions');
    }
};