<div>
    {{-- คอมเมนต์: ส่วนหัวของหน้า แสดงชื่อเพจ --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            สร้างใบคืนสินค้า (อ้างอิง GR: {{ $goodsReceipt->receipt_number }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <form wire:submit.prevent="saveReturn">
                    {{-- คอมเมนต์: ส่วนของฟอร์มสำหรับกรอกข้อมูลหลัก --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="return_date" value="วันที่คืนสินค้า" />
                            <x-text-input wire:model="return_date" id="return_date" type="date" class="block w-full mt-1" />
                            <x-input-error :messages="$errors->get('return_date')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="reason" value="เหตุผลการคืน (ถ้ามี)" />
                            <textarea wire:model="reason" id="reason" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>
                    </div>

                    {{-- คอมเมนต์: ส่วนของตารางรายการสินค้า --}}
                    <h3 class="mt-8 mb-4 text-lg font-semibold">รายการสินค้าที่คืน</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">สินค้า</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">จำนวนที่รับ</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">เคยคืนแล้ว</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">คืนได้</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase" style="width: 150px;">จำนวนที่ต้องการคืน</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- คอมเมนต์: ใช้ @forelse เพื่อจัดการกรณีไม่มีสินค้าให้คืน --}}
                                @forelse ($returnItems as $key => $item)
                                    <tr wire:key="return-item-{{ $key }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item['product_name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $item['sku'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-center text-gray-500 whitespace-nowrap">{{ number_format($item['quantity_received']) }}</td>
                                        {{-- คอมเมนต์: แสดงจำนวนที่เคยคืนไปแล้ว --}}
                                        <td class="px-6 py-4 text-sm text-center text-orange-600 whitespace-nowrap">{{ number_format($item['quantity_already_returned']) }}</td>
                                        {{-- คอมเมนต์: แสดงจำนวนที่สามารถคืนได้สูงสุด --}}
                                        <td class="px-6 py-4 text-sm font-bold text-center text-green-600 whitespace-nowrap">{{ number_format($item['quantity_returnable']) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- คอมเมนต์: แก้ไข max ให้เป็นจำนวนที่คืนได้จริง --}}
                                            <x-text-input wire:model.live="returnItems.{{ $key }}.quantity_to_return" type="number" class="w-full text-center" min="0" max="{{ $item['quantity_returnable'] }}" />
                                            <x-input-error :messages="$errors->get('returnItems.'.$key.'.quantity_to_return')" class="mt-1" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">ไม่มีสินค้าที่สามารถคืนได้จากใบรับของนี้</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @error('general') <span class="block mt-2 text-sm text-red-600">{{ $message }}</span> @enderror

                    {{-- คอมเมนต์: ส่วนของปุ่มบันทึกและยกเลิก --}}
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('goods-receipt.show', $goodsReceipt) }}" class="px-4 py-2 mr-4 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            ยกเลิก
                        </a>
                        <x-primary-button>
                            บันทึกการคืนสินค้า
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>