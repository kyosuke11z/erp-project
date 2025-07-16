<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Page Title & Actions -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">ใบสั่งขาย (Sales Orders)</h2>
                            <p class="mt-1 text-sm text-gray-600">รายการใบสั่งขายทั้งหมดในระบบ</p>
                        </div>
                        <a href="{{ route('sales.create') }}" wire:navigate class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            เพิ่มใบสั่งขาย
                        </a>
                    </div>

                    <!-- Search & Filters -->
                    <div class="mb-4">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="ค้นหาจากเลขที่ออเดอร์ หรือชื่อลูกค้า..." class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    @if (session()->has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Sales Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">เลขที่ออเดอร์</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">ลูกค้า</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">วันที่</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-right">ยอดรวม</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-center">สถานะ</th>
                                    <th class="px-4 py-2 font-medium text-gray-900 text-right">จัดการ</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @forelse ($salesOrders as $order)
                                    <tr>
                                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $order->order_number ?? '#' . $order->id }}</td>{{-- แสดง order_number ถ้ามีค่า, ถ้าไม่มี (เป็นข้อมูลเก่า) ให้แสดง id แทน --}}
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $order->customer->name ?? 'N/A' }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $order->order_date->format('d/m/Y') }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700 text-right">฿{{ number_format($order->total_amount, 2) }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-center">
                                            <span @class([
                                                'inline-flex items-center justify-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                                'bg-yellow-100 text-yellow-800' => $order->status == 'pending',
                                                'bg-green-100 text-green-800' => $order->status == 'completed',
                                                'bg-red-100 text-red-800' => $order->status == 'cancelled',
                                                'bg-gray-100 text-gray-800' => !in_array($order->status, ['pending', 'completed', 'cancelled']),
                                            ])>
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-2 text-right space-x-2">
                                            <a href="{{ route('sales.show', $order) }}" wire:navigate class="inline-block rounded bg-gray-100 px-4 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">ดูรายละเอียด</a>
                                            @if ($order->status === 'pending')
                                                <a href="{{ route('sales.edit', $order) }}" wire:navigate class="inline-block rounded bg-blue-100 px-4 py-2 text-xs font-medium text-blue-700 hover:bg-blue-200">แก้ไข</a>
                                                <button wire:click="delete({{ $order->id }})" wire:confirm="คุณแน่ใจหรือไม่ที่จะลบใบสั่งขาย #{{ $order->id }}? การกระทำนี้ไม่สามารถย้อนกลับได้" class="inline-block rounded bg-red-100 px-4 py-2 text-xs font-medium text-red-700 hover:bg-red-200">
                                                    ลบ
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-gray-500 py-4">ไม่พบข้อมูลใบสั่งขาย</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $salesOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>