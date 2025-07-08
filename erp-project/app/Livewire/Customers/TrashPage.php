<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class TrashPage extends Component
{
    use WithPagination;

    /**
     * กู้คืนข้อมูลลูกค้าที่ถูกลบ (Soft Delete)
     */
    public function restore(int $id): void
    {
        // ค้นหาเฉพาะข้อมูลที่อยู่ในถังขยะ และทำการกู้คืน
        Customer::onlyTrashed()->findOrFail($id)->restore();
        session()->flash('success', 'กู้คืนข้อมูลลูกค้าเรียบร้อยแล้ว');
    }

    /**
     * ลบข้อมูลลูกค้าออกจากระบบอย่างถาวร
     */
    public function forceDelete(int $id): void
    {
        // ค้นหาเฉพาะข้อมูลที่อยู่ในถังขยะ และทำการลบถาวร
        Customer::onlyTrashed()->findOrFail($id)->forceDelete();
        session()->flash('success', 'ลบข้อมูลลูกค้าอย่างถาวรเรียบร้อยแล้ว');
    }

    /**
     * Render component และดึงข้อมูลที่ถูกลบมาแสดง
     */
    public function render()
    {
        // ดึงข้อมูลเฉพาะลูกค้าที่ถูก Soft Delete
        $customers = Customer::onlyTrashed()
            ->latest('deleted_at') // เรียงตามวันที่ลบล่าสุด
            ->paginate(10);

         return view('livewire.customers.trash-page', ['customers' => $customers])->layout('layouts.app');
    }
}