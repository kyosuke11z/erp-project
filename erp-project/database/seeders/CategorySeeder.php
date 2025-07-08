<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'เครื่องเขียน', 'description' => 'อุปกรณ์สำหรับงานเขียนและสำนักงาน'],
            ['name' => 'อุปกรณ์อิเล็กทรอนิกส์', 'description' => 'แกดเจ็ตและอุปกรณ์เสริมต่างๆ'],
            ['name' => 'ของใช้ในบ้าน', 'description' => 'สินค้าสำหรับใช้ในชีวิตประจำวัน'],
            ['name' => 'หนังสือ', 'description' => 'หนังสือและสื่อสิ่งพิมพ์'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

