<div>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" wire:navigate class="flex items-center space-x-2 text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span class="hidden sm:inline font-semibold">ย้อนกลับ</span>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    ยืนยันการจ่ายเงินสำหรับใบสั่งซื้อ #{{ $purchaseOrder->id }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form wire:submit="savePayment">
                    <div class="p-6 space-y-4">
                        <p class="text-gray-700">
                            โปรดตรวจสอบรายละเอียดและกดยืนยันเพื่อบันทึกการจ่ายเงินสำหรับใบสั่งซื้อนี้
                        </p>

                        <dl class="space-y-2 text-sm border-t border-b py-4">
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600">ผู้ขาย:</dt>
                                <dd class="text-gray-900">{{ $purchaseOrder->supplier->name }}</dd>
                            </div>
                            <div class="flex justify-between items-center pt-2 mt-2">
                                <dt class="text-base font-semibold text-gray-900">ยอดชำระทั้งสิ้น:</dt>
                                <dd class="text-xl font-semibold text-red-600">฿{{ number_format($purchaseOrder->total_amount, 2) }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-4">
                        <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" wire:navigate class="inline-flex items-center rounded-md bg-red-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-700">ยกเลิก</a>
                        <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                            <span wire:loading.remove wire:target="savePayment">ยืนยันการจ่ายเงิน</span>
                            <span wire:loading wire:target="savePayment" class="loading loading-spinner loading-sm"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>