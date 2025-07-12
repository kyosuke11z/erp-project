<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // คอมเมนต์: ล้างข้อมูลเก่าในตารางที่เกี่ยวข้องก่อน เพื่อให้สามารถรัน seeder ซ้ำได้
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PurchaseOrder::truncate();
        PurchaseOrderItem::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ใช้ pluck เพื่อประสิทธิภาพที่ดีกว่าในการดึงข้อมูลจำนวนมาก
        $supplierIds = Supplier::pluck('id');
        $products = Product::all();

        if ($supplierIds->isEmpty() || $products->count() < 5) {
            $this->command->info('ไม่สามารถสร้าง Purchase Orders ได้ เนื่องจากไม่มีข้อมูล Suppliers หรือ Products');
            return;
        }

        // เพิ่มสถานะ 'received' เข้าไปในรายการสถานะที่จะสุ่ม
        $statuses = ['pending', 'completed', 'cancelled', 'received'];

        // ใช้ Transaction เพื่อให้แน่ใจว่าข้อมูลจะถูกสร้างอย่างสมบูรณ์หรือไม่ก็ไม่ถูกสร้างเลย
        DB::transaction(function () use ($supplierIds, $products, $statuses) {
            for ($i = 0; $i < 50; $i++) {
                // 1. สร้าง PO หลักพร้อมสถานะแบบสุ่ม และกำหนด po_number ที่ไม่ซ้ำกัน
                $po = PurchaseOrder::create([
                    'po_number' => 'PO-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                    'supplier_id' => $supplierIds->random(),
                    'order_date' => now()->subDays(rand(1, 365)),
                    'status' => $statuses[array_rand($statuses)],
                    'notes' => 'หมายเหตุทดสอบสำหรับ PO-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                    'total_amount' => 0, // จะถูกอัปเดตทีหลัง
                ]);

                $totalAmount = 0;
                $selectedProducts = $products->random(rand(1, 5));
                $itemsToCreate = [];

                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 10);
                    $price = $product->purchase_price;
                    $total = $quantity * $price;
                    $totalAmount += $total;

                    // 2. เตรียมข้อมูลรายการสินค้าไว้สำหรับ createMany
                    $itemsToCreate[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $total,
                    ];
                }

                // 3. สร้างรายการสินค้าทั้งหมดในครั้งเดียวเพื่อประสิทธิภาพที่ดีกว่า
                $po->items()->createMany($itemsToCreate);

                // 4. อัปเดตยอดรวมใน PO
                $po->update(['total_amount' => $totalAmount]);

                // 5. [สำคัญ] อัปเดตสต็อกหาก PO ถูกสร้างด้วยสถานะ 'received'
                // เพื่อให้ข้อมูลเริ่มต้นถูกต้องตาม Logic ของ Observer
                if ($po->status === 'received') {
                    foreach ($po->items as $item) {
                        // ใช้ increment เพื่อความปลอดภัยในการอัปเดตข้อมูล
                        $item->product()->increment('quantity', $item->quantity);
                    }
                }
            }
        });

        $this->command->info('สร้างข้อมูล Purchase Orders สำเร็จแล้ว');
    }
}