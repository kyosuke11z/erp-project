{{-- คอมเมนต์: ใช้ DaisyUI Modal, จะแสดงเมื่อ $showModal เป็น true --}}
<dialog class="modal {{ $showModal ? 'modal-open' : '' }}">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">
            {{ $transactionId ? 'แก้ไขรายการ' : 'เพิ่มรายการใหม่' }}
        </h3>

        <form wire:submit="save">
            <div class="space-y-4">
                {{-- ประเภท --}}
                {{-- คอมเมนต์: ใช้ fieldset และ legend เพื่อการจัดกลุ่ม radio ที่ถูกต้องตามหลัก Accessibility --}}
                <fieldset class="form-control">
                    <legend class="label"><span class="label-text">ประเภท</span></legend>
                    <div class="join">
                        <input wire:model.live="type" class="join-item btn" type="radio" name="type" value="expense" aria-label="รายจ่าย"/>
                        <input wire:model.live="type" class="join-item btn" type="radio" name="type" value="income" aria-label="รายรับ"/>
                    </div>
                </fieldset>

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
            </div>

            {{-- ปุ่ม Actions --}}
            <div class="modal-action">
                <button type="button" wire:click="$set('showModal', false)" class="btn">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">
                    <span wire:loading.remove wire:target="save">บันทึก</span>
                    <span wire:loading wire:target="save" class="loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </form>
    </div>
</dialog>
