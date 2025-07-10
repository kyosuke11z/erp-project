<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('รายละเอียดใบสั่งซื้อ') }}: {{ $purchaseOrder->po_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">

                {{-- Success/Error Messages --}}
                @if (session()->has('success'))
                    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Header Section with Actions --}}
                <div class="flex items-center justify-between pb-4 mb-4 border-b">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            ข้อมูลใบสั่งซื้อ
                        </h3>
                        <p class="max-w-2xl mt-1 text-sm text-gray-500">
                            สถานะปัจจุบัน: <span class="font-semibold text-white px-2 py-1 rounded-full text-xs {{ 
                                match($purchaseOrder->status) {
                                    'pending' => 'bg-yellow-500',
                                    'completed' => 'bg-blue-500',
                                    'partially_received' => 'bg-teal-500',
                                    'received' => 'bg-green-500',
                                    'cancelled' => 'bg-red-500',
                                    default => 'bg-gray-500',
                                } 
                            }}">{{ ucfirst($purchaseOrder->status) }}</span>
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        {{-- Create Goods Receipt Button --}}
                        @if(in_array($purchaseOrder->status, ['pending', 'completed', 'partially_received']))
                            <a href="{{ route('goods-receipt.create', $purchaseOrder) }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                รับสินค้า
                            </a>
                        @endif
                        
                        {{-- PDF Button --}}
                        <a href="{{ route('purchase-orders.pdf', $purchaseOrder) }}" target="_blank" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            พิมพ์ PDF
                        </a>
                    </div>
                </div>

                {{-- PO Details --}}
                <div class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">ซัพพลายเออร์</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $purchaseOrder->supplier->name }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">เลขที่ใบสั่งซื้อ</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $purchaseOrder->po_number }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">วันที่สั่งซื้อ</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d/m/Y') }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">วันที่คาดว่าจะได้รับ</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $purchaseOrder->expected_delivery_date ? \Carbon\Carbon::parse($purchaseOrder->expected_delivery_date)->format('d/m/Y') : '-' }}</dd>
                    </div>
                    @if($purchaseOrder->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">หมายเหตุ</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $purchaseOrder->notes }}</dd>
                    </div>
                    @endif
                </div>

                {{-- Items Table --}}
                <div class="mt-8">
                    <h4 class="text-md font-medium text-gray-700">รายการสินค้า</h4>
                    <div class="flex flex-col mt-2">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">สินค้า</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">จำนวน</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">ราคาต่อหน่วย</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">ยอดรวม</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($purchaseOrder->items as $item)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ $item->product->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $item->product->sku }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-right text-gray-500 whitespace-nowrap">{{ $item->quantity }}</td>
                                                    <td class="px-6 py-4 text-sm text-right text-gray-500 whitespace-nowrap">{{ number_format($item->price, 2) }}</td>
                                                    <td class="px-6 py-4 text-sm font-medium text-right text-gray-900 whitespace-nowrap">{{ number_format($item->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-gray-50">
                                            <tr>
                                                <td colspan="3" class="px-6 py-3 text-sm font-medium text-right text-gray-700 uppercase">ยอดรวมทั้งหมด</td>
                                                <td class="px-6 py-3 text-sm font-bold text-right text-gray-900">{{ number_format($purchaseOrder->total_amount, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Goods Receipts History --}}
                @if($purchaseOrder->goodsReceipts->isNotEmpty())
                <div class="mt-8">
                    <h4 class="text-md font-medium text-gray-700">ประวัติการรับสินค้า</h4>
                    <div class="flex flex-col mt-2">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">เลขที่ใบรับของ</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">วันที่รับ</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ผู้บันทึก</th>
                                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">หมายเหตุ</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($purchaseOrder->goodsReceipts as $receipt)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <a href="{{ route('goods-receipt.show', $receipt) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                            {{ $receipt->receipt_number }}
                                                        </a>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d/m/Y') }}</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $receipt->createdBy->name ?? 'N/A' }}</td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $receipt->notes }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>