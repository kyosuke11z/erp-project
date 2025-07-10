<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('รับสินค้าสำหรับใบสั่งซื้อ') }}: {{ $purchaseOrder->po_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <form wire:submit.prevent="saveReceipt" class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                {{-- Form Header --}}
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <x-input-label for="receipt_date" :value="__('วันที่รับสินค้า')" />
                        <x-text-input wire:model="receipt_date" id="receipt_date" class="block w-full mt-1" type="date" required />
                        <x-input-error :messages="$errors->get('receipt_date')" class="mt-2" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="notes" :value="__('หมายเหตุ')" />
                        <x-text-input wire:model="notes" id="notes" class="block w-full mt-1" type="text" />
                    </div>
                </div>

                {{-- Items Table --}}
                <div class="mt-8">
                    <h4 class="text-md font-medium text-gray-700">รายการสินค้าที่จะรับ</h4>
                    <div class="mt-2 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">สินค้า</th>
                                    <th class="px-4 py-2 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">จำนวนสั่ง</th>
                                    <th class="px-4 py-2 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">ค้างรับ</th>
                                    <th class="w-1/4 px-4 py-2 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">จำนวนที่รับ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($receiptItems as $index => $item)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item['product_name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $item['sku'] }}</div>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-right text-gray-500 whitespace-nowrap">{{ $item['quantity_ordered'] }}</td>
                                        <td class="px-4 py-2 text-sm text-right text-gray-500 whitespace-nowrap">{{ $item['quantity_outstanding'] }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <x-text-input wire:model="receiptItems.{{ $index }}.quantity_received" type="number" class="w-full text-right" min="0" max="{{ $item['quantity_outstanding'] }}" />
                                            <x-input-error :messages="$errors->get('receiptItems.'.$index.'.quantity_received')" class="mt-1" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end mt-8">
                    <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">
                        {{ __('ยกเลิก') }}
                    </a>
                    <x-primary-button>
                        {{ __('บันทึกการรับสินค้า') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>