<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = Category::pluck('id');

        // ป้องกัน Error ในกรณีที่ไม่มีหมวดหมู่ในระบบ
        if ($categoryIds->isEmpty()) {
            $this->command->warn('No categories found, skipping product seeding.');
            return;
        }
 
        // สร้างสินค้า 50 ชิ้น โดยส่งค่า category_id เข้าไปในขั้นตอน create เลย
        // Laravel จะทำการสุ่ม category_id ให้ใหม่สำหรับสินค้าแต่ละชิ้น
        Product::factory()->count(50)->create([
            'category_id' => fn() => $categoryIds->random(),
        ]);
    }
}