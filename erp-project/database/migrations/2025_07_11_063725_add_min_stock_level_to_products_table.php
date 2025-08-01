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
            // เพิ่มคอลัมน์สำหรับกำหนดจุดสั่งซื้อขั้นต่ำ โดยวางไว้หลังคอลัมน์ quantity
            // ซึ่งเป็นคอลัมน์สต็อกที่แท้จริงของระบบ
            $table->integer('min_stock_level')->default(5)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('min_stock_level');
        });
    }
};
