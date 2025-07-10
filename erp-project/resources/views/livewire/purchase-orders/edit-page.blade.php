<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('แก้ไขใบสั่งซื้อ') }} #{{ $purchaseOrder->po_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save">
                        {{-- ส่วนข้อมูลหลัก --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700">ซัพพลายเออร์</label>
                                <select wire:model.live="supplier_id" id="supplier_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- เลือกซัพพลายเออร์ --</option>
                                    @foreach($allSuppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="order_date" class="block text-sm font-medium text-gray-700">วันที่สั่งซื้อ</label>
                                <input type="date" wire:model="order_date" id="order_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('order_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="expected_delivery_date" class="block text-sm font-medium text-gray-700">วันที่คาดว่าจะได้รับ</label>
                                <input type="date" wire:model="expected_delivery_date" id="expected_delivery_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @error('expected_delivery_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                             <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">สถานะ</label>
                                <select wire:model="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="pending">รอดำเนินการ</option>
                                    <option value="completed">เสร็จสมบูรณ์</option>
                                    <option value="cancelled">ยกเลิก</option>
                                </select>
                                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- ส่วนรายการสินค้า --}}
                        <h3 class="text-lg font-medium text-gray-900 mb-4">รายการสินค้า</h3>
                        @error('orderItems') <div class="mb-4 text-red-500 text-sm">{{ $message }}</div> @enderror

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-left">สินค้า</th>
                                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-left w-24">จำนวน</th>
                                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-left w-32">ราคา/หน่วย</th>
                                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-left w-32">ยอดรวม</th>
                                        <th class="w-10"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($orderItems as $index => $item)
                                    <tr wire:key="item-{{ $index }}">
                                        <td>
                                            <select wire:model.live="orderItems.{{ $index }}.product_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">-- เลือกสินค้า --</option>
                                                @foreach($allProducts as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('orderItems.'.$index.'.product_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="number" wire:model.live="orderItems.{{ $index }}.quantity" min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            @error('orderItems.'.$index.'.quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" wire:model.live="orderItems.{{ $index }}.price" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            @error('orderItems.'.$index.'.price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="text" readonly value="{{ number_format($item['total'], 2) }}" class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" wire:click="removeItem({{ $index }})" class="text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <button type="button" wire:click="addItem" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 text-sm">+ เพิ่มรายการ</button>
                        </div>

                        {{-- ส่วนสรุปและหมายเหตุ --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">หมายเหตุ</label>
                                <textarea wire:model="notes" id="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex flex-col justify-end">
                                <div class="text-right">
                                    <span class="text-lg font-medium text-gray-700">ยอดรวมสุทธิ:</span>
                                    <span class="text-2xl font-bold text-gray-900 ml-2">{{ number_format($total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- ปุ่มบันทึกและยกเลิก --}}
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" wire:navigate class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">ยกเลิก</a>
                            <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                                <span wire:loading.remove wire:target="save">บันทึกการเปลี่ยนแปลง</span>
                                <span wire:loading wire:target="save">กำลังบันทึก...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>