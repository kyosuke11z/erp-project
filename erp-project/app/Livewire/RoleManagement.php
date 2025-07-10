<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;

class RoleManagement extends Component
{
    use WithPagination;

    public bool $isModalOpen = false;
    public ?int $roleId = null;
    public string $name = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3|unique:roles,name,' . $this->roleId,
        ];
    }

    public function render()
    {
        return view('livewire.role-management', [
            'roles' => Role::paginate(10)
        ])->layout('layouts.app');
    }

    public function create(): void
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal(): void
    {
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields(): void
    {
        $this->name = '';
        $this->roleId = null;
        $this->resetErrorBag();
    }

    public function store(): void
    {
        $this->validate();

        Role::updateOrCreate(['id' => $this->roleId], [
            'name' => $this->name,
        ]);

        session()->flash('success', $this->roleId ? 'อัปเดต Role เรียบร้อยแล้ว' : 'สร้าง Role ใหม่เรียบร้อยแล้ว');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit(int $id): void
    {
        $role = Role::findOrFail($id);
        $this->roleId = $id;
        $this->name = $role->name;
        $this->openModal();
    }

    public function delete(int $id): void
    {
        // อาจเพิ่มการตรวจสอบว่า Role นี้มีผู้ใช้งานอยู่หรือไม่ก่อนทำการลบ
        Role::find($id)->delete();
        session()->flash('success', 'ลบ Role เรียบร้อยแล้ว');
    }
}
