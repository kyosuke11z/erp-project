<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">

        {{-- คอมเมนต์: เพิ่มส่วนแสดงข้อความแจ้งเตือน --}}
        @if (session('success'))
            <div role="alert" class="alert alert-success mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">รายการเคลื่อนไหว</h2>
            <a href="{{ route('finance.create') }}" wire:navigate class="btn btn-primary">เพิ่มรายการใหม่</a>
        </div>

        {{-- คอมเมนต์: ส่วนของตัวกรองข้อมูล --}}
        <div class="mb-6 p-4 bg-gray-100 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="form-control">
                    <label for="search" class="label"><span class="label-text">ค้นหา</span></label>
                    <input wire:model.live.debounce.300ms="search" id="search" type="text" placeholder="ค้นหาตามคำอธิบาย..." class="input input-bordered w-full">
                </div>
                <div class="form-control">
                    <label for="filterType" class="label"><span class="label-text">ประเภท</span></label>
                    <select wire:model.live="filterType" id="filterType" class="select select-bordered w-full">
                        <option value="">ทุกประเภท</option>
                        <option value="income">รายรับ</option>
                        <option value="expense">รายจ่าย</option>
                    </select>
                </div>
                <div class="form-control">
                    <label for="startDate" class="label"><span class="label-text">วันที่เริ่มต้น</span></label>
                    <input wire:model.live="startDate" id="startDate" type="date" class="input input-bordered w-full">
                </div>
                <div class="form-control">
                    <label for="endDate" class="label"><span class="label-text">วันที่สิ้นสุด</span></label>
                    <input wire:model.live="endDate" id="endDate" type="date" class="input input-bordered w-full">
                </div>
            </div>
        </div>

        {{-- คอมเมนต์: ส่วนของตารางแสดงผล --}}
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>วันที่</th>
                        <th>คำอธิบาย</th>
                        <th>หมวดหมู่</th>
                        <th>ประเภท</th>
                        <th class="text-right">จำนวนเงิน (บาท)</th>
                        <th>ผู้บันทึก</th>
                        <th></th> {{-- สำหรับปุ่ม Action --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="hover">
                            <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->thaidate('j M Y') }}</td>
                            <td>
                                {{ $transaction->description }}
                                {{-- คอมเมนต์: แสดงข้อมูลอ้างอิงถ้ามี --}}
                                @if($transaction->related_model_id)
                                    <span class="text-xs text-gray-500 block">
                                        (อ้างอิง: {{ class_basename($transaction->related_model_type) }} #{{ $transaction->related_model_id }})
                                    </span>
                                @endif
                            </td>
                            <td>{{ $transaction->category->name }}</td>
                            <td>
                                @if ($transaction->type === 'income')
                                    <span class="badge badge-success text-white">รายรับ</span>
                                @else
                                    <span class="badge badge-error text-white">รายจ่าย</span>
                                @endif
                            </td>
                            <td class="text-right font-mono @if($transaction->type === 'income') text-success @else text-error @endif">
                                {{ number_format($transaction->amount, 2) }}
                            </td>
                            <td>{{ $transaction->user->name }}</td>
                            <td>
                                <div class="flex justify-end space-x-2">
                                    {{-- คอมเมนต์: เปลี่ยนปุ่มแก้ไขเป็นลิงก์ไปยังหน้า edit --}}
                                    <a href="{{ route('finance.edit', $transaction) }}" wire:navigate class="btn btn-xs btn-outline btn-info">
                                        แก้ไข
                                    </a>
                                    {{-- พื้นที่สำหรับปุ่มลบในอนาคต --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">ไม่พบข้อมูลรายการ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- คอมเมนต์: ส่วนของการแบ่งหน้า --}}
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>

    </div>
</div>
