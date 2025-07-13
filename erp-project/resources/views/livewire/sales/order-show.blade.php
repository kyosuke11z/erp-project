   <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <a href="{{ route('sales.index') }}" wire:navigate class="flex items-center space-x-2 text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    <span class="hidden sm:inline font-semibold">ย้อนกลับ</span>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        รายละเอียดใบสั่งขาย #{{ $salesOrder->order_number }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        สร้างเมื่อ {{ $salesOrder->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0 space-x-2">
                @if ($salesOrder->status === 'pending')
                    <a href="{{ route('sales.edit', $salesOrder) }}" wire:navigate class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        แก้ไข
                    </a>
                    {{-- คอมเมนต์: เปลี่ยนปุ่มเป็นลิงก์ไปยังหน้ายืนยันการชำระเงิน --}}
                    <a href="{{ route('sales.payment.create', $salesOrder) }}" wire:navigate
                        class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        บันทึกการชำระเงิน
                    </a>
                    <button
                        wire:click="cancelOrder"
                        wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการยกเลิกใบสั่งขายนี้?"
                        type="button"
                        class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                        ยกเลิก
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- คอมเมนต์: เพิ่มส่วนแสดงข้อความ Error --}}
            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
                <div class="lg:col-span-7">
                    <!-- Order items -->
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">รายการสินค้า</h3>
                            <div class="mt-4 flow-root">
                                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">สินค้า</th>
                                                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">จำนวน</th>
                                                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">ราคา/หน่วย</th>
                                                    <th scope="col" class="py-3.5 pl-3 pr-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">ยอดรวม</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach ($salesOrder->items as $item)
                                                    <tr>
                                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ $item->product->name }}</td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">{{ $item->quantity }}</td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right text-gray-500">฿{{ number_format($item->price, 2) }}</td>
                                                        <td class="whitespace-nowrap py-4 pl-3 pr-4 text-sm text-right font-medium text-gray-900 sm:pr-0">฿{{ number_format($item->subtotal, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th scope="row" colspan="3" class="hidden pl-4 pr-3 pt-4 text-right text-sm font-semibold text-gray-900 sm:table-cell sm:pl-0">ยอดรวมทั้งสิ้น</th>
                                                    <th scope="row" class="pl-6 pr-3 pt-4 text-left text-sm font-semibold text-gray-900 sm:hidden">ยอดรวมทั้งสิ้น</th>
                                                    <td class="pl-3 pr-4 pt-4 text-right text-sm font-semibold text-gray-900 sm:pr-0">฿{{ number_format($salesOrder->total_amount, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-8 bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">หมายเหตุ</h3>
                            <p class="mt-2 text-sm text-gray-500">{{ $salesOrder->notes ?: 'ไม่มีหมายเหตุ' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order summary -->
                <div class="mt-8 lg:col-span-5 lg:mt-0">
                    <div class="bg-white shadow-sm sm:rounded-lg mb-5">
                        <div class="p-6">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">สรุปข้อมูล</h3>
                            <dl class="mt-4 space-y-4 text-sm">
                                <div class="flex items-start">
                                    <dt class="w-1/3 font-medium text-gray-900">ลูกค้า</dt>
                                    <dd class="w-2/3 text-gray-700">{{ $salesOrder->customer->name }}</dd>
                                </div>
                                <div class="flex items-start">
                                    <dt class="w-1/3 font-medium text-gray-900">วันที่</dt>
                                    <dd class="w-2/3 text-gray-700">{{ $salesOrder->order_date->format('d F Y') }}</dd>
                                </div>
                                <div class="flex items-start">
                                    <dt class="w-1/3 font-medium text-gray-900">สถานะ</dt>
                                    <dd class="w-2/3">
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset
                                            @switch($salesOrder->status)
                                                @case('pending') bg-yellow-50 text-yellow-800 ring-yellow-600/20 @break
                                                @case('paid') bg-green-50 text-green-700 ring-green-600/20 @break
                                                @case('cancelled') bg-red-50 text-red-700 ring-red-600/10 @break
                                                @default bg-gray-50 text-gray-600 ring-gray-500/10
                                            @endswitch
                                        ">
                                            {{ ucfirst($salesOrder->status) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">ประวัติและความคิดเห็น</h3>
                            <div class="mt-6">
                                <form wire:submit="addComment">
                                    <div>
                                        <label for="comment" class="sr-only">เพิ่มความคิดเห็น</label>
                                        <textarea wire:model="newComment" id="comment" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="เพิ่มความคิดเห็น..."></textarea>
                                        @error('newComment') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-3 flex justify-end">
                                        <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                            ส่ง
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="mt-8 flow-root">
                                <ul role="list" class="-mb-8">
                                    @forelse ($salesOrder->comments->sortByDesc('created_at') as $comment)
                                        <li>
                                            <div class="relative pb-8">
                                                @if (!$loop->last)
                                                    <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                                            <span class="text-sm font-medium text-white">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                                                        </span>
                                                    </div>
                                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                        <div>
                                                            <p class="text-sm text-gray-500">
                                                                <span class="font-medium text-gray-900">{{ $comment->user->name }}:</span>
                                                                {{ $comment->body }}
                                                            </p>
                                                        </div>
                                                        <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                            <time datetime="{{ $comment->created_at->toIso8601String() }}">{{ $comment->created_at->diffForHumans() }}</time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li>
                                            <p class="text-sm text-center text-gray-500">ยังไม่มีความคิดเห็น</p>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>