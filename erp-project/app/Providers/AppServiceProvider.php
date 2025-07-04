<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // เมธอด register ควรจะว่างไว้สำหรับโปรเจกต์นี้
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // กำหนดให้ Pagination ใช้สไตล์ของ Tailwind CSS ทั่วทั้งแอปพลิเคชัน
        Paginator::useTailwind();

    }
}
