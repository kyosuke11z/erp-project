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
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id(); // ID ของรายการ
            $table->foreignId('sales_order_id') // ID ของออเดอร์ที่เป็นเจ้าของรายการนี้
                  ->constrained('sales_orders')
                  ->onDelete('cascade'); // ถ้าออเดอร์ถูกลบ ให้ลบรายการสินค้าในออเดอร์นั้นด้วย
            $table->foreignId('product_id') // ID ของสินค้า
                  ->constrained('products')
                  ->onDelete('restrict'); // ป้องกันการลบสินค้าที่เคยมีการขายไปแล้ว
            $table->integer('quantity'); // จำนวนที่สั่ง
            $table->decimal('price', 10, 2); // <<-- หัวใจสำคัญ: ราคา ณ วันที่ขาย (Snapshot Price)
            $table->decimal('subtotal', 10, 2); // ยอดรวมของรายการนี้ (quantity * price)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_items');
    }
};
