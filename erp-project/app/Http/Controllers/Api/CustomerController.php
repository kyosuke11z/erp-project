<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * แสดงรายการข้อมูลลูกค้าทั้งหมด
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // เริ่มต้น Query Builder
        $query = Customer::query();

        // เพิ่ม Filter: ค้นหาจากชื่อ, อีเมล หรือเบอร์โทรศัพท์
        // เช่น /api/customers?search=John
        $query->when($request->input('search'), function ($q, $search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });

        // เพิ่ม Pagination
        $customers = $query->latest()->paginate(15);

        // ส่งข้อมูลกลับไปโดยผ่าน CustomerResource
        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ส่วนนี้จะพัฒนาในขั้นตอนต่อไป
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        // ส่งข้อมูล customer ที่ถูกโหลดมาแล้วกลับไปโดยผ่าน Resource
        // สำหรับ Customer เราไม่จำเป็นต้อง load relationship เพิ่มเติม
        // เพราะข้อมูลที่ต้องการอยู่ในตารางตัวเองครบแล้ว
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        // ส่วนนี้จะพัฒนาในขั้นตอนต่อไป
    }
}