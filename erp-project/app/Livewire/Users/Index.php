<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;
use Illuminate\Validation\Rules;

class Index extends Component
{
    use WithPagination;

    // คอมเมนต์: ตัวแปรสำหรับควบคุม Modal และฟอร์ม
    public bool $showModal = false;
    public ?int $userId = null;

    // คอมเมนต์: ตัวแปรสำหรับผูกกับข้อมูลในฟอร์ม (Data Binding)
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $selectedRole = '';

    // คอมเมนต์: ตัวแปรสำหรับค้นหา
    public string $search = '';

    // คอมเมนต์: Listener สำหรับรับ event การลบจาก SweetAlert
    protected $listeners = ['deleteConfirmed' => 'destroy'];

    // คอมเมนต์: กฎการตรวจสอบความถูกต้องของข้อมูล
    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->userId],
            // คอมเมนต์: ถ้าเป็นการแก้ไข (มี userId) password ไม่จำเป็นต้องกรอก
            'password' => [$this->userId ? 'nullable' : 'required', 'confirmed', Rules\Password::defaults()],
            'selectedRole' => ['required', 'exists:roles,name'],
        ];
    }

    // คอมเมนต์: ฟังก์ชันสำหรับเปิด Modal เพื่อสร้างผู้ใช้ใหม่
    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    // คอมเมนต์: ฟังก์ชันสำหรับเปิด Modal เพื่อแก้ไขข้อมูลผู้ใช้
    public function edit(User $user)
    {
        $this->resetForm();
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRole = $user->roles->first()->name ?? ''; // ดึง Role แรกที่เจอ
        $this->showModal = true;
    }

    // คอมเมนต์: ฟังก์ชันสำหรับบันทึกข้อมูล (ทั้งสร้างใหม่และแก้ไข)
    public function save()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        // คอมเมนต์: อัปเดตรหัสผ่านเฉพาะเมื่อมีการกรอกข้อมูลใหม่เท่านั้น
        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        // คอมเมนต์: ใช้ updateOrCreate เพื่อความกระชับในการสร้างหรืออัปเดต
        $user = User::updateOrCreate(['id' => $this->userId], $userData);

        // คอมเมนต์: กำหนด Role ให้กับผู้ใช้ (syncRoles จะลบ Role เก่าออกทั้งหมดก่อนกำหนดใหม่)
        $user->syncRoles($this->selectedRole);

        $this->closeModal();
        $this->dispatch('swal:success', [
            'title' => 'สำเร็จ!',
            'text' => 'บันทึกข้อมูลผู้ใช้เรียบร้อยแล้ว',
        ]);
    }

    // คอมเมนต์: ฟังก์ชันสำหรับแสดงกล่องยืนยันการลบ
    public function confirmDelete($userId)
    {
        $this->userId = $userId;
        $this->dispatch('swal:confirm', [
            'title' => 'คุณแน่ใจหรือไม่?',
            'text' => 'การกระทำนี้ไม่สามารถย้อนกลับได้!',
            'confirmButtonText' => 'ใช่, ลบเลย!',
            'method' => 'deleteConfirmed', // คอมเมนต์: Event ที่จะถูกยิงเมื่อยืนยัน
        ]);
    }

    // คอมเมนต์: ฟังก์ชันสำหรับลบผู้ใช้ (ถูกเรียกโดย event 'deleteConfirmed')
    public function destroy()
    {
        // คอมเมนต์: ป้องกันการลบผู้ใช้ที่กำลังล็อกอินอยู่ เพื่อความปลอดภัย
        if ($this->userId == auth()->id()) {
            $this->dispatch('swal:error', [
                'title' => 'ผิดพลาด!',
                'text' => 'คุณไม่สามารถลบข้อมูลของตัวเองได้',
            ]);
            return;
        }

        $user = User::find($this->userId);
        if ($user) {
            $user->delete();
            $this->dispatch('swal:success', [
                'title' => 'สำเร็จ!',
                'text' => 'ลบข้อมูลผู้ใช้เรียบร้อยแล้ว',
            ]);
        }
    }

    // คอมเมนต์: ฟังก์ชันสำหรับปิด Modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // คอมเมนต์: ฟังก์ชันสำหรับรีเซ็ตค่าในฟอร์มและ error messages
    private function resetForm()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'password_confirmation', 'selectedRole']);
        $this->resetErrorBag();
    }

    // คอมเมนต์: ฟังก์ชัน render สำหรับแสดงผล Component
    public function render()
    {
        // คอมเมนต์: ดึงข้อมูลผู้ใช้พร้อมกับ Role และมีการค้นหา
        $users = User::with('roles')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);

        // คอมเมนต์: ดึง Role ทั้งหมดเพื่อใช้ใน Dropdown (ยกเว้น Admin เพื่อไม่ให้สร้าง Admin เพิ่มได้)
        $roles = Role::where('name', '!=', 'Admin')->pluck('name', 'name');

        return view('livewire.users.index', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.app');
    }
}