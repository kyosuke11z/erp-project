<div>
    {{-- คอมเมนต์: ส่วนหัวของหน้า --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            รายการใบคืนสินค้า
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                {{-- คอมเมนต์: ส่วนของฟอร์มค้นหาและปุ่ม Action --}}
                <div class="flex flex-col items-start justify-between gap-4 mb-4 md:flex-row md:items-center">
                    <x-text-input wire:model.live.debounce.300ms="search" type="text" placeholder="ค้นหาเลขที่เอกสาร..." class="w-full md:w-1/3" />
                    <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center self-end px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 md:self-auto">
                        กลับไปหน้าใบสั่งซื้อ
                    </a>
                </div>

                {{-- คอมเมนต์: ส่วนของตารางแสดงผล --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">เลขที่ใบคืน</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">วันที่คืน</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">อ้างอิง GR</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ผู้บันทึก</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">สถานะ</th>
                                <th class="relative px-6 py-3"><span class="sr-only">ดู</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($returns as $return)
                                <tr wire:key="return-{{ $return->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-indigo-600">{{ $return->return_number }}</div></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') }}</div></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('goods-receipt.show', $return->goodsReceipt) }}" class="text-sm text-indigo-600 hover:underline">{{ $return->goodsReceipt->receipt_number }}</a></td>
                                    {{-- คอมเมนต์: ใช้ Nullsafe operator (?->) เพื่อป้องกัน error --}}
                                    <td class="px-6 py-4 whitespace-nowrap"><div class="text-sm text-gray-500">{{ $return->createdBy?->name ?? 'N/A' }}</div></td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                            {{ $return->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                        <a href="{{ route('supplier-returns.show', $return) }}" class="text-indigo-600 hover:text-indigo-900">ดูรายละเอียด</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">ไม่พบข้อมูล</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- คอมเมนต์: ส่วนของการแบ่งหน้า --}}
                <div class="mt-4">
                    {{ $returns->links() }}
                </div>
            </div>
        </div>
    </div>
</div>