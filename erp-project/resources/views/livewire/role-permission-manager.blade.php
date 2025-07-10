 <div>
     <x-slot name="header">
         <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('จัดการสิทธิ์สำหรับ Role') }}: <span class="text-blue-600">{{ $role->name }}</span>
         </h2>
     </x-slot>
     <div class="py-12">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
 
                 @if (session()->has('success'))
                     <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                         <span class="block sm:inline">{{ session('success') }}</span>
                     </div>
                 @endif
 
                 <form wire:submit.prevent="savePermissions">
                     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                         @forelse ($allPermissions as $permission)
                             <div class="flex items-center">
                                 <input type="checkbox"
                                        id="permission-{{ $loop->index }}"
                                        value="{{ $permission }}"
                                        wire:model="assignedPermissions"
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                 <label for="permission-{{ $loop->index }}" class="ml-2 block text-sm text-gray-900">
                                     {{ $permission }}
                                 </label>
                             </div>
                         @empty
                             <p class="text-gray-500 col-span-full">ไม่พบ Permissions</p>
                         @endforelse
                     </div>
 
                     <div class="mt-6">
                         <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                             บันทึกสิทธิ์
                         </button>
                         <a href="{{ route('roles.index') }}" class="ml-4 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-md hover:bg-gray-300 focus:outline-none">
                             กลับ
                         </a>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>