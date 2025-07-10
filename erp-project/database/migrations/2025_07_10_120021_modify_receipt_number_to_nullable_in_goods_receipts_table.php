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
        Schema::table('goods_receipts', function (Blueprint $table) {
            // ทำให้คอลัมน์เป็น nullable โดยไม่ต้องพยายามเพิ่ม unique index ซ้ำซ้อน
            $table->string('receipt_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods_receipts', function (Blueprint $table) {
            // ย้อนกลับไปเป็น not nullable
            $table->string('receipt_number')->nullable(false)->change();
        });
    }
};