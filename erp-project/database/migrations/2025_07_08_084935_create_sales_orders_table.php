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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id(); // ID ของออเดอร์
            $table->foreignId('customer_id') // ID ของลูกค้าที่สั่งซื้อ
                  ->constrained('customers')
                  ->onDelete('restrict'); // ป้องกันการลบลูกค้าที่มีออเดอร์ค้างอยู่
            $table->date('order_date'); // วันที่สั่งซื้อ
            $table->string('status')->default('pending')->index(); // สถานะ: pending, processing, completed, cancelled
            $table->decimal('total_amount', 10, 2); // ยอดรวมสุทธิของออเดอร์
            $table->text('notes')->nullable(); // หมายเหตุเพิ่มเติม (ถ้ามี)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
