<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                {{-- ปุ่มยกเลิกจะพาผู้ใช้กลับไปหน้ารายละเอียด --}}
                <a href="{{ route('sales.show', $salesOrder) }}" wire:navigate class="flex items-center space-x-2 text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                    <span class="hidden sm:inline font-semibold">ยกเลิกการแก้ไข</span>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        แก้ไขใบสั่งขาย #{{ $salesOrder->order_number }}
                    </h2>
                </div>
            </div>
            <div class="flex-shrink-0">
                {{-- ปุ่มบันทึกจะอยู่นอกฟอร์ม แต่เชื่อมกันด้วย form id --}}
                <button type="submit" form="edit-sales-order-form" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form wire:submit="save" id="edit-sales-order-form">
                <div class="lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
                    {{-- คอลัมน์ซ้าย: รายการสินค้า --}}
                    <div class="lg:col-span-7">
                        <div class="bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">รายการสินค้า</h3>
                                <div class="mt-4 flow-root">
                                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                            <table class="min-w-full divide-y divide-gray-300">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0" style="width: 50%;">สินค้า</th>
                                                        <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900" style="width: 15%;">จำนวน</th>
                                                        <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900" style="width: 25%;">ราคา/หน่วย</th>
                                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0" style="width: 10%;"><span class="sr-only">Remove</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    @foreach ($orderItems as $index => $item)
                                                        <tr>
                                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                                                <select wire:model.live="orderItems.{{ $index }}.product_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                                    <option value="">เลือกสินค้า</option>
                                                                    @foreach ($allProducts as $product)
                                                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('orderItems.'.$index.'.product_id') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                                                            </td>
                                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                                <input type="number" wire:model.live="orderItems.{{ $index }}.quantity" min="1" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 text-center">
                                                                @error('orderItems.'.$index.'.quantity') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                                                            </td>
                                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">฿{{ number_format($item['price'], 2) }}</td>
                                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-600 hover:text-red-900">ลบ</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @error('orderItems') <div class="mt-2 text-sm text-red-600">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="button" wire:click="addItem" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                        + เพิ่มรายการ
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- คอลัมน์ขวา: ข้อมูลสรุป --}}
                    <div class="mt-8 lg:col-span-5 lg:mt-0">
                        <div class="bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">ข้อมูลใบสั่งขาย</h3>
                                <div class="mt-6 space-y-6">
                                    {{-- Customer --}}
                                    <div>
                                        <label for="customer_id" class="block text-sm font-medium leading-6 text-gray-900">ลูกค้า</label>
                                        <div class="mt-2">
                                            <select wire:model="customer_id" id="customer_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">-- เลือกลูกค้า --</option>
                                                @foreach($allCustomers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('customer_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Order Date --}}
                                    <div>
                                        <label for="order_date" class="block text-sm font-medium leading-6 text-gray-900">วันที่สั่ง</label>
                                        <div class="mt-2">
                                            <input type="date" wire:model="order_date" id="order_date" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                        @error('order_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Notes --}}
                                    <div>
                                        <label for="notes" class="block text-sm font-medium leading-6 text-gray-900">หมายเหตุ</label>
                                        <div class="mt-2">
                                            <textarea wire:model="notes" id="notes" rows="4" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                        </div>
                                        @error('notes') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Total Amount --}}
                                    <div class="border-t border-gray-200 pt-4">
                                        <dl class="space-y-4">
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <dt>ยอดรวมทั้งสิ้น</dt>
                                                <dd>฿{{ number_format($total_amount, 2) }}</dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

