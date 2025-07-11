<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $isEditing ? 'แก้ไขสินค้า' : 'สร้างสินค้าใหม่' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        {{-- SKU --}}
                        <div class="md:col-span-1">
                            <x-input-label for="sku" value="SKU" />
                            <x-text-input wire:model="sku" id="sku" type="text" class="block w-full mt-1" />
                            <x-input-error :messages="$errors->get('sku')" class="mt-2" />
                        </div>

                        {{-- Product Name --}}
                        <div class="md:col-span-2">
                            <x-input-label for="name" value="ชื่อสินค้า" />
                            <x-text-input wire:model="name" id="name" type="text" class="block w-full mt-1" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Category --}}
                        <div class="md:col-span-1">
                            <x-input-label for="category_id" value="หมวดหมู่" />
                            <select wire:model="category_id" id="category_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">เลือกหมวดหมู่</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        {{-- Selling Price --}}
                        <div class="md:col-span-1">
                            <x-input-label for="selling_price" value="ราคาขาย" />
                            <x-text-input wire:model="selling_price" id="selling_price" type="number" step="0.01" class="block w-full mt-1" />
                            <x-input-error :messages="$errors->get('selling_price')" class="mt-2" />
                        </div>

                        {{-- Quantity --}}
                        <div class="md:col-span-1">
                            <x-input-label for="quantity" value="จำนวนคงเหลือ" />
                            <x-text-input wire:model="quantity" id="quantity" type="number" class="block w-full mt-1" />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        {{-- คอมเมนต์: เพิ่มฟิลด์สำหรับ Min Stock Level --}}
                        <div class="md:col-span-1">
                            <x-input-label for="min_stock_level" value="จุดสั่งซื้อขั้นต่ำ (Min. Stock)" />
                            <x-text-input wire:model="min_stock_level" id="min_stock_level" type="number" class="block w-full mt-1" />
                            <x-input-error :messages="$errors->get('min_stock_level')" class="mt-2" />
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <x-input-label for="description" value="คำอธิบาย" />
                            <textarea wire:model="description" id="description" rows="4" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50" wire:navigate>
                            ยกเลิก
                        </a>
                        <x-primary-button class="ms-4">
                            บันทึกข้อมูล
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>