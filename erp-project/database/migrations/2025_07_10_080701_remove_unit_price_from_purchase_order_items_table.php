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
        // ตรวจสอบก่อนว่าคอลัมน์มีอยู่จริงหรือไม่ เพื่อความปลอดภัย
        if (Schema::hasColumn('purchase_order_items', 'unit_price')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                $table->dropColumn('unit_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->nullable()->after('quantity');
        });
    }
};

