<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- คอมเมนต์: ส่วนหัวของหน้า --}}
            {{ __('ระบบบันทึกรายรับ-รายจ่าย') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- คอมเมนต์: เรียกใช้ Livewire component ที่เราสร้างขึ้นเพื่อแสดงรายการ --}}
            <livewire:finance.transaction-list />
        </div>
    </div>
</x-app-layout>