<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- หัวข้อหน้า --}}
            รายละเอียดคำสั่งขาย #{{ $salesOrder->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- แสดงข้อความ Success/Error --}}
                    @if (session()->has('success'))
                        <div class="mb-4 rounded-md bg-green-100 p-4 text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="mb-4 rounded-md bg-red-100 p-4 text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- ส่วนแสดงข้อมูลหลักของ Order --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <h3 class="font-semibold text-lg">ข้อมูลลูกค้า</h3>
                            <p><strong>ชื่อ:</strong> {{ $salesOrder->customer->name }}</p>
                            <p><strong>อีเมล:</strong> {{ $salesOrder->customer->email }}</p>
                            <p><strong>เบอร์โทร:</strong> {{ $salesOrder->customer->phone }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">ข้อมูลคำสั่งขาย</h3>
                            <p><strong>เลขที่ออเดอร์:</strong> #{{ $salesOrder->id }}</p>
                            <p><strong>วันที่สั่ง:</strong> {{ \Carbon\Carbon::parse($salesOrder->order_date)->format('d/m/Y') }}</p>
                            <p><strong>สถานะ:</strong>
                                <span @class([
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    'bg-green-100 text-green-800' => $salesOrder->status == 'completed',
                                    'bg-yellow-100 text-yellow-800' => $salesOrder->status == 'pending',
                                    'bg-red-100 text-red-800' => $salesOrder->status == 'cancelled',
                                ])>
                                    {{-- แปลง status เป็นภาษาไทย --}}
                                    {{ match($salesOrder->status) {'completed' => 'เสร็จสิ้น', 'pending' => 'รอดำเนินการ', 'cancelled' => 'ยกเลิก'} }}
                                </span>
                            </p>
                        </div>
                        <div class="md:text-right">
                            <h3 class="font-semibold text-lg">ยอดรวม</h3>
                            <p class="text-2xl font-bold">฿{{ number_format($salesOrder->total_amount, 2) }}</p>
                        </div>
                    </div>

                    {{-- ส่วนแสดงรายการสินค้า --}}
                    <div class="mt-6">
                        <h3 class="font-semibold text-lg mb-2">รายการสินค้า</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สินค้า</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ราคาต่อหน่วย</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">จำนวน</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ราคารวม</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($salesOrder->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">฿{{ number_format($item->price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">฿{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ส่วนปุ่ม Actions --}}
                    <div class="mt-8 flex justify-end space-x-3 border-t pt-4">
                        <a href="{{ route('sales.index') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            กลับ
                        </a>
                        {{-- แสดงปุ่มแก้ไขและยกเลิกเฉพาะสถานะ 'รอดำเนินการ' --}}
                        @if ($salesOrder->status == 'pending')
                            <a href="{{ route('sales.edit', $salesOrder) }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                แก้ไข
                            </a>
                            {{-- ปุ่มสำหรับยกเลิกออเดอร์ --}}
                            <x-danger-button
                                type="button"
                                wire:click="cancelOrder"
                                wire:confirm.prompt="คุณแน่ใจหรือไม่ที่จะยกเลิกออเดอร์นี้?|พิมพ์ CANCEL เพื่อยืนยัน|ยืนยันการยกเลิก">
                                ยกเลิกออเดอร์
                            </x-danger-button>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
