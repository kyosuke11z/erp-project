<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('สร้างคำสั่งขายใหม่') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <form wire:submit.prevent="save">
                    {{-- Customer, Order Date, and Status Section --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <x-input-label for="customer" :value="__('ลูกค้า')" />
                            <x-select-input id="customer" wire:model="customerId" class="mt-1 block w-full">
                                <option value="">-- เลือกลูกค้า --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </x-select-input>
                            <x-input-error :messages="$errors->get('customerId')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="order_date" :value="__('วันที่สั่งซื้อ')" />
                            <x-text-input type="date" id="order_date" wire:model="orderDate" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('orderDate')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="status" :value="__('สถานะ')" />
                            <x-select-input id="status" wire:model="status" class="mt-1 block w-full">
                                <option value="pending">รอดำเนินการ</option>
                                <option value="completed">เสร็จสิ้น</option>
                                <option value="cancelled">ยกเลิก</option>
                            </x-select-input>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>

                    {{-- ส่วนสำหรับเพิ่มสินค้า --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">เพิ่มรายการสินค้า</h3>
                        <div class="flex items-start gap-4">
                            <div class="flex-grow">
                                <x-input-label for="selectedProductId" :value="__('เลือกสินค้า')" />
                                <x-select-input id="selectedProductId" wire:model="selectedProductId" class="mt-1 block w-full">
                                    <option value="">-- กรุณาเลือกสินค้า --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} (คงเหลือ: {{ $product->quantity }}) (ราคา: {{ number_format($product->price, 2) }} บาท)</option>
                                    @endforeach
                                </x-select-input>
                                <x-input-error :messages="$errors->get('selectedProductId')" class="mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 invisible">เพิ่ม</label>
                                <x-secondary-button type="button" wire:click.prevent="addProduct" class="mt-1">เพิ่ม</x-secondary-button>
                            </div>
                        </div>
                    </div>

                    {{-- ตารางแสดงรายการสินค้าที่สั่งซื้อ --}}
                    <div class="mt-6">
                        <x-input-error :messages="$errors->get('orderItems')" class="mt-2 mb-2" />
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สินค้า</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ราคาต่อหน่วย</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">จำนวน</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ราคารวม</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">ลบ</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($orderItems as $index => $item)
                                        <tr wire:key="item-{{ $index }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['product_name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($item['price'], 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <x-text-input type="number" wire:model.live="orderItems.{{ $index }}.quantity" min="1" class="w-24 text-center" />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                                {{ number_format($item['price'] * $item['quantity'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button type="button" wire:click.prevent="removeProduct({{ $index }})" class="text-red-600 hover:text-red-900">ลบ</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">ยังไม่มีรายการสินค้า</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex items-center justify-end mt-8 border-t border-gray-200 pt-6 gap-4">
                        <a href="{{ route('sales.index') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                            {{ __('ย้อนกลับ') }}
                        </a>
                        <x-primary-button type="submit" wire:loading.attr="disabled">บันทึกคำสั่งขาย</x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
