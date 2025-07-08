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
        Schema::table('products', function (Blueprint $table) {
            // เพิ่มคอลัมน์ image ชนิด string, อนุญาตให้เป็นค่าว่าง (nullable)
            // และวางไว้หลังคอลัมน์ description
            $table->string('image')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // คำสั่งสำหรับตอนที่ต้องการย้อนกลับ (rollback) migration
            $table->dropColumn('image');
        });
    }
};
