<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('เพิ่มรายการทางการเงิน') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form wire:submit="save">
                        <div class="space-y-6">
                            {{-- ประเภท --}}
                            <div class="form-control">
                                <label class="label"><span class="label-text">ประเภท</span></label>
                                {{-- คอมเมนต์: เปลี่ยนมาใช้ Radio Button แบบมาตรฐานเพื่อแก้ปัญหาการเลือกค่าไม่ได้ --}}
                                <div class="flex items-center space-x-6">
                                    <div class="form-control">
                                        <label class="label cursor-pointer space-x-2">
                                            <span class="label-text">รายจ่าย</span>
                                            <input type="radio" wire:model.live="type" name="type" value="expense" class="radio radio-primary" />
                                        </label>
                                    </div>
                                    <div class="form-control">
                                        <label class="label cursor-pointer space-x-2">
                                            <span class="label-text">รายรับ</span>
                                            <input type="radio" wire:model.live="type" name="type" value="income" class="radio radio-primary" />
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- วันที่ --}}
                            <div class="form-control">
                                <label class="label" for="transaction_date"><span class="label-text">วันที่</span></label>
                                <input wire:model="transaction_date" id="transaction_date" type="date" class="input input-bordered w-full">
                                @error('transaction_date') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- จำนวนเงิน --}}
                            <div class="form-control">
                                <label class="label" for="amount"><span class="label-text">จำนวนเงิน</span></label>
                                <input wire:model="amount" id="amount" type="number" step="0.01" placeholder="0.00" class="input input-bordered w-full">
                                @error('amount') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- หมวดหมู่ --}}
                            <div class="form-control">
                                <label class="label" for="finance_category_id"><span class="label-text">หมวดหมู่</span></label>
                                <select wire:model="finance_category_id" id="finance_category_id" class="select select-bordered w-full">
                                    <option value="">-- เลือกหมวดหมู่ --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('finance_category_id') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- คำอธิบาย --}}
                            <div class="form-control">
                                <label class="label" for="description"><span class="label-text">คำอธิบาย</span></label>
                                <textarea wire:model="description" id="description" class="textarea textarea-bordered w-full" rows="3"></textarea>
                                @error('description') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- ปุ่ม Actions --}}
                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('finance.index') }}" wire:navigate class="btn">ยกเลิก</a>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove wire:target="save">บันทึก</span>
                                    <span wire:loading wire:target="save" class="loading loading-spinner loading-sm"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>