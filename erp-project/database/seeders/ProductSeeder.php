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
 
        // สร้างสินค้า 50 ชิ้น โดยใช้ closure เพื่อกำหนดค่าราคาที่สมจริง
       Product::factory()
            ->count(50)
            ->create(function (array $attributes) use ($categoryIds) {
                $purchasePrice = fake()->randomFloat(2, 50, 1000); // สุ่มราคาซื้อระหว่าง 50 - 1000
                $sellingPrice = $purchasePrice * fake()->randomFloat(2, 1.2, 1.8); // บวกกำไร 20% - 80%

                return [
                    'category_id' => $categoryIds->random(),
                    'purchase_price' => $purchasePrice,
                    'selling_price' => round($sellingPrice, 2),
                ];
            });
    }
}