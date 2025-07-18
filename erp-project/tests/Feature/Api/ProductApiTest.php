<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // รัน Seeder เพื่อสร้าง Roles และ Permissions ที่จำเป็นก่อนทุกเทส
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    /**
     * ทดสอบว่าผู้ใช้ที่ยืนยันตัวตนแล้วสามารถดึงข้อมูลสินค้าทั้งหมดได้
     *
     * @return void
     */
    public function test_authenticated_user_can_get_all_products(): void
    {
        // 1. Arrange: จัดเตรียมข้อมูล
        // สร้าง User และทำการยืนยันตัวตนด้วย Sanctum
        $user = User::factory()->create();
        $user->assignRole('Admin'); // กำหนด Role ให้ User
        Sanctum::actingAs($user);

        // สร้างสินค้าจำลอง 3 ชิ้นในฐานข้อมูล
        Product::factory()->count(3)->create();

        // 2. Act: ยิง API request
        $response = $this->getJson('/api/products');

        // 3. Assert: ตรวจสอบผลลัพธ์
        $response
            ->assertStatus(200) // ตรวจสอบว่าได้ HTTP Status 200 OK
            ->assertJsonStructure([ // ตรวจสอบว่าโครงสร้าง JSON ถูกต้อง
                'data' => [
                    '*' => ['id', 'product_name', 'sku', 'price', 'stock', 'category']
                ],
                'links', 'meta'
            ])
            ->assertJsonCount(3, 'data'); // ตรวจสอบว่ามีข้อมูลสินค้า 3 ชิ้นใน key 'data'
    }

    /**
     * ทดสอบว่าผู้ใช้ที่ไม่ได้ยืนยันตัวตนจะได้รับ 401 Unauthorized
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_get_products(): void
    {
        // 1. Arrange: ไม่ต้องทำอะไร เพราะเราต้องการจำลองผู้ใช้ที่ไม่ได้ล็อกอิน

        // 2. Act: ยิง API request โดยไม่มี Token
        $response = $this->getJson('/api/products');

        // 3. Assert: ตรวจสอบว่าได้รับ 401 Unauthorized
        $response->assertStatus(401);
    }

    /**
     * ทดสอบว่าผู้ใช้ที่ยืนยันตัวตนแล้วสามารถสร้างสินค้าใหม่ได้
     *
     * @return void
     */
    public function test_authenticated_user_can_create_a_product(): void
    {
        // 1. Arrange
        $user = User::factory()->create();
        $user->assignRole('Admin');
        Sanctum::actingAs($user);

        $category = Category::factory()->create();

        $productData = [
            'product_name' => 'New Awesome Keyboard',
            'sku' => 'SKU-AWESOME-KBD',
            'category_id' => $category->id,
            'price' => 2599.99,
            'stock' => 50,
        ];

        // 2. Act
        $response = $this->postJson('/api/products', $productData);

        // 3. Assert
        $response
            ->assertStatus(201) // ยืนยันว่า Status Code คือ 201 Created
            ->assertJson([
                'data' => [
                    'product_name' => 'New Awesome Keyboard',
                    'sku' => 'SKU-AWESOME-KBD',
                    'price' => 2599.99,
                    'stock' => 50,
                    'category' => $category->name,
                ]
            ]);

        // ตรวจสอบว่าข้อมูลถูกบันทึกลงในฐานข้อมูลจริง
        $this->assertDatabaseHas('products', ['sku' => 'SKU-AWESOME-KBD']);
    }

    /**
     * ทดสอบว่าระบบจะ trả về validation error เมื่อข้อมูลที่ส่งมาไม่ถูกต้อง
     *
     * @return void
     */
    public function test_product_creation_fails_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');
        Sanctum::actingAs($user);

        // ส่งข้อมูลที่ไม่สมบูรณ์ตาม "สัญญา" ของ API (ขาด sku, price, etc.)
        $response = $this->postJson('/api/products', ['product_name' => 'Only Name']);

        $response
            ->assertStatus(422) // ยืนยันว่า Status Code คือ 422 Unprocessable Entity
            ->assertJsonValidationErrors(['sku', 'category_id', 'price', 'stock']); // ตรวจสอบ error จาก key ที่ API รู้จัก
    }

    /**
     * ทดสอบว่าผู้ใช้ที่ยืนยันตัวตนแล้วสามารถอัปเดตข้อมูลสินค้าได้
     *
     * @return void
     */
    public function test_authenticated_user_can_update_a_product(): void
    {
        // 1. Arrange
        $user = User::factory()->create();
        $user->assignRole('Admin');
        Sanctum::actingAs($user);

        $product = Product::factory()->create();

        $updateData = [
            'product_name' => 'Updated Product Name',
            'price' => 199.99,
        ];

        // 2. Act
        $response = $this->putJson("/api/products/{$product->id}", $updateData);

        // 3. Assert
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.product_name', 'Updated Product Name')
            ->assertJsonPath('data.price', 199.99);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Product Name', 'selling_price' => 199.99]);
    }

    /**
     * ทดสอบว่าผู้ใช้ที่ยืนยันตัวตนแล้วสามารถลบข้อมูลสินค้าได้
     *
     * @return void
     */
    public function test_authenticated_user_can_delete_a_product(): void
    {
        // 1. Arrange
        $user = User::factory()->create();
        $user->assignRole('Admin');
        Sanctum::actingAs($user);

        // สร้างสินค้าขึ้นมา 1 ชิ้นเพื่อให้มีข้อมูลสำหรับลบ
        $product = Product::factory()->create();

        // 2. Act
        // ยิง API request ไปยัง endpoint สำหรับลบข้อมูล
        $response = $this->deleteJson("/api/products/{$product->id}");

        // 3. Assert
        // ตรวจสอบว่าได้รับ Status 204 No Content
        $response->assertStatus(204);
        // ตรวจสอบให้แน่ใจว่าข้อมูลสินค้านั้นได้หายไปจากฐานข้อมูลแล้วจริงๆ
        $this->assertSoftDeleted($product);
    }

    /**
     * ทดสอบว่าผู้ใช้ที่ไม่มี Role 'Admin' จะไม่สามารถสร้างสินค้าได้
     *
     * @return void
     */
    public function test_user_without_admin_role_cannot_create_a_product(): void
    {
        // 1. Arrange
        // สร้าง User ธรรมดาที่ไม่มี Role 'Admin'
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $productData = ['product_name' => 'Sneaky Product'];

        // 2. Act
        $response = $this->postJson('/api/products', $productData);

        // 3. Assert: คาดหวังว่าจะได้รับ Status 403 Forbidden
        $response->assertStatus(403);
    }

    /**
     * ทดสอบว่าผู้ใช้ที่ไม่มี Role 'Admin' จะไม่สามารถอัปเดตสินค้าได้
     *
     * @return void
     */
    public function test_user_without_admin_role_cannot_update_a_product(): void
    {
        // 1. Arrange
        $user = User::factory()->create(); // User ธรรมดา
        Sanctum::actingAs($user);
        $product = Product::factory()->create();

        // 2. Act
        $response = $this->putJson("/api/products/{$product->id}", [
            'product_name' => 'Updated by non-admin'
        ]);

        // 3. Assert
        $response->assertStatus(403);
    }

    /**
     * ทดสอบว่าผู้ใช้ที่ไม่มี Role 'Admin' จะไม่สามารถลบสินค้าได้
     *
     * @return void
     */
    public function test_user_without_admin_role_cannot_delete_a_product(): void
    {
        // 1. Arrange
        $user = User::factory()->create(); // User ธรรมดา
        Sanctum::actingAs($user);
        $product = Product::factory()->create();

        // 2. Act
        $response = $this->deleteJson("/api/products/{$product->id}");

        // 3. Assert
        $response->assertStatus(403);
        
    }
 }