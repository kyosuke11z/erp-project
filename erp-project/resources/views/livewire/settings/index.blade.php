<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('ตั้งค่าระบบ') }}
        </h2>
    </x-slot>

    <div class="px-4 py-8 mx-auto max-w-4xl sm:px-6 lg:px-8">

        {{-- Success Banner --}}
        @if ($saved)
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 3000)"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-6 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
            >
                <svg class="h-5 w-5 shrink-0 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                บันทึกการตั้งค่าเรียบร้อยแล้ว
            </div>
        @endif

        <form wire:submit="save" class="space-y-8">

            {{-- Company Info --}}
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">ข้อมูลบริษัท</h3>
                    <p class="mt-1 text-sm text-gray-500">ข้อมูลนี้จะแสดงบนเอกสารต่างๆ เช่น ใบสั่งซื้อ และรายงาน</p>
                </div>
                <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">ชื่อบริษัท <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="company_name"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('company_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">ที่อยู่</label>
                        <textarea wire:model="company_address" rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">เบอร์โทรศัพท์</label>
                        <input type="text" wire:model="company_phone"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('company_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">อีเมล</label>
                        <input type="email" wire:model="company_email"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('company_email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Currency & Regional --}}
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">สกุลเงินและภูมิภาค</h3>
                </div>
                <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">สกุลเงิน <span class="text-red-500">*</span></label>
                        <select wire:model="currency"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="THB">THB — บาทไทย</option>
                            <option value="USD">USD — US Dollar</option>
                            <option value="EUR">EUR — Euro</option>
                            <option value="JPY">JPY — Japanese Yen</option>
                            <option value="CNY">CNY — Chinese Yuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">สัญลักษณ์สกุลเงิน <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="currency_symbol" maxlength="5"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('currency_symbol') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Timezone <span class="text-red-500">*</span></label>
                        <select wire:model="timezone"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="Asia/Bangkok">Asia/Bangkok (UTC+7)</option>
                            <option value="Asia/Singapore">Asia/Singapore (UTC+8)</option>
                            <option value="Asia/Tokyo">Asia/Tokyo (UTC+9)</option>
                            <option value="UTC">UTC</option>
                            <option value="America/New_York">America/New_York (UTC-5)</option>
                            <option value="Europe/London">Europe/London (UTC+0/+1)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">รูปแบบวันที่ <span class="text-red-500">*</span></label>
                        <select wire:model="date_format"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="d/m/Y">DD/MM/YYYY</option>
                            <option value="m/d/Y">MM/DD/YYYY</option>
                            <option value="Y-m-d">YYYY-MM-DD</option>
                            <option value="d-m-Y">DD-MM-YYYY</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- System Preferences --}}
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">ค่าเริ่มต้นระบบ</h3>
                </div>
                <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">จำนวนรายการต่อหน้า <span class="text-red-500">*</span></label>
                        <input type="number" wire:model="items_per_page" min="5" max="100"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('items_per_page') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <button type="button"
                            wire:click="$toggle('low_stock_notify')"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $low_stock_notify ? 'bg-indigo-600' : 'bg-gray-200' }}">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $low_stock_notify ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                        <span class="text-sm font-medium text-gray-700">แจ้งเตือนเมื่อสินค้าสต็อกต่ำ</span>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                        wire:loading.attr="disabled">
                    <svg wire:loading wire:target="save" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    บันทึกการตั้งค่า
                </button>
            </div>

        </form>
    </div>
</div>
