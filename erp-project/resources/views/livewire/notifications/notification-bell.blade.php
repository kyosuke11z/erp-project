{{-- เพิ่ม wire:poll.15s เพื่อให้คอมโพเนนต์โหลดข้อมูลใหม่ทุก 15 วินาที --}}
<div x-data="{ open: false }" @click.outside="open = false" class="relative" wire:poll.15s="loadNotifications">
    {{-- Bell Icon --}}
    <button @click="open = !open" class="relative p-1 text-gray-400 rounded-full hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
        <span class="sr-only">View notifications</span>
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full">{{ $unreadCount }}</span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-10 w-80 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
         style="display: none;">
        <div class="py-1">
            <div class="px-4 py-2 text-sm font-semibold text-gray-900 border-b">
                การแจ้งเตือน
            </div>
            <div class="max-h-80 overflow-y-auto">
                @forelse($unreadNotifications as $notification)
                    <div class="px-4 py-3 border-b hover:bg-gray-50">
                        <p class="text-sm text-gray-700">
                            {{ $notification->data['message'] }}
                        </p>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                            <button wire:click="markAsRead('{{ $notification->id }}')" class="text-xs text-indigo-600 hover:underline">ทำเครื่องหมายว่าอ่านแล้ว</button>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-4 text-sm text-center text-gray-500">
                        ไม่มีการแจ้งเตือนใหม่
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>