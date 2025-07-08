<?php

namespace Database\Seeders;

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
         User::factory(10)->create();
         $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class, // เพิ่มการเรียกใช้ CategorySeeder ในไฟล์นี้
            CustomerSeeder::class, // เพิ่มการเรียกใช้ CustomerSeeder ในไฟล์นี้
            ProductSeeder::class
        ]);
        
    }
}
