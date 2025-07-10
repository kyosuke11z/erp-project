<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('รายละเอียดใบรับสินค้า') }}: {{ $goodsReceipt->receipt_number }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('goods-receipt.pdf', $goodsReceipt) }}" target="_blank" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    พิมพ์
                </a>
                {{-- Add Return Button --}}
            <a href="{{ route('supplier-returns.create', $goodsReceipt) }}" class="inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                คืนสินค้า
            </a>
                <a href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                    &larr; {{ __('กลับไปที่ใบสั่งซื้อ') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                {{-- Header Section --}}
                <div class="pb-4 mb-4 border-b">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                        ข้อมูลใบรับสินค้า
                    </h3>
                    <p class="max-w-2xl mt-1 text-sm text-gray-500">
                        อ้างอิงใบสั่งซื้อ:
                        <a href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                            {{ $goodsReceipt->purchaseOrder->po_number }}
                        </a>
                    </p>
                </div>

                {{-- Receipt Details --}}
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">เลขที่ใบรับสินค้า</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $goodsReceipt->receipt_number }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">วันที่รับ</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $goodsReceipt->receipt_date }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">ซัพพลายเออร์</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $goodsReceipt->purchaseOrder->supplier->name }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">ผู้บันทึก</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $goodsReceipt->createdBy->name ?? 'N/A' }}</dd>
                    </div>
                    @if($goodsReceipt->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">หมายเหตุ</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $goodsReceipt->notes }}</dd>
                    </div>
                    @endif
                </div>

                {{-- Items Table --}}
                <div class="mt-8">
                    <h4 class="text-md font-medium text-gray-700">รายการสินค้าที่ได้รับ</h4>
                    <div class="flex flex-col mt-2">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">สินค้า</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">จำนวนที่ได้รับ</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($goodsReceipt->items as $item)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ $item->product->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $item->product->sku }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm font-medium text-right text-gray-900 whitespace-nowrap">{{ $item->quantity_received }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>