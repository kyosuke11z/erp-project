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
        // สร้างตารางใหม่ชื่อ 'categories'
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // สร้างคอลัมน์ id (Primary Key, Auto-Increment)
            $table->string('name'); // สร้างคอลัมน์ name สำหรับเก็บชื่อหมวดหมู่ (ชนิดข้อมูล VARCHAR)
            $table->text('description')->nullable(); // สร้างคอลัมน์ description สำหรับเก็บคำอธิบายหมวดหมู่ (ชนิดข้อมูล TEXT) และอนุญาตให้เป็น NULL
            $table->timestamps(); // สร้างคอลัมน์ created_at และ updated_at อัตโนมัติ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
