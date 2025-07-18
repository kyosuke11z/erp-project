<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SalesOrderController;
use App\Http\Controllers\Api\ReportController;
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

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

// กำหนดเส้นทางสำหรับ Product API
Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');
// กำหนดเส้นทางสำหรับ Customer API
Route::apiResource('customers', CustomerController::class)->middleware('auth:sanctum');
// กำหนดเส้นทางสำหรับ Sales Order API
Route::apiResource('sales', SalesOrderController::class)->middleware('auth:sanctum');
// Reports
Route::get('/reports/monthly-sales', [ReportController::class, 'monthlySales'])->middleware('auth:sanctum');
Route::get('/reports/top-selling-products', [ReportController::class, 'topSellingProducts'])->middleware('auth:sanctum');