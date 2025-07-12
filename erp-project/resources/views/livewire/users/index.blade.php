<div>
    {{-- คอมเมนต์: ส่วนหัวของหน้า (Header) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('จัดการผู้ใช้งาน') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- คอมเมนต์: ปุ่มเพิ่มผู้ใช้ใหม่ และช่องค้นหา --}}
                    <div class="flex justify-between mb-6">
                        <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('เพิ่มผู้ใช้ใหม่') }}
                        </button>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="ค้นหาชื่อหรืออีเมล..." class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    </div>

                    {{-- คอมเมนต์: ตารางแสดงรายชื่อผู้ใช้ --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อ</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">อีเมล</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">บทบาท (Role)</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- คอมเมนต์: แสดง Role แรกที่เจอ และใช้ Badge สีต่างๆ เพื่อให้ดูง่าย --}}
                                            @if ($user->roles->isNotEmpty())
                                                @php
                                                    $roleName = $user->roles->first()->name;
                                                    $badgeColor = $roleName == 'Admin' ? 'bg-red-100 text-red-800' : ($roleName == 'Manager' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeColor }}">
                                                    {{ $roleName }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- คอมเมนต์: ปุ่มแก้ไขและลบ, ไม่แสดงปุ่มสำหรับ Admin คนแรกเพื่อความปลอดภัย --}}
                                            @if ($user->email !== 'admin@example.com')
                                                <button wire:click="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900">แก้ไข</button>
                                                <button wire:click="confirmDelete({{ $user->id }})" class="text-red-600 hover:text-red-900 ml-4">ลบ</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            ไม่พบข้อมูลผู้ใช้
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- คอมเมนต์: ส่วนแสดงผล Pagination --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- คอมเมนต์: Modal สำหรับสร้าง/แก้ไขผู้ใช้ --}}
    <!-- Modal Root -->
    <div
        x-data="{ show: @entangle('showModal') }"
        x-show="show"
        x-on:keydown.escape.window="show = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <!-- Overlay -->
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Panel -->
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative bg-white rounded-lg shadow-xl transform transition-all sm:w-full sm:max-w-lg sm:mx-auto my-8">
            <div class="px-6 py-4">
                <div class="text-lg font-medium text-gray-900">
                    {{ $userId ? 'แก้ไขข้อมูลผู้ใช้' : 'สร้างผู้ใช้ใหม่' }}
                </div>

                <div class="mt-4 space-y-4">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block font-medium text-sm text-gray-700">{{ __('ชื่อ') }}</label>
                        <input id="name" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="name">
                        @error('name') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block font-medium text-sm text-gray-700">{{ __('อีเมล') }}</label>
                        <input id="email" type="email" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="email">
                        @error('email') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block font-medium text-sm text-gray-700">{{ __('รหัสผ่าน') }}</label>
                        <input id="password" type="password" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="password">
                        @if ($userId)
                            <small class="text-gray-500">เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน</small>
                        @endif
                        @error('password') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block font-medium text-sm text-gray-700">{{ __('ยืนยันรหัสผ่าน') }}</label>
                        <input id="password_confirmation" type="password" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="password_confirmation">
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="selectedRole" class="block font-medium text-sm text-gray-700">{{ __('บทบาท (Role)') }}</label>
                        <select id="selectedRole" wire:model="selectedRole" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">-- เลือกบทบาท --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                        @error('selectedRole') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right">
                <button wire:click="closeModal" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('ยกเลิก') }}
                </button>

                <button class="ml-3 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150" wire:click="save" wire:loading.attr="disabled">
                    {{ __('บันทึก') }}
                </button>
            </div>
        </div>
    </div>
</div>