<?php


namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
 
}