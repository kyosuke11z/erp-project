<?php

namespace Tests\Unit\Business;

use App\Exceptions\InsufficientStockException;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class StockManagementTest extends TestCase
{
    use RefreshDatabase; // <-- สำคัญมาก! ทำให้ทุก test case เริ่มต้นด้วยฐานข้อมูลที่ว่างเปล่า

    /**
     * ทดสอบว่าสต็อกสินค้าถูกตัดอย่างถูกต้องเมื่อมีการสร้าง Sales Order
     *
     * @return void
     */
    public function test_it_deducts_stock_correctly_when_sales_order_is_created(): void
    {
        // เรียกใช้ Seeder เพื่อสร้าง Roles และ Permissions ที่จำเป็นก่อน
        $this->seed(RolesAndPermissionsSeeder::class);
        // 1. Arrange: จัดเตรียมข้อมูล
        // สร้างสินค้าขึ้นมา 1 ชิ้น โดยมีสต็อกเริ่มต้น 100 ชิ้น
        $product = Product::factory()->create(['quantity' => 100]);
        // สร้างลูกค้า
        $customer = Customer::factory()->create();

        // 2. Act: เรียกใช้งาน Logic ที่ต้องการทดสอบ
        // สร้าง Sales Order โดยสั่งซื้อสินค้าชิ้นนั้นจำนวน 10 ชิ้น
        $salesOrder = SalesOrder::factory()->create([
            'customer_id' => $customer->id,
        ]);

        // เพิ่มรายการสินค้าใน Sales Order
        $salesOrder->items()->create([
            'product_id' => $product->id,
            'quantity' => 10,
            'price' => $product->price,
            'subtotal' => $product->price * 10, // เพิ่ม: คำนวณและใส่ค่า subtotal
        ]);

        
        // *** Logic การตัดสต็อกจริงๆ จะถูกเรียกโดยอัตโนมัติผ่าน Model Observer ที่เรากำลังจะสร้าง ***
        // 3. Assert: ตรวจสอบผลลัพธ์
        // ตรวจสอบว่าสต็อกของสินค้าในฐานข้อมูลลดลงเหลือ 90 ชิ้นจริงหรือไม่
        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 90]);
    }

    /**
     * ทดสอบว่าระบบจะโยน Exception เมื่อพยายามสร้างออเดอร์ที่สต็อกสินค้าไม่เพียงพอ
     *
     * @return void
     */
    public function test_it_throws_an_exception_when_creating_an_order_item_with_insufficient_stock(): void
    {
        // 1. Arrange: จัดเตรียมข้อมูล
        $this->seed(RolesAndPermissionsSeeder::class);
        // สร้างสินค้าที่มีสต็อกน้อย (เพียง 5 ชิ้น)
        $product = Product::factory()->create(['quantity' => 5]);
        $customer = Customer::factory()->create();
        $salesOrder = SalesOrder::factory()->create(['customer_id' => $customer->id]);

        // 2. Expect: บอก PHPUnit ว่าเราคาดหวังว่าจะเกิด Exception ประเภทนี้
        $this->expectException(InsufficientStockException::class);

        // 3. Act: พยายามสร้างรายการสั่งซื้อที่เกินจำนวนสต็อก (สั่ง 10 ชิ้น)
        // การกระทำนี้ควรจะทำให้เกิด Exception และทำให้เทสผ่าน
        $salesOrder->items()->create([
            'product_id' => $product->id,
            'quantity' => 10, // สั่งซื้อ 10 ชิ้น ซึ่งมากกว่าสต็อกที่มี (5)
            'price' => $product->price,
            'subtotal' => $product->price * 10,
        ]);
    }
     /**
     * ทดสอบว่าสต็อกสินค้าถูกคืนกลับอย่างถูกต้องเมื่อมีการลบ Sales Order Item
     *
     * @return void
     */
    public function test_it_restores_stock_when_sales_order_item_is_deleted(): void
    {
        // 1. Arrange: จัดเตรียมข้อมูล
        $this->seed(RolesAndPermissionsSeeder::class);
        $product = Product::factory()->create(['quantity' => 50]);
        $customer = Customer::factory()->create();
        $salesOrder = SalesOrder::factory()->create(['customer_id' => $customer->id]);

        // สร้าง item, ณ จุดนี้ observer จะทำงานและตัดสต็อกไป 15 เหลือ 35
        $item = $salesOrder->items()->create([
            'product_id' => $product->id,
            'quantity' => 15,
            'price' => $product->price,
            'subtotal' => $product->price * 15,
        ]);

        // 2. Act: ลบ item ที่เพิ่งสร้างไป
        // การกระทำนี้ควรจะไปกระตุ้น event "deleted" ใน Observer
        $item->delete();

        // 3. Assert: ตรวจสอบผลลัพธ์
        // ตรวจสอบว่าสต็อกของสินค้ากลับมาเป็น 50 เท่าเดิม
        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 50]);
    }
}