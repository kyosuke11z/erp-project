<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for products with low stock and notify admins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for low stock products...');

    // คอมเมนต์: แก้ไขให้ตรวจสอบจากคอลัมน์ 'quantity' ที่ถูกต้อง
        $lowStockProducts = Product::whereRaw('quantity <= min_stock_level')->get();


        if ($lowStockProducts->isEmpty()) {
            $this->info('No low stock products found.');
            return;
        }

        // ค้นหาผู้ใช้ทั้งหมดที่มี Role 'Admin' เพื่อส่งการแจ้งเตือน
        $admins = User::role('Admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('No admin users found to notify.');
            return;
        }
         // เพิ่มการแสดงผลเพื่อการดีบัก: แสดงรายชื่อแอดมินที่จะได้รับการแจ้งเตือน
        $adminNames = $admins->pluck('name')->implode(', ');
        $this->info("Found {$admins->count()} admin(s) to notify: {$adminNames}");

        foreach ($lowStockProducts as $product) {
            // ส่ง Notification ไปให้ Admin ทุกคน
            Notification::send($admins, new LowStockAlert($product));
            $this->line("Notified admins about low stock for: {$product->name}");
        }

        $this->info('Low stock check complete.');
    }
}
