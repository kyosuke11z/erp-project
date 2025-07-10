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
use App\Http\Controllers\PurchaseOrderController;
use App\Livewire\Users\UserManagement;
use App\Livewire\RoleManagement;
use App\Livewire\RolePermissionManager;
use App\Livewire\SupplierReturn; // คอมเมนต์: เพิ่ม use statement สำหรับ Component ที่สร้างขึ้นใหม่

Route::view('/', 'welcome');

// จัดกลุ่ม Route ที่ต้องมีการยืนยันตัวตน (auth)
Route::middleware(['auth', 'verified'])->group(function () { // and email verification
    // Core application routes
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // User Management (Admin Only)
    Route::get('/users', UserManagement::class)->name('users.index')->middleware('can:view-user-management');
    Route::get('roles', RoleManagement::class)->name('roles.index');
    Route::get('roles/{role}/permissions', RolePermissionManager::class)->name('roles.permissions');
    // Categories Module (Admin Only)
    Route::prefix('categories')->name('categories.')->middleware('role:Admin')->group(function () {
        Route::get('/', Categories\Index::class)->name('index');
        Route::get('/trash', Categories\Trash::class)->name('trash');
    });

    // Products Module
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', Products\Index::class)->name('index');
        Route::get('/trash', Products\Trash::class)->name('trash');
    });

    // Customers Module
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', Customers\CustomerPage::class)->name('index');
        Route::get('/trash', Customers\TrashPage::class)->name('trash');
    });

    // Sales Module
    Route::prefix('sales')->name('sales.')->middleware('role:Admin')->group(function () {
        Route::get('/', Sales\OrderIndex::class)->name('index');
        Route::get('/create', Sales\CreatePage::class)->name('create');
        Route::get('/{salesOrder}', Sales\OrderShow::class)->name('show');
        Route::get('/{salesOrder}/edit', Sales\EditPage::class)->name('edit');
    });

    // Suppliers Module
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', Suppliers\Index::class)->name('index');
        // In the future, you can add a trash route here
        // Route::get('/trash', Suppliers\Trash::class)->name('trash');
    });

    // Purchase Orders Module
    Route::prefix('purchase-orders')->name('purchase-orders.')->group(function () {
        Route::get('/', PurchaseOrders\Index::class)->name('index');
        
        Route::get('/create', PurchaseOrders\CreatePage::class)->name('create');
        Route::get('/{purchaseOrder}', PurchaseOrders\Show::class)->name('show');
        // PDF generation route
        Route::get('/{purchaseOrder}/edit', PurchaseOrders\EditPage::class)->name('edit');
        Route::get('/{purchaseOrder}/pdf', [PurchaseOrderController::class, 'generatePdf'])->name('pdf');
    });

    // Goods Receipt Module - จัดกลุ่มให้เป็นระเบียบ
    Route::prefix('goods-receipt')->name('goods-receipt.')->group(function () {
        Route::get('/create/{purchaseOrder}', GoodsReceipt\CreatePage::class)->name('create');
        Route::get('/{goodsReceipt}', GoodsReceipt\ShowPage::class)->name('show');
        Route::get('/{goodsReceipt}/pdf', [\App\Http\Controllers\GoodsReceiptController::class, 'generatePdf'])->name('pdf');
    });

    // Supplier Returns Module - จัดกลุ่ม Route สำหรับการคืนสินค้า
    Route::prefix('supplier-returns')->name('supplier-returns.')->group(function () {
        // You can add an index page later
        // Route::get('/', SupplierReturn\IndexPage::class)->name('index');
        Route::get('/create/{goodsReceipt}', SupplierReturn\CreatePage::class)->name('create');
    });
});

// Authentication routes (e.g., login, register)
require __DIR__.'/auth.php';