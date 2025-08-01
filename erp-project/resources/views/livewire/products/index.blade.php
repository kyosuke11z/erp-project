<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('การจัดการสินค้า') }}
        </h2>
    </x-slot>

    <div class="py-12">
        {{-- คอมเมนต์: เปลี่ยนจาก max-w-7xl เป็น max-w-screen-2xl เพื่อให้กล่องแสดงผลกว้างขึ้น รองรับตารางที่มีหลายคอลัมน์ --}}
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div class="w-1/3">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="ค้นหาสินค้าด้วยชื่อหรือ SKU..."
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('products.trash') }}" wire:navigate class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">ถังขยะ</a>
                            {{-- คอมเมนต์: เปลี่ยนจาก wire:click เป็นการลิงก์ไปยัง Route สำหรับสร้างสินค้า --}}
                            <a href="{{ route('products.create') }}" wire:navigate class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                เพิ่มสินค้าใหม่
                            </a>
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
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">รูปภาพ</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">SKU</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">ชื่อสินค้า</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">รายละเอียด</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">หมวดหมู่</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">ราคาขาย</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">ราคาทุน</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">จำนวนคงคลัง</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">จุดสั่งซื้อขั้นต่ำ</th>
                                <th class="px-4 py-2 text-right">การกระทำ</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-4 py-2">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded-md">
                                        @else
                                            <div class="h-12 w-12 bg-gray-200 rounded-md flex items-center justify-center">
                                                <span class="text-xs text-gray-500">ไม่มีรูป</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $product->sku }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $product->name }}</td>
                                    {{-- คอมเมนต์: ปรับปรุงการแสดงผลของคอลัมน์รายละเอียด ให้ตัดข้อความและแสดง ... เมื่อยาวเกินไป และแสดงข้อความเต็มเมื่อเอาเมาส์ไปชี้ --}}
                                    <td class="px-4 py-2 text-gray-700 max-w-xs truncate" title="{{ $product->description }}">{{ $product->description ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $product->category->name ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ number_format($product->selling_price, 2) }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ number_format($product->purchase_price ?? 0, 2) }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $product->quantity }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $product->min_stock_level }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-right">
                                        {{-- คอมเมนต์: เปลี่ยนจาก wire:click เป็นการลิงก์ไปยัง Route สำหรับแก้ไข --}}
                                        {{-- คอมเมนต์: แก้ไขการส่งพารามิเตอร์ให้ตรงกับ Route Model Binding (ส่งทั้ง object หรือ key ที่ชื่อ 'product') --}}
                                        <a href="{{ route('products.edit', ['product' => $product->id]) }}" wire:navigate class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">แก้ไข</a>
                                        <button wire:click="confirmDelete({{ $product->id }})" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">ลบ</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-gray-500 py-4">ไม่พบข้อมูลสินค้า</td>
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

    {{-- คอมเมนต์: ลบการเรียกใช้ฟอร์มแบบ Modal ออกไป เนื่องจากเราเปลี่ยนไปใช้หน้า UpsertPage แยกต่างหากแล้ว --}}
    {{-- <livewire:products.product-form /> --}}

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