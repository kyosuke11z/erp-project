<?php

namespace Database\Seeders;

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
            // UserSeeder สร้างทั้ง Role และ Admin User แล้ว จึงไม่จำเป็นต้องเรียก AdminUserSeeder ซ้ำ
            UserSeeder::class,
            // หาก RolesAndPermissionsSeeder สร้าง Permission อื่นๆ ที่จำเป็น ก็ยังคงไว้
            RolesAndPermissionsSeeder::class,
            CategorySeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            SupplierSeeder::class,
            PurchaseOrderSeeder::class,
        ]);
    }
}
