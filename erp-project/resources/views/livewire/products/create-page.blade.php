<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('สร้างสินค้าใหม่') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- SKU -->
                            <div class="md:col-span-1">
                                <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                                <input type="text" wire:model="sku" id="sku" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Product Name -->
                            <div class="md:col-span-1">
                                <label for="name" class="block text-sm font-medium text-gray-700">ชื่อสินค้า</label>
                                <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Category -->
                            <div class="md:col-span-2">
                                <label for="category_id" class="block text-sm font-medium text-gray-700">หมวดหมู่</label>
                                <select wire:model="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- เลือกหมวดหมู่ --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">รายละเอียด</label>
                                <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Selling Price -->
                            <div class="md:col-span-1">
                                <label for="selling_price" class="block text-sm font-medium text-gray-700">ราคาขาย</label>
                                <input type="number" step="0.01" wire:model="selling_price" id="selling_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('selling_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Purchase Price -->
                            <div class="md:col-span-1">
                                <label for="purchase_price" class="block text-sm font-medium text-gray-700">ราคาทุน</label>
                                <input type="number" step="0.01" wire:model="purchase_price" id="purchase_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('purchase_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="md:col-span-1">
                                <label for="quantity" class="block text-sm font-medium text-gray-700">จำนวนคงคลัง</label>
                                <input type="number" wire:model="quantity" id="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Min Stock Level -->
                            <div class="md:col-span-1">
                                <label for="min_stock_level" class="block text-sm font-medium text-gray-700">จุดสั่งซื้อขั้นต่ำ</label>
                                <input type="number" wire:model="min_stock_level" id="min_stock_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('min_stock_level') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('products.index') }}" wire:navigate class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">ยกเลิก</a>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                <span wire:loading.remove wire:target="save">บันทึกสินค้า</span>
                                <span wire:loading wire:target="save">กำลังบันทึก...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>