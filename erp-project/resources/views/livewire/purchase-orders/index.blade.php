<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('ใบสั่งซื้อ') }}
            </h2>
            <a href="{{ route('purchase-orders.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('สร้างใบสั่งซื้อ') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">

                {{-- Success Messages --}}
                @if (session()->has('success'))
                    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Filters Section --}}
                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-3">
                    <div class="md:col-span-2">
                        <x-input-label for="search" value="ค้นหา" class="sr-only" />
                        <x-text-input wire:model.live.debounce.300ms="search" id="search" class="block w-full" type="text" placeholder="ค้นหาตามเลขที่ PO หรือชื่อซัพพลายเออร์..." />
                    </div>
                    <div>
                        <x-input-label for="status" value="สถานะ" class="sr-only" />
                        <select wire:model.live="statusFilter" id="status" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">สถานะทั้งหมด</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="partially_received">Partially Received</option>
                            <option value="received">Received</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

                {{-- Table --}}
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">เลขที่ PO</th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ซัพพลายเออร์</th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">วันที่สั่ง</th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">ยอดรวม</th>
                                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase">สถานะ</th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($purchaseOrders as $po)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $po->po_number }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $po->supplier->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($po->order_date)->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-right text-gray-500 whitespace-nowrap">
                                                    {{ number_format($po->total_amount, 2) }}
                                                </td>
                                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full {{ 
                                                        match($po->status) {
                                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                                            'completed' => 'bg-blue-100 text-blue-800',
                                                            'partially_received' => 'bg-teal-100 text-teal-800',
                                                            'received' => 'bg-green-100 text-green-800',
                                                            'cancelled' => 'bg-red-100 text-red-800',
                                                            default => 'bg-gray-100 text-gray-800',
                                                        } 
                                                    }}">
                                                        {{ ucfirst($po->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                    {{-- Actions Dropdown --}}
                                                    <div x-data="{ open: false }" @click.outside="open = false" class="relative inline-block text-left">
                                                        <div>
                                                            <button @click="open = !open" type="button" class="flex items-center text-gray-400 rounded-full hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="menu-button" aria-expanded="true" aria-haspopup="true">
                                                                <span class="sr-only">Open options</span>
                                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                                </svg>
                                                            </button>
                                                        </div>

                                                        <div x-show="open"
                                                            x-transition:enter="transition ease-out duration-100"
                                                            x-transition:enter-start="transform opacity-0 scale-95"
                                                            x-transition:enter-end="transform opacity-100 scale-100"
                                                            x-transition:leave="transition ease-in duration-75"
                                                            x-transition:leave-start="transform opacity-100 scale-100"
                                                            x-transition:leave-end="transform opacity-0 scale-95"
                                                            class="absolute right-0 z-10 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                            role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" style="display: none;">
                                                            <div class="py-1" role="none">
                                                                <a href="{{ route('purchase-orders.show', $po) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">ดูรายละเอียด</a>
                                                                <a href="{{ route('purchase-orders.edit', $po) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">แก้ไข</a>
                                                                <a href="{{ route('purchase-orders.pdf', $po) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">พิมพ์ PDF</a>
                                                                <button wire:click="delete({{ $po->id }})" wire:confirm="คุณแน่ใจหรือไม่ที่จะลบใบสั่งซื้อ #{{ $po->po_number }}?" type="button" class="block w-full px-4 py-2 text-sm text-left text-red-700 hover:bg-gray-100" role="menuitem" tabindex="-1">ลบ</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 whitespace-nowrap">
                                                    ไม่พบข้อมูลใบสั่งซื้อ
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $purchaseOrders->links() }}
                </div>

            </div>
        </div>
    </div>
</div>