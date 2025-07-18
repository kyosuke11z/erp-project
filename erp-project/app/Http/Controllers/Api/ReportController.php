<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * ดึงข้อมูลสรุปยอดขายรายเดือน
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function monthlySales(Request $request)
    {
        // ตรวจสอบและกรองข้อมูลที่รับเข้ามา อนุญาตให้รับเฉพาะ 'year' ที่เป็นตัวเลข
        $validated = $request->validate([
            'year' => 'sometimes|integer|min:1900|max:2100'
        ]);

        // เริ่มต้น Query Builder จากโมเดล SalesOrder
        $query = SalesOrder::query()
            ->select(
                // ใช้ DB::raw เพื่อเขียนคำสั่ง SQL ดิบๆ สำหรับฟังก์ชันที่ซับซ้อน
                DB::raw('YEAR(order_date) as year'),
                DB::raw('MONTH(order_date) as month'),
                DB::raw('COUNT(id) as total_orders'),
                DB::raw('SUM(total_amount) as total_sales')
            )
            // เพิ่ม: เงื่อนไขการกรองข้อมูลตามปี ถ้ามีการส่ง 'year' มาใน request
            ->when(isset($validated['year']), function ($q) use ($validated) {
                return $q->whereYear('order_date', $validated['year']);
            })
            ->groupBy('year', 'month') // จัดกลุ่มตามปีและเดือน
            ->orderBy('year', 'desc')   // เรียงจากปีล่าสุดก่อน
            ->orderBy('month', 'desc');  // แล้วเรียงจากเดือนล่าสุดก่อน

        $monthlySales = $query->get();

        return response()->json($monthlySales);
    }

    /**
     * ดึงข้อมูลสรุปสินค้าขายดีตามจำนวนที่กำหนด
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function topSellingProducts(Request $request)
    {
        $limit = $request->query('limit', 5); // รับค่า limit จาก query string, ถ้าไม่มีให้ใช้ 5

        $topProducts = DB::table('sales_order_items')
            ->join('products', 'sales_order_items.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', 'products.sku', DB::raw('SUM(sales_order_items.quantity) as total_quantity_sold'))
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_quantity_sold', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($topProducts);
    }
}