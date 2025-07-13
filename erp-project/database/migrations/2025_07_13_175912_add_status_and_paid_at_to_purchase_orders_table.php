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
        Schema::table('purchase_orders', function (Blueprint $table) {
            // คอมเมนต์: เพิ่มเฉพาะคอลัมน์ paid_at เนื่องจาก status มีอยู่แล้ว
            $table->timestamp('paid_at')->nullable()->after('status'); // เพิ่มคอลัมน์นี้ต่อจาก status ที่มีอยู่
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('paid_at');
        });
    }
};