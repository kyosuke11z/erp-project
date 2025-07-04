<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // สร้างคอลัมน์ id (Primary Key, Auto-Increment)
            $table->string('sku')->unique(); // สร้างคอลัมน์ sku (รหัสสินค้า) และกำหนดให้เป็น unique (ห้ามซ้ำ)
            $table->string('name'); // สร้างคอลัมน์ name (ชื่อสินค้า)
            $table->text('description')->nullable(); // สร้างคอลัมน์ description (รายละเอียด) และอนุญาตให้เป็นค่าว่าง (nullable)
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete(); // สร้างคอลัมน์ category_id และสร้าง Foreign Key เชื่อมไปยังตาราง categories (ถ้าลบ category สินค้าในกลุ่มนี้จะถูกลบตาม)
            $table->decimal('purchase_price', 10, 2)->default(0); // สร้างคอลัมน์ราคาซื้อ (ทศนิยม 2 ตำแหน่ง)
            $table->decimal('selling_price', 10, 2)->default(0); // สร้างคอลัมน์ราคาขาย (ทศนิยม 2 ตำแหน่ง)
            $table->integer('quantity')->default(0); // สร้างคอลัมน์จำนวนสินค้าคงคลัง (ตัวเลขจำนวนเต็ม)
            $table->timestamps(); // สร้างคอลัมน์ created_at และ updated_at อัตโนมัติ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
