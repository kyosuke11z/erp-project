<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // คอมเมนต์: แก้ไขคอลัมน์ status ให้รองรับค่า 'paid' เพิ่มเติม
        // เรารวบรวมสถานะทั้งหมดจากไฟล์ show.blade.php มาไว้ที่นี่
        DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending', 'completed', 'partially_received', 'received', 'cancelled', 'paid') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // คอมเมนต์: ทำให้สามารถย้อนกลับได้ โดยการนำ 'paid' ออก
        DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('pending', 'completed', 'partially_received', 'received', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};