<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('แดชบอร์ด') }}
                    </x-nav-link>
                    
                    {{-- คอมเมนต์: เปลี่ยนจากการเช็ค Role 'Admin' มาเป็นการเช็ค Permission เพื่อความยืดหยุ่น --}}
                    {{-- Manager และ Role อื่นๆ ที่มีสิทธิ์จะเห็นเมนูเหล่านี้ด้วย --}}
                    @can('user-list')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('จัดการผู้ใช้งาน') }}
                        </x-nav-link>
                    @endcan
                    @can('category-list')
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')" wire:navigate>
                            {{ __('หมวดหมู่') }}
                        </x-nav-link>
                    @endcan
                    @can('product-list')
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')" wire:navigate>
                            {{ __('สินค้า') }}
                        </x-nav-link>
                    @endcan
                    @can('customer-list')
                        <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.index')" wire:navigate>
                            {{ __('ลูกค้า') }}
                        </x-nav-link>
                    @endcan
                    @can('supplier-list')
                        <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" wire:navigate>
                            {{ __('ซัพพลายเออร์') }}
                        </x-nav-link>
                    @endcan
                    @can('purchase-order-list')
                        <x-nav-link :href="route('purchase-orders.index')" :active="request()->routeIs('purchase-orders.*')" wire:navigate>
                            {{ __('ใบสั่งซื้อ') }}
                        </x-nav-link>
                    @endcan
                    @can('sales-order-list')
                        <x-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*')" wire:navigate>
                            {{ __('คำสั่งขาย') }}
                        </x-nav-link>
                    @endcan
                    {{-- Finance Dropdown --}}
                    @can('view finance')
                        <div class="relative flex items-center">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('finance.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                        <div>{{ __('การเงิน') }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('finance.index')" :active="request()->routeIs('finance.index')" wire:navigate>
                                        {{ __('รายการการเงิน') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('finance.categories.index')" :active="request()->routeIs('finance.categories.index')" wire:navigate>
                                        {{ __('จัดการหมวดหมู่') }}
                                    </x-dropdown-link>
                                    {{-- คอมเมนต์: เพิ่มลิงก์สำหรับหน้ารายงานสรุป --}}
                                    <x-dropdown-link :href="route('finance.report')" :active="request()->routeIs('finance.report')" wire:navigate>
                                        {{ __('รายงานสรุป') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endcan
                </div>
            </div>
                  <!-- Notifications Bell -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @livewire('notifications.notification-bell')
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                {{-- คอมเมนต์: เพิ่ม aria-label เพื่อให้ปุ่มมีข้อความที่เข้าถึงได้ (Accessibility) --}}
                <button @click="open = ! open" aria-label="Toggle navigation" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- คอมเมนต์: เปลี่ยนจากการเช็ค Role 'Admin' มาเป็นการเช็ค Permission ในมุมมอง Responsive --}}
            @can('user-list')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('จัดการผู้ใช้งาน') }}
                </x-responsive-nav-link>
            @endcan
            @can('category-list')
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.index')" wire:navigate>
                    {{ __('หมวดหมู่') }}
                </x-responsive-nav-link>
            @endcan
            @can('product-list')
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')" wire:navigate>
                    {{ __('สินค้า') }}
                </x-responsive-nav-link>
            @endcan
            @can('customer-list')
                <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.index')" wire:navigate>
                    {{ __('ลูกค้า') }}
                </x-responsive-nav-link>
            @endcan
            @can('supplier-list')
                <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" wire:navigate>
                    {{ __('ซัพพลายเออร์') }}
                </x-responsive-nav-link>
            @endcan
            @can('purchase-order-list')
                <x-responsive-nav-link :href="route('purchase-orders.index')" :active="request()->routeIs('purchase-orders.*')" wire:navigate>
                    {{ __('ใบสั่งซื้อ') }}
                </x-responsive-nav-link>
            @endcan
            @can('sales-order-list')
                <x-responsive-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*')" wire:navigate>
                    {{ __('คำสั่งขาย') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            {{-- คอมเมนต์: เพิ่มเมนูสำหรับระบบการเงิน (Responsive) --}}
            @can('view finance')
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ __('การเงิน') }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('finance.index')" :active="request()->routeIs('finance.index')" wire:navigate>
                        {{ __('รายการการเงิน') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('finance.categories.index')" :active="request()->routeIs('finance.categories.index')" wire:navigate>
                        {{ __('จัดการหมวดหมู่') }}
                    </x-responsive-nav-link>
                    {{-- คอมเมนต์: เพิ่มลิงก์สำหรับหน้ารายงานสรุป (Responsive) --}}
                    <x-responsive-nav-link :href="route('finance.report')" :active="request()->routeIs('finance.report')" wire:navigate>
                        {{ __('รายงานสรุป') }}
                    </x-responsive-nav-link>
                </div>
            @endcan

            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
