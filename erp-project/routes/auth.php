<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// กลุ่มของ Route สำหรับผู้ใช้ที่ยังไม่ได้ล็อกอิน (Guest)
Route::middleware('guest')->group(function () {
    // ปิดเส้นทางการสมัครสมาชิกโดยการคอมเมนต์บรรทัดนี้ไว้
     Volt::route('register', 'pages.auth.register')
         ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

// กลุ่มของ Route สำหรับผู้ใช้ที่ล็อกอินแล้ว
Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    // เส้นทางนี้ต้องใช้ Controller แบบดั้งเดิมตามที่ Breeze กำหนดมา
    // เพื่อจัดการกับลิงก์ยืนยันอีเมลที่ถูกเซ็นชื่อ (signed URL)
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');

    // เพิ่มเส้นทางสำหรับ Logout ที่ขาดหายไป
    Volt::route('logout', 'pages.auth.logout')
        ->name('logout');
});
