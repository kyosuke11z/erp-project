<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Categories\Index as CategoryIndex;

Route::view('/', 'welcome');

// จัดกลุ่ม Route ที่ต้องมีการยืนยันตัวตน (auth)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');
    Route::get('categories', CategoryIndex::class)
        ->middleware('role:Admin')
        ->name('categories.index');
    Route::get('categories/trash', \App\Livewire\Categories\Trash::class)
        ->name('categories.trash');
    Route::get('products', \App\Livewire\Products\Index::class)
        ->name('products.index');
        Route::get('products/trash', \App\Livewire\Products\Trash::class)
        ->name('products.trash');
});
require __DIR__.'/auth.php';
