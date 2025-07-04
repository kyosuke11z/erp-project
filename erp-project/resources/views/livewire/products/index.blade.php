<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('การจัดการสินค้า') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div class="w-1/3">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="ค้นหาสินค้าด้วยชื่อหรือ SKU..."
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('products.trash') }}" wire:navigate class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">ถังขยะ</a>
                            <button wire:click="openModal" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                เพิ่มสินค้าใหม่
                            </button>
                        </div>
                    </div>

                    {{-- แสดง Flash Message --}}
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 border border-green-200 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">SKU</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">ชื่อสินค้า</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">หมวดหมู่</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">ราคาขาย</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">จำนวนคงคลัง</th>
                                <th class="px-4 py-2 text-right">การกระทำ</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $product->sku }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $product->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $product->category->name ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ number_format($product->selling_price, 2) }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $product->quantity }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-right">
                                        <button wire:click="edit({{ $product->id }})" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">แก้ไข</button>
                                        <button wire:click="confirmDelete({{ $product->id }})" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">ลบ</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-gray-500 py-4">ไม่พบข้อมูลสินค้า</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal สำหรับเพิ่ม/แก้ไขสินค้า --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl">
                <h3 class="text-xl font-bold mb-4">{{ $editing ? 'แก้ไขสินค้า' : 'เพิ่มสินค้าใหม่' }}</h3>

                <form wire:submit="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- SKU --}}
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                            <input wire:model="sku" type="text" id="sku" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('sku') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Product Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">ชื่อสินค้า</label>
                            <input wire:model="name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Category --}}
                        <div class="md:col-span-2">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">หมวดหมู่</label>
                            <select wire:model="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- เลือกหมวดหมู่ --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Selling Price --}}
                        <div>
                            <label for="selling_price" class="block text-sm font-medium text-gray-700">ราคาขาย</label>
                            <input wire:model="selling_price" type="number" step="0.01" id="selling_price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('selling_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Quantity --}}
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700">จำนวนคงคลัง</label>
                            <input wire:model="quantity" type="number" id="quantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">รายละเอียดสินค้า</label>
                            <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">ยกเลิก</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">{{ $editing ? 'บันทึกการเปลี่ยนแปลง' : 'บันทึก' }}</button>
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
                <p>คุณแน่ใจหรือไม่ว่าต้องการลบสินค้า "<strong>{{ $deleting?->name }}</strong>"?</p>

                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" wire:click="$set('showDeleteModal', false)" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">ยกเลิก</button>
                    <button type="button" wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">ยืนยันการลบ</button>
                </div>
            </div>
        </div>
    @endif
</div>
