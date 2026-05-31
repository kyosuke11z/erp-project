<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel ERP Portfolio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideInUp { from { transform: translateY(40px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .fade-in { animation: fadeIn 1s ease-out forwards; }
        .slide-in-up { animation: slideInUp 0.8s ease-out forwards; }
        .feature-card { opacity: 0; animation: slideInUp 0.7s ease-out forwards; }
        .light-bg {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e2e8f0' fill-opacity='0.5'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="antialiased font-sans">
<div class="relative min-h-screen flex flex-col light-bg selection:bg-red-500 selection:text-white">

    <!-- Top nav -->
    <div class="absolute top-0 right-0 p-6 text-right z-10">
        @auth
            <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">Log in</a>
        @endauth
    </div>

    <!-- Hero -->
    <header class="text-center pt-24 pb-16 px-6 fade-in">
        <div class="slide-in-up">
            <span class="inline-block bg-red-100 text-red-700 text-sm font-semibold px-4 py-1 rounded-full mb-6 tracking-wide uppercase">Portfolio Project</span>
            <h1 class="text-5xl md:text-6xl font-extrabold mb-5 text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">
                Laravel ERP System
            </h1>
            <p class="text-lg md:text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                ระบบบริหารจัดการทรัพยากรองค์กร (ERP) ที่สร้างด้วย Laravel 12, Livewire 3 และ Tailwind CSS<br class="hidden md:block">
                ครอบคลุมตั้งแต่การขาย, จัดซื้อ, สต็อก, การเงิน ไปจนถึง RESTful API พร้อม Unit & Feature Tests
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ url('/dashboard') }}"
                   class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full transition duration-200 transform hover:scale-105 text-base shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    เข้าสู่แดชบอร์ด
                </a>
                <a href="https://github.com/kyosuke11z/erp-project" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-800 font-bold py-3 px-8 rounded-full transition duration-200 border border-gray-300 text-base shadow-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.09-.745.083-.729.083-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23A11.51 11.51 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.29-1.552 3.297-1.23 3.297-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.295 24 12c0-6.63-5.37-12-12-12z"/></svg>
                    ดู Source Code
                </a>
            </div>
        </div>
    </header>

    <!-- Tech Stack Badges -->
    <div class="flex flex-wrap justify-center gap-3 px-6 pb-16 fade-in">
        <span class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-full px-4 py-1.5 text-sm font-medium text-gray-700 shadow-sm">
            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3.6L1.5 20.4h21L12 3.6z"/></svg>
            Laravel 12
        </span>
        <span class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-full px-4 py-1.5 text-sm font-medium text-gray-700 shadow-sm">🔴 Livewire 3</span>
        <span class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-full px-4 py-1.5 text-sm font-medium text-gray-700 shadow-sm">🎨 Tailwind CSS 3</span>
        <span class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-full px-4 py-1.5 text-sm font-medium text-gray-700 shadow-sm">🗄️ MySQL / SQLite</span>
        <span class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-full px-4 py-1.5 text-sm font-medium text-gray-700 shadow-sm">🔐 Laravel Sanctum</span>
        <span class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-full px-4 py-1.5 text-sm font-medium text-gray-700 shadow-sm">🛡️ Spatie RBAC</span>
        <span class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-full px-4 py-1.5 text-sm font-medium text-gray-700 shadow-sm">🐘 PHP 8.2+</span>
    </div>

    <!-- Feature Cards -->
    <section class="w-full max-w-6xl mx-auto px-6 pb-20">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">ฟีเจอร์หลักของระบบ</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="feature-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow" style="animation-delay:0.1s">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Dashboard & Analytics</h3>
                <p class="text-gray-600 text-sm leading-relaxed">กราฟยอดขาย, สินค้าขายดี 5 อันดับ, ตัวชี้วัดรายรับ-รายจ่าย พร้อม date range filter แบบ real-time</p>
            </div>

            <div class="feature-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow" style="animation-delay:0.15s">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Sales Management</h3>
                <p class="text-gray-600 text-sm leading-relaxed">สร้างใบสั่งขาย, ตัดสต็อกอัตโนมัติผ่าน Eloquent Observer, ตรวจสอบสต็อกก่อนสร้าง, Export PDF</p>
            </div>

            <div class="feature-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow" style="animation-delay:0.2s">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Purchasing & Stock</h3>
                <p class="text-gray-600 text-sm leading-relaxed">ใบสั่งซื้อ, รับสินค้า (Goods Receipt), คืนสินค้าซัพพลายเออร์ พร้อม validation จำนวนคืนที่ซับซ้อน</p>
            </div>

            <div class="feature-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow" style="animation-delay:0.25s">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Finance Module</h3>
                <p class="text-gray-600 text-sm leading-relaxed">บันทึกรายรับ-รายจ่าย, ผูก Sales/Purchase Order, รายงานสรุป, Export PDF & Excel (maatwebsite)</p>
            </div>

            <div class="feature-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow" style="animation-delay:0.3s">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">RESTful API + Sanctum</h3>
                <p class="text-gray-600 text-sm leading-relaxed">API endpoints สำหรับ Products, Customers, Sales พร้อม Token Auth, API Resources, Pagination & Filtering</p>
            </div>

            <div class="feature-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow" style="animation-delay:0.35s">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">RBAC & Testing</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Role-based Access Control (Admin/Manager/Staff) ด้วย Spatie, Unit Tests สำหรับ business logic, Feature Tests สำหรับ API</p>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto py-8 text-center text-sm text-gray-500 border-t border-gray-200">
        <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} · PHP v{{ PHP_VERSION }} · Built as a portfolio project</p>
    </footer>

</div>
</body>
</html>
