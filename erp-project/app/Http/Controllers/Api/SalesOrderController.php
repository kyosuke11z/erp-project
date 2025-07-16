<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalesOrderResource;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * แสดงรายการใบสั่งขายทั้งหมด
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // เริ่มต้น Query Builder พร้อม Eager Loading
        $query = SalesOrder::with(['customer', 'items.product']);

        // เพิ่ม Filter: ค้นหาจากเลขที่ออเดอร์ หรือชื่อลูกค้า
        // เช่น /api/sales?search=SO-2024
        $query->when($request->input('search'), function ($q, $search) {
            $q->where('order_number', 'like', "%{$search}%")
              ->orWhereHas('customer', function ($customerQuery) use ($search) {
                  $customerQuery->where('name', 'like', "%{$search}%");
              });
        });

        // เพิ่ม Filter ตามสถานะ
        // เช่น /api/sales?status=pending
        $query->when($request->input('status'), function ($q, $status) {
            $q->where('status', $status);
        });

        // เพิ่ม Pagination
        $salesOrders = $query->latest()->paginate(15);

        // ส่งข้อมูลกลับไปโดยผ่าน SalesOrderResource
        return SalesOrderResource::collection($salesOrders);
    }

    public function store(Request $request)
    {
        // ส่วนนี้จะพัฒนาในขั้นตอนต่อไป
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrder $salesOrder)
    {
        // สำหรับการแสดงผลรายการเดียว เราควรจะ load relationship ทั้งหมด
        // เพื่อให้ข้อมูลที่ส่งกลับไปครบถ้วนสมบูรณ์
        $salesOrder->load(['customer', 'items.product']);

        return new SalesOrderResource($salesOrder);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesOrder $salesOrder)
    {
        // ส่วนนี้จะพัฒนาในขั้นตอนต่อไป
    }
}