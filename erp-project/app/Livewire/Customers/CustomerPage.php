<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class CustomerPage extends Component
{
    use WithPagination;

    // คุณสมบัติสำหรับ Modal และฟอร์ม
    public bool $showModal = false; // สถานะการแสดง Modal
    public ?int $customerId = null;

    // คุณสมบัติสำหรับผูกกับข้อมูลในฟอร์ม (ใช้ Rule สำหรับ Validation)
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|max:255')]
    public string $email = '';

    #[Rule('nullable|string|max:20')]
    public string $phone = '';

    #[Rule('nullable|string')]
    public string $address = '';

    // คุณสมบัติสำหรับการค้นหา
    #[Url(as: 's', keep: true)] // ทำให้การค้นหาคงอยู่ใน URL (s=... )
    public string $search = ''; // ตัวแปรสำหรับเก็บคำค้นหา

    /**
     * เปิด Modal สำหรับสร้างลูกค้าใหม่
     */
    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    /**
     * เปิด Modal และโหลดข้อมูลลูกค้าสำหรับแก้ไข
     */
    public function edit(Customer $customer): void // ใช้ Route Model Binding ของ Livewire
    {
        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->phone = $customer->phone ?? '';
        $this->address = $customer->address ?? ''; // ใช้ ?? '' เพื่อป้องกันค่า null

        $this->showModal = true;
    }

    /**
     * บันทึกข้อมูล (ทั้งสร้างใหม่และแก้ไข)
     */
    public function save(): void
    {
        // สร้าง Rule สำหรับ email uniqueness ตอนแก้ไข
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $this->customerId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        // ถ้ามี customerId คือการแก้ไข, ถ้าไม่มีคือการสร้างใหม่
        Customer::updateOrCreate(
            ['id' => $this->customerId],
            [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ]
        );

        session()->flash('success', 'บันทึกข้อมูลลูกค้าเรียบร้อยแล้ว');

        $this->closeModal();
    }

    /**
     * ลบข้อมูลลูกค้า (Soft Delete)
     */
    public function delete(Customer $customer): void // ใช้ Route Model Binding ของ Livewire
    {
        $customer->delete();
        session()->flash('success', 'ลบข้อมูลลูกค้าเรียบร้อยแล้ว');
    }

    /**
     * ปิด Modal และรีเซ็ตฟอร์ม
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * รีเซ็ตค่าในฟอร์มทั้งหมด
     */
    private function resetForm(): void
    {
        $this->reset(['customerId', 'name', 'email', 'phone', 'address']);
    }

    /**
     * Render component และดึงข้อมูลมาแสดง
     */
    public function render()
    {
        // ปรับปรุง Query ให้สวยงามและมีประสิทธิภาพขึ้นด้วย when()
        $customers = Customer::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        // ส่ง view ไปแสดงผล พร้อมกับระบุ layout (โดยไม่ต้องส่งข้อมูล header)
        return view('livewire.customers.customer-page', [
            'customers' => $customers,
        ])->layout('layouts.app');
    }
}