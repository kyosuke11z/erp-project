<?php


namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProductRequest;
use App\Http\Requests\Api\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        // กำหนดสิทธิ์การเข้าถึงสำหรับแต่ละ Action อย่างชัดเจนโดยใช้ Middleware
        // Laravel จะเรียกใช้ Policy ที่เราลงทะเบียนไว้โดยอัตโนมัติ
        // 'can:ability,model'
        $this->middleware('can:viewAny,' . Product::class)->only('index');
        $this->middleware('can:view,product')->only('show');
        $this->middleware('can:create,' . Product::class)->only('store');
        $this->middleware('can:update,product')->only('update');
        $this->middleware('can:delete,product')->only('destroy');
    }

    public function index(Request $request)
    {
        // เริ่มต้นสร้าง Query Builder
        $query = Product::with('category');

        // 1. เพิ่ม Filter: ค้นหาจากชื่อสินค้าหรือ SKU
        // ตรวจสอบว่ามี query parameter 'search' ส่งมาหรือไม่ (เช่น /api/products?search=phone)
        $query->when($request->input('search'), function ($q, $search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });

        // 2. เพิ่ม Pagination: แทนที่ get() ด้วย paginate()
        // สามารถกำหนดจำนวนรายการต่อหน้าได้ (เช่น 15 รายการต่อหน้า)
        // Laravel จะอ่าน query parameter 'page' โดยอัตโนมัติ
        $products = $query->latest()->paginate(15);

        // ส่งข้อมูลกลับไปโดยผ่าน ProductResource
        // Laravel จะแปลงข้อมูลทั้งหมดให้อยู่ในรูปแบบที่เรากำหนดใน Resource โดยอัตโนมัติ
        // และจะเพิ่มข้อมูล pagination (links, meta) เข้าไปให้เอง
        return ProductResource::collection($products);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->toModelData());

        return (new ProductResource($product->load('category')))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \App\Http\Resources\ProductResource
     */
    public function show(Product $product)
    {
        // ส่งข้อมูล product ที่ถูกโหลดมาแล้วกลับไปโดยผ่าน Resource
        // ไม่จำเป็นต้องใช้ ::collection() เพราะเป็นข้อมูลแค่รายการเดียว
        return new ProductResource($product->load('category'));
    }
         /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product       $product
     * @return \App\Http\Resources\ProductResource
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Use array_filter to remove null values so we only update fields that were sent
        $product->update(array_filter($request->toModelData()));

        return new ProductResource($product->load('category'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent(); // ส่งกลับ Status 204 No Content ซึ่งเป็นมาตรฐานสำหรับการลบสำเร็จ
    }
}
