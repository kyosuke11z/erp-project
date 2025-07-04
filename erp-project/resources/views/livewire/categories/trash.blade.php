<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ถังขยะหมวดหมู่') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('categories.index') }}" wire:navigate class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            &larr; กลับไปหน้าหลัก
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 border border-green-200 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">ชื่อ</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">วันที่ลบ</th>
                                <th class="px-4 py-2 text-right">การกระทำ</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $category->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $category->deleted_at->format('d/m/Y H:i') }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-right">
                                        <button wire:click="restore({{ $category->id }})" class="inline-block rounded bg-green-600 px-4 py-2 text-xs font-medium text-white hover:bg-green-700">กู้คืน</button>
                                        <button wire:click="forceDelete({{ $category->id }})" wire:confirm="คุณแน่ใจหรือไม่? การลบถาวรจะไม่สามารถกู้คืนข้อมูลได้อีก" class="inline-block rounded bg-red-800 px-4 py-2 text-xs font-medium text-white hover:bg-red-900">ลบถาวร</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-500 py-4">ถังขยะว่างเปล่า</td>
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
</div>

