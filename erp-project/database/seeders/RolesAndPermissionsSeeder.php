<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. สร้าง Permissions (สิทธิ์ในการกระทำต่างๆ) โดยใช้ firstOrCreate เพื่อป้องกันการสร้างซ้ำ
        Permission::firstOrCreate(['name' => 'view purchase orders']);
        Permission::firstOrCreate(['name' => 'create purchase orders']);
        Permission::firstOrCreate(['name' => 'edit purchase orders']);
        Permission::firstOrCreate(['name' => 'delete purchase orders']);
        Permission::firstOrCreate(['name' => 'view reports']);
        Permission::firstOrCreate(['name' => 'view-user-management']);

        // 2. สร้าง Roles โดยใช้ firstOrCreate
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $purchasingRole = Role::firstOrCreate(['name' => 'Purchasing']); // แผนกจัดซื้อ
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer']);       // ผู้ดูอย่างเดียว

        // 3. มอบสิทธิ์ให้กับ Role
        // Admin มีสิทธิ์ทั้งหมดผ่าน Gate::before ใน AuthServiceProvider
        $purchasingRole->givePermissionTo([
            'view purchase orders',
            'create purchase orders',
            'edit purchase orders',
        ]);

        $viewerRole->givePermissionTo('view purchase orders');

        // 4. กำหนด Role ให้กับ User คนแรก (สมมติว่าเป็น Admin)
        if ($adminUser = User::first()) {
            $adminUser->assignRole('Admin');
        }
    }
}
