<div>
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

                        {{-- Image Upload --}}
                        <div class="md:col-span-2">
                            <label for="newImage" class="block text-sm font-medium text-gray-700">รูปภาพสินค้า</label>
                            <input wire:model="newImage" type="file" id="newImage" class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                            ">
                            @error('newImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                            <div wire:loading wire:target="newImage" class="mt-2 text-sm text-gray-500">กำลังอัปโหลด...</div>

                            {{-- Image Preview --}}
                            <div class="mt-4">
                                @if ($newImage instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                    {{-- แสดงรูปภาพใหม่ที่อัปโหลด --}}
                                    <span class="block text-sm font-medium text-gray-700 mb-2">ตัวอย่างรูปภาพใหม่:</span>
                                    <img src="{{ $newImage->temporaryUrl() }}" class="h-24 w-24 object-cover rounded-md">
                               @elseif (isset($existingImage) && $existingImage)
                                    {{-- แสดงรูปภาพที่มีอยู่แล้ว --}}
                                    <span class="block text-sm font-medium text-gray-700 mb-2">รูปภาพปัจจุบัน:</span>
                                    <img src="{{ asset('storage/' . $existingImage) }}" class="h-24 w-24 object-cover rounded-md">
                                @endif
                            </div>
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
</div>

