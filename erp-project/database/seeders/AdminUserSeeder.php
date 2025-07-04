<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; 
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission; 

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้าง Role 'Admin' ถ้ายังไม่มี
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // (ทางเลือก) สร้าง Permission แล้วกำหนดให้ Role
        // Permission::firstOrCreate(['name' => 'manage everything']);
        // $adminRole->givePermissionTo('manage everything');

        // สร้าง User สำหรับ Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@erp.com'], // ค้นหาด้วย email
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // ตั้งรหัสผ่านเริ่มต้น (ควรเปลี่ยนหลัง login ครั้งแรก)
            ]
        );

        // กำหนด Role 'Admin' ให้กับ User
        $adminUser->assignRole($adminRole);
    }
}