<?php

namespace Database\Seeders;

use App\Models\FinanceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinanceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // คอมเมนต์: สร้างข้อมูลหมวดหมู่เริ่มต้น
        $categories = [
            // หมวดหมู่รายรับ
            ['name' => 'รายได้จากการขาย', 'type' => 'income'],
            ['name' => 'รายได้อื่นๆ', 'type' => 'income'],

            // หมวดหมู่รายจ่าย
            ['name' => 'ค่าวัตถุดิบ/สินค้า', 'type' => 'expense'],
            ['name' => 'เงินเดือน', 'type' => 'expense'],
            ['name' => 'ค่าเช่า', 'type' => 'expense'],
            ['name' => 'ค่าการตลาด', 'type' => 'expense'],
            ['name' => 'ค่าสาธารณูปโภค', 'type' => 'expense'],
            ['name' => 'ค่าใช้จ่ายอื่นๆ', 'type' => 'expense'],
        ];

        foreach ($categories as $category) {
            // คอมเมนต์: ใช้ updateOrCreate เพื่อป้องกันข้อมูลซ้ำซ้อนหากรัน seeder หลายครั้ง
            FinanceCategory::updateOrCreate(
                ['name' => $category['name'], 'type' => $category['type']],
                $category
            );
        }
    }
}