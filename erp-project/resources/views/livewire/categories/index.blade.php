<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('การจัดการหมวดหมู่') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div class="w-1/3">
                            {{-- wire:model.live.debounce.300ms จะส่ง request หากหยุดพิมพ์ 300ms --}}
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="ค้นหาหมวดหมู่..."
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                         <div class="flex space-x-2">
                            <a href="{{ route('products.trash') }}" wire:navigate class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">ถังขยะ</a>
                            <button wire:click="openModal" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                เพิ่มหมวดหมู่ใหม่
                            </button>
                        </div>
                    </div>

                    {{-- แสดง Flash Message เมื่อสร้างข้อมูลสำเร็จ --}}
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 border border-green-200 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">รหัส</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">ชื่อ</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">วันที่สร้าง</th>
                                    <th class="px-4 py-2 text-right">การกระทำ</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @forelse ($categories as $category)
                                    <tr>
                                        {{-- แสดงลำดับที่ โดยคำนวณจากหน้า Pagination ปัจจุบัน --}}
                                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ ($categories->firstItem() + $loop->index) }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $category->name }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-right">
                                            {{-- ปุ่มแก้ไขจะเรียกใช้เมธอด edit() พร้อมส่ง ID ของ category --}}
                                            <button wire:click="edit({{ $category->id }})" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">แก้ไข</button>
                                            {{-- ปุ่มลบจะเรียกใช้เมธอด confirmDelete() --}}
                                            <button wire:click="confirmDelete({{ $category->id }})" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">ลบ</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-500 py-4">ไม่พบข้อมูลหมวดหมู่</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $categories->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal สำหรับเพิ่ม Category ใหม่ --}}
    {{-- จะแสดงผลก็ต่อเมื่อ property $showModal เป็น true --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                {{-- เปลี่ยนหัวข้อ Modal ตามสถานะ (สร้างใหม่ หรือ แก้ไข) --}}
                <h3 class="text-xl font-bold mb-4">{{ $editing ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่ใหม่' }}</h3>

                {{-- ฟอร์มจะเรียกใช้เมธอด save() เมื่อถูก submit --}}
                <form wire:submit="save">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">ชื่อหมวดหมู่</label>
                        {{-- wire:model.live จะอัปเดตค่าทันทีที่พิมพ์ ทำให้ validation ทำงานแบบ real-time --}}
                        <input wire:model.live="name" type="text" id="name"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        {{-- ปุ่ม Cancel จะเรียกใช้เมธอด closeModal() --}}
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                            ยกเลิก
                        </button>

                        {{-- ปุ่ม Save จะทำการ submit ฟอร์ม --}}
                        <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            {{-- เปลี่ยนข้อความในปุ่มตามสถานะ --}}
                            {{ $editing ? 'บันทึกการเปลี่ยนแปลง' : 'บันทึก' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal สำหรับยืนยันการลบ --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                <h3 class="text-xl font-bold mb-4">ยืนยันการลบ</h3>
                <p>คุณแน่ใจหรือไม่ว่าต้องการลบหมวดหมู่ "<strong>{{ $deleting?->name }}</strong>"? การกระทำนี้ไม่สามารถย้อนกลับได้</p>

                <div class="mt-6 flex justify-end space-x-4">
                    {{-- ปุ่มยกเลิกจะซ่อน Modal โดยการตั้งค่า property $showDeleteModal เป็น false --}}
                    <button type="button" wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        ยกเลิก
                    </button>

                    {{-- ปุ่มยืนยันจะเรียกใช้เมธอด delete() --}}
                    <button type="button" wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        ยืนยันการลบ
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>