<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('รายการคำสั่งขาย') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <div class="w-1/3">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="ค้นหาจากเลขที่ออเดอร์ หรือ ชื่อลูกค้า..."
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <a href="{{ route('sales.create') }}" wire:navigate class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                สร้างคำสั่งขายใหม่
                            </a>
                        </div>
                    </div>

                    @if (session()->has('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 border border-green-200 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-4 px-4 py-2 bg-red-100 text-red-800 border border-red-200 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="px-4 py-2 font-medium text-gray-900">เลขที่ออเดอร์</th>
                                    <th class="px-4 py-2 font-medium text-gray-900">ลูกค้า</th>
                                    <th class="px-4 py-2 font-medium text-gray-900">วันที่สั่งซื้อ</th>
                                    <th class="px-4 py-2 font-medium text-gray-900">ยอดรวม</th>
                                    <th class="px-4 py-2 font-medium text-gray-900">สถานะ</th>
                                    <th class="px-4 py-2 font-medium text-gray-900 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($salesOrders as $order)
                                    <tr wire:key="order-{{ $order->id }}">
                                        <td class="px-4 py-2 font-medium text-gray-900">#{{ $order->id }}</td>
                                        <td class="px-4 py-2 text-gray-700">{{ $order->customer->name }}</td>
                                        <td class="px-4 py-2 text-gray-700">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-gray-700">{{ number_format($order->total_amount, 2) }}</td>
                                        <td class="px-4 py-2 text-gray-700">
                                            <span class="px-2 py-1 font-semibold leading-tight rounded-full
                                                @switch($order->status)
                                                    @case('pending') bg-yellow-200 text-yellow-800 @break
                                                    @case('processing') bg-blue-200 text-blue-800 @break
                                                    @case('completed') bg-green-200 text-green-800 @break
                                                    @case('cancelled') bg-red-200 text-red-800 @break
                                                @endswitch">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-right">
                                            <div class="flex justify-end items-center space-x-4">
                                                <a href="{{ route('sales.show', $order->id) }}" wire:navigate
                                                   class="font-medium text-indigo-600 hover:text-indigo-900">ดูรายละเอียด</a>

                                                @if(!in_array($order->status, ['completed', 'cancelled']))
                                                    <a href="{{ route('sales.edit', $order->id) }}" wire:navigate
                                                       class="font-medium text-yellow-600 hover:text-yellow-900">แก้ไข</a>

                                                    <button wire:click="cancelOrder({{ $order->id }})" wire:confirm="คุณแน่ใจหรือไม่ที่จะยกเลิกออเดอร์นี้? สต็อกสินค้าจะถูกคืนเข้าระบบ"
                                                            class="font-medium text-red-600 hover:text-red-900">ยกเลิก</button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-gray-500 py-4">ไม่พบข้อมูลคำสั่งขาย</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $salesOrders->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

