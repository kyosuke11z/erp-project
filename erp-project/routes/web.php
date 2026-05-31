<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Categories;
use App\Livewire\Products;
use App\Livewire\Customers;
use App\Livewire\Suppliers;
use App\Livewire\PurchaseOrders;
use App\Livewire\Sales;
use App\Livewire\GoodsReceipt;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\Finance\FinancialReportController;
use App\Livewire\Finance\FinancialReport;
use App\Http\Controllers\PurchaseOrderController;
use App\Livewire\SupplierReturn\IndexPage as SupplierReturnIndex;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\SupplierReturn\ShowPage as SupplierReturnShow;
use App\Livewire\SupplierReturn\CreatePage as SupplierReturnCreate;
use App\Livewire\Products\CreatePage as ProductCreatePage;
use App\Livewire\Products\EditPage as ProductEditPage;
use App\Livewire\Users;
use App\Livewire\Settings;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardIndex::class)->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Finance Module
    Route::prefix('finance')->name('finance.')->middleware('role:Admin|Manager')->group(function () {
        Route::get('/', [FinancialTransactionController::class, 'index'])->name('index');
        Route::get('/create', \App\Livewire\Finance\CreatePage::class)->name('create');
        Route::get('/categories', \App\Livewire\Finance\CategoryManager::class)->name('categories.index');
        Route::get('/report', FinancialReport::class)->name('report');
        Route::get('/reports/pdf', [FinancialReportController::class, 'exportPdf'])->name('report.pdf');
        Route::get('/{transaction}/edit', \App\Livewire\Finance\EditPage::class)->name('edit');
    });

    // User Management Module (Admin Only)
    Route::prefix('users')->name('users.')->middleware('role:Admin')->group(function () {
        Route::get('/', Users\Index::class)->name('index');
    });

    // Settings Module (Admin Only)
    Route::prefix('settings')->name('settings.')->middleware('role:Admin')->group(function () {
        Route::get('/', Settings\Index::class)->name('index');
    });

    // Categories Module
    Route::prefix('categories')->name('categories.')->middleware('role:Admin|Manager')->group(function () {
        Route::get('/', Categories\Index::class)->name('index');
        Route::get('/trash', Categories\Trash::class)->name('trash');
    });

    // Products Module
    Route::prefix('products')->name('products.')->middleware('role:Admin|Manager|Staff')->group(function () {
        Route::get('/', Products\Index::class)->name('index');
        Route::get('/trash', Products\Trash::class)->name('trash');
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
        Route::get('/{salesOrder}/payment/create', \App\Livewire\Sales\RecordPaymentPage::class)->name('payment.create');
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
        Route::get('/{purchaseOrder}/payment/create', \App\Livewire\PurchaseOrders\RecordPaymentPage::class)->name('payment.create');
        Route::get('/{purchaseOrder}/pdf', [PurchaseOrderController::class, 'generatePdf'])->name('pdf');
    });

    // Goods Receipt Module
    Route::prefix('goods-receipt')->name('goods-receipt.')->middleware('role:Admin|Manager|Staff')->group(function () {
        Route::get('/create/{purchaseOrder}', GoodsReceipt\CreatePage::class)->name('create');
        Route::get('/{goodsReceipt}', GoodsReceipt\ShowPage::class)->name('show');
        Route::get('/{goodsReceipt}/pdf', [\App\Http\Controllers\GoodsReceiptController::class, 'generatePdf'])->name('pdf');
    });

    // Supplier Returns Module
    Route::prefix('supplier-returns')->name('supplier-returns.')->middleware('role:Admin|Manager|Staff')->group(function () {
        Route::get('/', SupplierReturnIndex::class)->name('index');
        Route::get('/create/{goodsReceipt}', SupplierReturnCreate::class)->name('create');
        Route::get('/{supplierReturn}', SupplierReturnShow::class)->name('show');
    });
});

require __DIR__.'/auth.php';
