<?php

namespace App\Livewire\Superadmin;

use App\Models\RoleName;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Manage Role Names')]
class ManageRoleNames extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';

    #[Rule('required|string|max:100|unique:role_names,name')]
    public string $newRoleName = '';

    public ?RoleName $editingRole = null; // Role being edited
    #[Rule('required|string|max:100')]
    public string $editingRoleNameValue = '';


    public function addRoleName()
    {
        $validated = $this->validateOnly('newRoleName');
        RoleName::create(['name' => $validated['newRoleName']]);
        $this->reset('newRoleName');
        session()->flash('message', 'Role Name added successfully.');
    }

    public function startEditing(RoleName $role)
    {
        $this->editingRole = $role;
        $this->editingRoleNameValue = $role->name;
         // Reset validation state for editing field
        $this->resetValidation('editingRoleNameValue');
    }

    public function cancelEditing()
    {
         $this->reset('editingRole', 'editingRoleNameValue');
         $this->resetValidation(); // Reset all validation
    }

    public function updateRoleName()
    {
        if (!$this->editingRole) return;

        // Add unique check, ignoring the current role being edited
        $this->validate([
            'editingRoleNameValue' => 'required|string|max:100|unique:role_names,name,' . $this->editingRole->id
        ]);

        $this->editingRole->update(['name' => $this->editingRoleNameValue]);
        $this->cancelEditing(); // Close editing form
        session()->flash('message', 'Role Name updated successfully.');
    }


    public function deleteRoleName(RoleName $role)
    {
        // Optional: Check if role is in use before deleting
        // if ($role->users()->exists() || $role->invitations()->exists()) { ... }
        $role->delete();
        session()->flash('message', 'Role Name deleted successfully.');
         // If the deleted role was being edited, cancel editing
        if ($this->editingRole && $this->editingRole->id === $role->id) {
             $this->cancelEditing();
        }
    }

    public function render()
    {
        $roles = RoleName::orderBy('name')->paginate(10);
        return view('livewire.superadmin.manage-role-names', [
            'roles' => $roles,
        ]);
    }
}