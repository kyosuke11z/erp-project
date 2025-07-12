<?php

use Illuminate\Support\Facades\Route;

// Import Livewire Component Namespaces for cleaner route definitions
use App\Livewire\Categories;
use App\Livewire\Products;
use App\Livewire\Customers;
use App\Livewire\Suppliers;
use App\Livewire\PurchaseOrders;
use App\Livewire\Sales;
use App\Livewire\GoodsReceipt;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\PurchaseOrderController;
use App\Livewire\SupplierReturn\IndexPage as SupplierReturnIndex;
use App\Livewire\SupplierReturn\ShowPage as SupplierReturnShow;
use App\Livewire\SupplierReturn\CreatePage as SupplierReturnCreate;
use App\Livewire\Products\CreatePage as ProductCreatePage;
use App\Livewire\Products\EditPage as ProductEditPage;
use App\Livewire\Users; // คอมเมนต์: เพิ่ม namespace สำหรับ User Management


Route::view('/', 'welcome');

// จัดกลุ่ม Route ที่ต้องมีการยืนยันตัวตน (auth)
Route::middleware(['auth', 'verified'])->group(function () { // and email verification
    // Core application routes
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Finance Module
    // คอมเมนต์: กำหนดสิทธิ์ให้ Admin และ Manager เข้าถึงโมดูลการเงิน
    Route::prefix('finance')->name('finance.')->middleware('role:Admin|Manager')->group(function () {
        Route::get('/', [FinancialTransactionController::class, 'index'])->name('index');
        // คอมเมนต์: เพิ่ม Route สำหรับหน้าสร้างและแก้ไขรายการการเงิน
        Route::get('/create', \App\Livewire\Finance\CreatePage::class)->name('create');
        Route::get('/{transaction}/edit', \App\Livewire\Finance\EditPage::class)->name('edit');
    });

    // User Management Module (Admin Only)
    Route::prefix('users')->name('users.')->middleware('role:Admin')->group(function () {
        Route::get('/', Users\Index::class)->name('index');
    });

    // Categories Module
    // คอมเมนต์: กำหนดให้ Admin และ Manager เท่านั้นที่สามารถจัดการหมวดหมู่ได้
    Route::prefix('categories')->name('categories.')->middleware('role:Admin|Manager')->group(function () {
        Route::get('/', Categories\Index::class)->name('index');
        Route::get('/trash', Categories\Trash::class)->name('trash');
    });

    // Products Module
    // คอมเมนต์: กำหนดให้ทุก Role ที่ล็อกอินสามารถเข้าถึงโมดูลสินค้าได้ แต่การกระทำจะถูกจำกัดโดย Policy/Component
    Route::prefix('products')->name('products.')->middleware('role:Admin|Manager|Staff')->group(function () {
        Route::get('/', Products\Index::class)->name('index');
        Route::get('/trash', Products\Trash::class)->name('trash');
        // คอมเมนต์: เปลี่ยนไปใช้ CreatePage และ EditPage ที่เราสร้างขึ้นใหม่
        Route::get('/create', ProductCreatePage::class)->name('create');
        Route::get('/{product}/edit', ProductEditPage::class)->name('edit');
    });

    // Customers Module
    Route::prefix('customers')->name('customers.')->middleware('role:Admin|Manager|Staff')->group(function () {
        Route::get('/', Customers\CustomerPage::class)->name('index');
        Route::get('/trash', Customers\TrashPage::class)->name('trash');
    });

    // Sales Module
    Route::prefix('sales')->name('sales.')->middleware('role:Admin|Manager|Staff')->group(function () {
        Route::get('/', Sales\OrderIndex::class)->name('index');
        Route::get('/create', Sales\CreatePage::class)->name('create');
        Route::get('/{salesOrder}', Sales\OrderShow::class)->name('show');
        Route::get('/{salesOrder}/edit', Sales\EditPage::class)->name('edit');
    });

    // Suppliers Module
    Route::prefix('suppliers')->name('suppliers.')->middleware('role:Admin|Manager|Staff')->group(function () {
        Route::get('/', Suppliers\Index::class)->name('index');
    });

    // Purchase Orders Module
    Route::prefix('purchase-orders')->name('purchase-orders.')->middleware('role:Admin|Manager|Staff')->group(function () {
        Route::get('/', PurchaseOrders\Index::class)->name('index');
        Route::get('/create', PurchaseOrders\CreatePage::class)->name('create');
        Route::get('/{purchaseOrder}', PurchaseOrders\Show::class)->name('show');
        Route::get('/{purchaseOrder}/edit', PurchaseOrders\EditPage::class)->name('edit');
        Route::get('/{purchaseOrder}/pdf', [PurchaseOrderController::class, 'generatePdf'])->name('pdf');
    });

    // Goods Receipt Module - จัดกลุ่มให้เป็นระเบียบ
    Route::prefix('goods-receipt')->name('goods-receipt.')->middleware('role:Admin|Manager|Staff')->group(function () {
        Route::get('/create/{purchaseOrder}', GoodsReceipt\CreatePage::class)->name('create');
        Route::get('/{goodsReceipt}', GoodsReceipt\ShowPage::class)->name('show');
        Route::get('/{goodsReceipt}/pdf', [\App\Http\Controllers\GoodsReceiptController::class, 'generatePdf'])->name('pdf');
    });

    // Supplier Returns Module - จัดกลุ่ม Route สำหรับการคืนสินค้า
    Route::prefix('supplier-returns')->name('supplier-returns.')->middleware('role:Admin|Manager|Staff')->group(function () {
        Route::get('/', SupplierReturnIndex::class)->name('index');
        Route::get('/create/{goodsReceipt}', SupplierReturnCreate::class)->name('create');
        Route::get('/{supplierReturn}', SupplierReturnShow::class)->name('show');
    });
});

// Authentication routes (e.g., login, register)
require __DIR__.'/auth.php';