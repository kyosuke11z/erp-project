<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Categories;
use App\Livewire\Products;
use App\Livewire\Customers;
use App\Livewire\Sales;
use App\Livewire\Sales\OrderIndex;
use App\Livewire\Sales\OrderShow;
use App\Livewire\Sales\EditPage;
use App\Livewire\Sales\CreatePage;


Route::view('/', 'welcome');

// จัดกลุ่ม Route ที่ต้องมีการยืนยันตัวตน (auth)
Route::middleware(['auth', 'verified'])->group(function () {
    // Core application routes
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

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
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', OrderIndex::class)->name('index');
        Route::get('/create', CreatePage::class)->name('create');
        Route::get('/{salesOrder}', OrderShow::class)->name('show');
        Route::get('/{salesOrder}/edit', EditPage::class)->name('edit');
    });
});

// Authentication routes (e.g., login, register)
require __DIR__.'/auth.php';
