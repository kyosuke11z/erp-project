<?php

namespace Database\Seeders;

use App\Models\FinanceCategory;
use App\Models\SalesOrder;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // User::factory(10)->create(); // ปิดการใช้งานส่วนนี้ไป เพราะเราจะสร้าง User ที่ต้องการผ่าน Seeder โดยตรง
         $this->call([
            RolesAndPermissionsSeeder::class,
            CategorySeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            SupplierSeeder::class,
            PurchaseOrderSeeder::class,
            FinanceCategorySeeder::class,
        ]);
    }
}
