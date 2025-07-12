<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // คอมเมนต์: รีเซ็ต cache ของ roles และ permissions ก่อนการสร้างใหม่
        // เพื่อป้องกันปัญหาเมื่อรัน seeder ซ้ำ
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // คอมเมนต์: สร้าง Permissions สำหรับแต่ละโมดูล
        // การตั้งชื่อ permission ให้เป็นระบบ (เช่น module-action) จะช่วยให้จัดการง่ายในอนาคต
        $permissions = [
            // User Management
            'user-list', 'user-create', 'user-edit', 'user-delete',
            // Role & Permission Management
            'role-list', 'role-create', 'role-edit', 'role-delete', 'role-assign',
            // Product
            'product-list', 'product-create', 'product-edit', 'product-delete',
            // Category
            'category-list', 'category-create', 'category-edit', 'category-delete',
            // Customer
            'customer-list', 'customer-create', 'customer-edit', 'customer-delete',
            // Supplier
            'supplier-list', 'supplier-create', 'supplier-edit', 'supplier-delete',
            // Sales Order
            'sales-order-list', 'sales-order-create', 'sales-order-edit', 'sales-order-delete', 'sales-order-approve',
            // Purchase Order
            'purchase-order-list', 'purchase-order-create', 'purchase-order-edit', 'purchase-order-delete', 'purchase-order-approve',
            // Goods Receipt
            'goods-receipt-list', 'goods-receipt-create',
            // Supplier Return
            'supplier-return-list', 'supplier-return-create',
        ];

        foreach ($permissions as $permission) {
            // คอมเมนต์: ใช้ updateOrCreate เพื่อให้ seeder สามารถรันซ้ำได้โดยไม่เกิดข้อผิดพลาด
            Permission::updateOrCreate(['name' => $permission], ['guard_name' => 'web']);
        }

        // คอมเมนต์: สร้าง Role 'Admin'
        $adminRole = Role::updateOrCreate(['name' => 'Admin']);

        // คอมเมนต์: สร้าง Role 'Manager' และผูก Permissions ที่จำเป็น
        $managerRole = Role::updateOrCreate(['name' => 'Manager']);
        $managerPermissions = [
            'product-list', 'product-create', 'product-edit', 'product-delete',
            'category-list', 'category-create', 'category-edit', 'category-delete',
            'customer-list', 'customer-create', 'customer-edit', 'customer-delete',
            'supplier-list', 'supplier-create', 'supplier-edit', 'supplier-delete',
            'sales-order-list', 'sales-order-create', 'sales-order-edit', 'sales-order-delete', 'sales-order-approve',
            'purchase-order-list', 'purchase-order-create', 'purchase-order-edit', 'purchase-order-delete', 'purchase-order-approve',
            'goods-receipt-list', 'goods-receipt-create',
            'supplier-return-list', 'supplier-return-create',
        ];
        $managerRole->givePermissionTo($managerPermissions);

        // คอมเมนต์: สร้าง Role 'Staff' และผูก Permissions พื้นฐาน
        $staffRole = Role::updateOrCreate(['name' => 'Staff']);
        $staffPermissions = [
            'product-list',
            'customer-list',
            'supplier-list',
            'sales-order-list', 'sales-order-create',
            'purchase-order-list', 'purchase-order-create',
            'goods-receipt-list', 'goods-receipt-create',
            'supplier-return-list', 'supplier-return-create',
        ];
        $staffRole->givePermissionTo($staffPermissions);

        // คอมเมนต์: สร้างผู้ใช้ Admin เริ่มต้นของระบบ
        // ตรวจสอบก่อนว่ามีผู้ใช้ที่มี email นี้แล้วหรือยัง เพื่อป้องกัน error เวลารัน seed ซ้ำ
        if (!User::where('email', 'admin@example.com')->exists()) {
            $adminUser = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                // คอมเมนต์: กำหนดรหัสผ่านเริ่มต้นให้ชัดเจน เพื่อป้องกันปัญหาจาก UserFactory
                'password' => bcrypt('password'), // รหัสผ่านคือ 'password'
            ]);
            $adminUser->assignRole($adminRole);
        }
    }
}