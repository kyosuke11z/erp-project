<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.app')]
class UserManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Properties for creating/editing a user
    public $userId;
    public $name;
    public $email;
    public $password;
    public $userRoles = [];

    public $isModalOpen = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'password' => $this->userId ? 'nullable|min:8' : 'required|min:8',
            'userRoles' => 'required|array|min:1',
        ];
    }

    public function openModal()
    {
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInput()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->userRoles = [];
    }

    public function store()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $userData);

        $user->syncRoles($this->userRoles);

        session()->flash('success', $this->userId ? 'อัปเดตข้อมูลผู้ใช้เรียบร้อยแล้ว' : 'สร้างผู้ใช้ใหม่เรียบร้อยแล้ว');

        $this->closeModal();
        $this->resetInput();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = ''; // Do not show password
        $this->userRoles = $user->getRoleNames()->toArray();

        $this->openModal();
    }

    public function delete($id)
    {
        // Prevent deleting the currently logged-in user
        if ($id == auth()->id()) {
            session()->flash('error', 'ไม่สามารถลบผู้ใช้ที่กำลังล็อกอินอยู่ได้');
            return;
        }
        User::find($id)->delete();
        session()->flash('success', 'ลบผู้ใช้เรียบร้อยแล้ว');
    }

    public function render()
    {
        return view('livewire.users.user-management', [
            'users' => User::with('roles')->paginate(10),
            'roles' => Role::all(),
        ]);
    }
}