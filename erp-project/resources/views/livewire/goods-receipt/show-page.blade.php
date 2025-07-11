<div>
    {{-- คอมเมนต์: ส่วนหัวของหน้า --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            รายละเอียดใบรับของ: {{ $goodsReceipt->receipt_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">

                {{-- คอมเมนต์: ส่วนแสดงข้อความแจ้งเตือน (Success Message) --}}
                @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        <span class="font-medium">สำเร็จ!</span> {{ session('success') }}
                    </div>
                @endif

                {{-- คอมเมนต์: ส่วนข้อมูลหลักของใบรับของ --}}
                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">เลขที่ใบรับของ (GR)</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $goodsReceipt->receipt_number }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">วันที่รับของ</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $goodsReceipt->receipt_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">ซัพพลายเออร์</h3>
                        <p class="mt-1 text-gray-900">{{ $goodsReceipt->purchaseOrder->supplier->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">อ้างอิงใบสั่งซื้อ (PO)</h3>
                        <a href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}" class="mt-1 text-indigo-600 hover:underline">{{ $goodsReceipt->purchaseOrder->po_number }}</a>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">ผู้บันทึก</h3>
                        <p class="mt-1 text-gray-900">{{ $goodsReceipt->createdBy->name }}</p>
                    </div>
                </div>

                {{-- คอมเมนต์: ส่วนรายการสินค้าที่รับ --}}
                <h3 class="mt-8 mb-4 text-lg font-semibold">รายการสินค้าที่รับ</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">SKU</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ชื่อสินค้า</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">จำนวนที่รับ</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($goodsReceipt->items as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $item->product->sku }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $item->product->name }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-right text-gray-900 whitespace-nowrap">{{ number_format($item->quantity_received) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- คอมเมนต์: ปุ่มดำเนินการ --}}
                <div class="flex items-center justify-end mt-6 space-x-4">
                    <a href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">กลับไปที่ใบสั่งซื้อ</a>
                    <a href="{{ route('supplier-returns.create', $goodsReceipt) }}" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700">สร้างใบคืนสินค้า</a>
                </div>
            </div>
        </div>
    </div>

    {{-- คอมเมนต์: ส่วนแสดงประวัติการคืนสินค้าที่เกี่ยวข้อง --}}
    @if ($goodsReceipt->supplierReturns->isNotEmpty())
    <div class="pb-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h3 class="px-6 text-lg font-semibold leading-6 text-gray-900 lg:px-8">
                ประวัติการคืนสินค้า
            </h3>
            <div class="mt-4 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">เลขที่ใบคืน</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">วันที่คืน</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">จำนวนรายการ</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ผู้บันทึก</th>
                                <th class="relative px-6 py-3"><span class="sr-only">ดู</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($goodsReceipt->supplierReturns as $return)
                                <tr wire:key="sreturn-{{ $return->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('supplier-returns.show', $return) }}" class="text-sm font-medium text-indigo-600 hover:underline">{{ $return->return_number }}</a></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') }}</div></td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-500 whitespace-nowrap">{{ $return->items->count() }}</td>
                                    {{-- คอมเมนต์: ใช้ Nullsafe operator (?->) เพื่อป้องกัน error กรณีไม่มีข้อมูลผู้บันทึก --}}
                                    <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-500">{{ $return->createdBy?->name ?? 'N/A' }}</div></td>
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap"><a href="{{ route('supplier-returns.show', $return) }}" class="text-indigo-600 hover:text-indigo-900">ดูรายละเอียด</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
