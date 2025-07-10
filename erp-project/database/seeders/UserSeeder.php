<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้าง Role 'Admin' ถ้ายังไม่มี
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // สร้าง User ที่เป็น Admin
        // ใช้ firstOrCreate เพื่อป้องกันการสร้างซ้ำหากรัน seeder หลายครั้ง
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // ตั้งรหัสผ่านเป็น 'password'
            ]
        );

        // กำหนด Role 'Admin' ให้กับ User
        if (!$adminUser->hasRole('Admin')) {
            $adminUser->assignRole($adminRole);
        }
    }
}