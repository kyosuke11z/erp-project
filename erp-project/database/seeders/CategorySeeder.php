<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้างข้อมูลหมวดหมู่ตัวอย่าง
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Books & Audible'],
            ['name' => 'Clothing, Shoes & Jewelry'],
            ['name' => 'Home & Kitchen'],
            ['name' => 'Sports & Outdoors'],
            ['name' => 'Toys & Games'],
            ['name' => 'Health & Personal Care'],
        ];

        // วนลูปเพื่อสร้างข้อมูลแต่ละรายการโดยใช้ Eloquent's create method
        // เพื่อให้ timestamps (created_at, updated_at) ถูกสร้างโดยอัตโนมัติ
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

