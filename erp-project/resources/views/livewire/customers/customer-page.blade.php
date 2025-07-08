<div>
    {{-- ส่วนเนื้อหาหลักของหน้า --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- ส่วนหัว, ปุ่ม และช่องค้นหา --}}
                    <div class="flex justify-between items-center mb-6">
                        <div class="w-1/3">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="ค้นหาจากชื่อ หรือ อีเมล..."
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('customers.trash') }}" wire:navigate class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">ถังขยะ</a>
                            <button wire:click="create" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                เพิ่มลูกค้าใหม่
                            </button>
                        </div>
                    </div>

                    {{-- ส่วนแสดงข้อความ Flash Message --}}
                    @if (session()->has('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 border border-green-200 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- ตารางแสดงข้อมูลลูกค้า --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">ชื่อ-นามสกุล</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">อีเมล</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">เบอร์โทรศัพท์</th>
                                    <th class="px-4 py-2 text-right">การกระทำ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($customers as $customer)
                                    <tr wire:key="customer-{{ $customer->id }}">
                                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $customer->name }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $customer->email }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $customer->phone ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-4 py-2 text-right">
                                            <button wire:click="edit({{ $customer->id }})" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">แก้ไข</button>
                                            <button wire:click="delete({{ $customer->id }})" wire:confirm="คุณแน่ใจหรือไม่ที่จะลบลูกค้ารายนี้?" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">ลบ</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-500 py-4">ไม่พบข้อมูลลูกค้า</td>
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

    {{-- Modal สำหรับเพิ่ม/แก้ไขข้อมูล --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg" @click.away="$wire.closeModal()">
                <h3 class="text-xl font-bold mb-4">{{ $customerId ? 'แก้ไขข้อมูลลูกค้า' : 'เพิ่มลูกค้าใหม่' }}</h3>

                <form wire:submit="save">
                    <div class="space-y-4">
                        {{-- Form fields --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">ชื่อ-นามสกุล</label>
                            <input type="text" wire:model="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
                            <input type="email" wire:model="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">เบอร์โทรศัพท์</label>
                            <input type="text" wire:model="phone" id="phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">ที่อยู่</label>
                            <textarea wire:model="address" id="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- ปุ่มใน Modal --}}
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">ยกเลิก</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>