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
            // เพิ่มสถานะ 'partially_received' เพื่อรองรับการรับของไม่ครบ
            $table->enum('status', ['pending', 'completed', 'cancelled', 'partially_received', 'received'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            // ย้อนกลับไปสถานะก่อนที่จะเพิ่ม 'partially_received'
            $table->enum('status', ['pending', 'completed', 'cancelled', 'received'])->default('pending')->change();
        });
    }
};