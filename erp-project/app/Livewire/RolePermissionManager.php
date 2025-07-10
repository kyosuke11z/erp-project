<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionManager extends Component
{
    public Role $role;

    public $allPermissions;

    public $assignedPermissions = [];

    /**
     * Mount the component and initialize the data.
     *
     * @param Role $role The role to manage permissions for.
     */
    public function mount(Role $role)
    {
        $this->role = $role;
        $this->allPermissions = Permission::all()->pluck('name');
        $this->assignedPermissions = $this->role->permissions()->pluck('name')->toArray();
    }

    /**
     * Save the updated permissions for the role.
     */
    public function savePermissions()
    {
        // Validate that the permissions exist
        $validatedPermissions = Permission::whereIn('name', $this->assignedPermissions)->get();

        // Sync the permissions with the role
        $this->role->syncPermissions($validatedPermissions);

        // Flash a success message
        session()->flash('success', 'อัปเดตสิทธิ์เรียบร้อยแล้ว');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.role-permission-manager')
            ->layout('layouts.app'); // Assuming you have a main layout file
    }
}
