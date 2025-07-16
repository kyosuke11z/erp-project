<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SalesOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| นี่คือไฟล์สำหรับลงทะเบียน API routes ทั้งหมดของแอปพลิเคชัน
| Routes ทั้งหมดในนี้จะถูกกำหนด prefix เป็น /api โดยอัตโนมัติ
| และจะใช้ middleware group ชื่อ 'api'
|
*/

// กำหนดเส้นทางสำหรับ Product API
Route::apiResource('products', ProductController::class);
// กำหนดเส้นทางสำหรับ Customer API
Route::apiResource('customers', CustomerController::class);
// กำหนดเส้นทางสำหรับ Sales Order API
Route::apiResource('sales', SalesOrderController::class);