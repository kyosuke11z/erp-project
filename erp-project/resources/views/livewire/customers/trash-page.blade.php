<div>
    {{-- ส่วนเนื้อหาหลัก --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- ปุ่มกลับไปหน้ารายชื่อหลัก --}}
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('customers.index') }}" wire:navigate class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            &larr; กลับไปหน้ารายชื่อลูกค้า
                        </a>
                    </div>

                    {{-- ส่วนแสดงข้อความ Flash Message --}}
                    @if (session()->has('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 border border-green-200 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- ตารางแสดงข้อมูลที่ถูกลบ --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">ชื่อ-นามสกุล</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">อีเมล</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">วันที่ลบ</th>
                                    <th class="px-4 py-2 text-right">การกระทำ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($customers as $customer)
                                    <tr wire:key="trashed-customer-{{ $customer->id }}">
                                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $customer->name }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $customer->email }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $customer->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-right">
                                            <button wire:click="restore({{ $customer->id }})" class="inline-block rounded bg-green-600 px-4 py-2 text-xs font-medium text-white hover:bg-green-700">กู้คืน</button>
                                            <button wire:click="forceDelete({{ $customer->id }})" wire:confirm="คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้อย่างถาวร? การกระทำนี้ไม่สามารถย้อนกลับได้" class="inline-block rounded bg-black px-4 py-2 text-xs font-medium text-white hover:bg-gray-800">ลบถาวร</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-500 py-4">ไม่พบข้อมูลในถังขยะ</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- ส่วนแสดง Pagination --}}
                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>