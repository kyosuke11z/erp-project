<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('จัดการผู้ใช้และสิทธิ์') }}
            </h2>
            <button wire:click="openModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                สร้างผู้ใช้ใหม่
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session()->has('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">อีเมล</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                            <th class="relative px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @foreach ($user->roles as $role)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900">แก้ไข</button>
                                    <button wire:click="delete({{ $user->id }})" wire:confirm="คุณแน่ใจหรือไม่ที่จะลบผู้ใช้ {{ $user->name }}?" class="text-red-600 hover:text-red-900 ml-4">ลบ</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    @if ($isModalOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="store">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $userId ? 'แก้ไขผู้ใช้' : 'สร้างผู้ใช้ใหม่' }}
                            </h3>
                            <div class="mt-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">ชื่อ</label>
                                    <input type="text" wire:model.defer="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
                                    <input type="email" wire:model.defer="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="mt-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
                                    <input type="password" wire:model.defer="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="{{ $userId ? 'ปล่อยว่างไว้หากไม่ต้องการเปลี่ยน' : '' }}">
                                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700">Roles</label>
                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                        @foreach ($roles as $role)
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" wire:model.defer="userRoles" value="{{ $role->name }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-600">{{ $role->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('userRoles') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                บันทึก
                            </button>
                            <button type="button" wire:click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                ยกเลิก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
```

เรียบร้อยครับ! ตอนนี้เมื่อคุณล็อกอินในฐานะ Admin คุณจะเห็นเมนู "จัดการผู้ใช้" เมื่อคลิกเข้าไป คุณจะสามารถสร้าง, แก้ไข, ลบผู้ใช้ และกำหนด Role ให้กับพวกเขาได้อย่างอิสระผ่านหน้าต่าง Modal ที่สวยงามและใช้งานง่ายครับ

<!--
[PROMPT_SUGGESTION]จะเพิ่มการตรวจสอบสิทธิ์ในเมนูนำทาง (Navigation Menu) ได้อย่างไร เพื่อให้ผู้ใช้เห็นเฉพาะเมนูที่ตนเองมีสิทธิ์[/PROMPT_SUGGESTION]
[PROMPT_SUGGESTION]จะเพิ่มการกำหนด Permission ให้กับ Role โดยตรงจากหน้าเว็บได้อย่างไร[/PROMPT_SUGGESTION]
