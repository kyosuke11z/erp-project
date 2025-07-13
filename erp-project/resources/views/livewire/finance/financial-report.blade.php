<div class="py-12" x-data="{
    startDate: @entangle('startDate'),
    endDate: @entangle('endDate'),
    pdfLoading: false
}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <!-- ส่วนหัวของหน้า -->
                <h2 class="text-2xl font-semibold leading-tight mb-4">
                    รายงานสรุปรายรับ-รายจ่าย
                </h2>

                <!-- ฟอร์มสำหรับกรองข้อมูล -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700">วันที่เริ่มต้น</label>
                            <input wire:model.live="startDate" type="date" id="startDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700">วันที่สิ้นสุด</label>
                            <input wire:model.live="endDate" type="date" id="endDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <!-- ส่วนของปุ่ม Export -->
                <div class="flex justify-end items-center mb-6 gap-2">
                    <button wire:click="exportExcel" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <span wire:loading.remove wire:target="exportExcel">Export to Excel</span>
                        <span wire:loading wire:target="exportExcel">กำลังสร้าง...</span>
                    </button>

                  <a :href="`{{ route('finance.report.pdf') }}?startDate=${startDate}&endDate=${endDate}`"
                       target="_blank"
                       x-on:click="pdfLoading = true; setTimeout(() => pdfLoading = false, 3000)"
                       :class="{ 'opacity-25 cursor-not-allowed': pdfLoading }"
                       class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <span x-show="!pdfLoading">Export to PDF</span>
                        <span x-show="pdfLoading">กำลังสร้าง...</span>
                    </a>
                </div>

                <!-- การ์ดสรุปยอด -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- รายรับรวม -->
                    <div class="bg-green-100 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-green-800">รายรับรวม</h3>
                        <p class="text-3xl font-bold text-green-600 mt-2">
                            {{ number_format($totalIncome, 2) }}
                        </p>
                    </div>
                    <!-- รายจ่ายรวม -->
                    <div class="bg-red-100 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-red-800">รายจ่ายรวม</h3>
                        <p class="text-3xl font-bold text-red-600 mt-2">
                            {{ number_format($totalExpense, 2) }}
                        </p>
                    </div>
                    <!-- ยอดคงเหลือ -->
                    <div class="bg-blue-100 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-blue-800">ยอดคงเหลือสุทธิ</h3>
                        <p class="text-3xl font-bold text-blue-600 mt-2">
                            {{ number_format($netBalance, 2) }}
                        </p>
                    </div>
                </div>

                <!-- ตารางแสดงรายการ -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รายการ</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ประเภท</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">จำนวนเงิน</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($transaction->type == 'income')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                รายรับ
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                รายจ่าย
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($transaction->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">ไม่พบข้อมูลธุรกรรมในช่วงวันที่ที่เลือก</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- ส่วนของการแบ่งหน้า -->
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>