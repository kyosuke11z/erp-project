<div>
    {{-- คอมเมนต์: ส่วนหัวของหน้า --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            รายละเอียดใบคืนสินค้า: {{ $supplierReturn->return_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                {{-- คอมเมนต์: ส่วนข้อมูลหลัก --}}
                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">เลขที่ใบคืน</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $supplierReturn->return_number }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">วันที่คืน</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ \Carbon\Carbon::parse($supplierReturn->return_date)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">สถานะ</h3>
                        <p class="mt-1 text-lg text-gray-900">
                            <span class="inline-flex px-3 py-1 text-base font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                {{ $supplierReturn->status }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">ซัพพลายเออร์</h3>
                        <p class="mt-1 text-gray-900">{{ $supplierReturn->goodsReceipt->purchaseOrder->supplier->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">อ้างอิงใบรับของ (GR)</h3>
                        <a href="{{ route('goods-receipt.show', $supplierReturn->goodsReceipt) }}" class="mt-1 text-indigo-600 hover:underline">
                            {{ $supplierReturn->goodsReceipt->receipt_number }}
                        </a>
                    </div>
                     <div>
                        <h3 class="text-sm font-medium text-gray-500">ผู้บันทึก</h3>
                        {{-- คอมเมนต์: ใช้ Nullsafe operator (?->) เพื่อป้องกัน error --}}
                        <p class="mt-1 text-gray-900">{{ $supplierReturn->createdBy?->name ?? 'N/A' }}</p>
                    </div>
                    {{-- คอมเมนต์: ใช้ @if เพื่อตรวจสอบก่อนว่ามีข้อมูลหรือไม่ ก่อนที่จะแสดงผลส่วนนี้ --}}
                    @if($supplierReturn->reason)
                        <div class="md:col-span-3">
                            <h3 class="text-sm font-medium text-gray-500">เหตุผลการคืน</h3>
                            <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $supplierReturn->reason }}</p>
                        </div>
                    @endif
                </div>

                {{-- คอมเมนต์: ส่วนรายการสินค้า --}}
                <h3 class="mt-8 mb-4 text-lg font-semibold">รายการสินค้าที่คืน</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">SKU</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ชื่อสินค้า</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">จำนวนที่คืน</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($supplierReturn->items as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $item->product->sku }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $item->product->name }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-right text-gray-900 whitespace-nowrap">{{ number_format($item->quantity) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- คอมเมนต์: ปุ่มย้อนกลับ --}}
                <div class="flex justify-end mt-6">
                    <a href="{{ route('supplier-returns.index') }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        กลับไปหน้ารายการ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>