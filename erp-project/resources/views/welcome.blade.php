<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio - ERP Project</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- เพิ่มสไตล์สำหรับ animation และการแสดงผล -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .fade-in { animation: fadeIn 1.5s ease-out forwards; }
        .slide-in-up { animation: slideInUp 1s ease-out forwards; }
        .animated-card {
            opacity: 0; /* เริ่มต้นให้การ์ดโปรเจคซ่อนไว้ */
            animation: slideInUp 1s ease-out forwards;
        }
        /* Pattern background from Hero Patterns */
        .light-mode-bg {
            background-color: #f8fafc; /* สีพื้นหลัง light-mode ที่สบายตา (Tailwind's gray-50) */
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e2e8f0' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="antialiased font-sans">
    <!-- Container หลัก -->
    <div class="relative min-h-screen flex flex-col items-center justify-center light-mode-bg bg-center selection:bg-red-500 selection:text-white">

        <!-- Navigation Links -->
        <div class="absolute top-0 right-0 p-6 text-right z-10">
            @auth
                <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                @endif
            @endauth
        </div>

        <!-- Hero Section -->
        <header class="text-center p-6 fade-in w-full">
            <div class="slide-in-up">
                <h1 class="text-5xl md:text-7xl font-extrabold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-400">
                    ERP Portfolio
                </h1>
                <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    ผลงานระบบบริหารจัดการธุรกิจ (ERP) ที่สร้างขึ้นด้วยเทคโนโลยีที่ทันสมัย
                </p>
                <a href="{{ url('/dashboard') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 transform hover:scale-105 text-lg">
                    เข้าสู่แดชบอร์ด
                </a>
            </div>
        </header>

        <!-- Projects Section -->
        <section id="projects" class="w-full max-w-7xl mx-auto py-20 px-6">
            <h2 class="text-4xl font-bold text-center mb-12 slide-in-up text-gray-800">ผลงานเด่น (Featured Projects)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Project 1: ERP System -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-2 animated-card" style="animation-delay: 0.2s;">
                    <img src="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="ERP System Concept" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-2 text-gray-800">ERP System</h3>
                        <p class="text-gray-600">ระบบ ERP ครบวงจร สร้างด้วย Laravel, Livewire และ Tailwind CSS เพื่อการจัดการข้อมูลธุรกิจอย่างมีประสิทธิภาพ</p>
                    </div>
                </div>
                <!-- Project 2: E-commerce Platform -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-2 animated-card" style="animation-delay: 0.4s;">
                    <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="E-commerce Storefront" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-2 text-gray-800">E-commerce Platform</h3>
                        <p class="text-gray-600">แพลตฟอร์มอีคอมเมิร์ซสมัยใหม่ เน้นประสบการณ์ผู้ใช้ และการจัดการสต็อกแบบเรียลไทม์</p>
                    </div>
                </div>
                <!-- Project 3: Analytics Dashboard -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-2 animated-card" style="animation-delay: 0.6s;">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Analytics Dashboard with Charts" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-2 text-gray-800">Analytics Dashboard</h3>
                        <p class="text-gray-600">แดชบอร์ดวิเคราะห์ข้อมูลพร้อมกราฟและตัวชี้วัดสำคัญ เพื่อการแสดงผลข้อมูลเชิงลึกทางธุรกิจ</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-10 text-center text-sm text-gray-500 w-full">
            <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
        </footer>
    </div>
</body>
</html>
