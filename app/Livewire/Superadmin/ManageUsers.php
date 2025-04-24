<?php

namespace App\Livewire\Superadmin;

use App\Models\RoleName;
use App\Models\StaffInvitation;
use App\Models\User;
use App\Notifications\StaffInvitationNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
// Removed Rule attribute import as it's not used on properties now
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; // <-- Import Laravel's Rule facade

#[Layout('components.layouts.app')]
#[Title('Manage Users')]
class ManageUsers extends Component
{
    use WithPagination;

    // Tabs
    public string $activeTab = 'users'; // Default tab

    // Invite Modal State & Form Fields
    public bool $showInviteModal = false;
    public string $inviteEmail = '';
    public string $inviteRoleName = '';

    // Edit User Modal State & Form Fields
     public bool $showEditUserModal = false;
     public ?User $editingUser = null;
     public string $editFirstName = '';
     public string $editLastName = '';
     public string $editUsername = '';
     public string $editEmail = '';
     public string $editRole = '';
     public string $editRoleName = '';
     // Optional: Add other editable profile fields here if needed

    // Search / Filtering
    public string $searchUsers = '';
    public string $searchStaff = '';
    public string $searchInvited = '';
    public int $perPage = 10;

    // Query string mapping
    protected $queryString = [
        'activeTab' => ['except' => 'users', 'as' => 'tab'],
        'searchUsers' => ['except' => '', 'as' => 's_users'],
        'searchStaff' => ['except' => '', 'as' => 's_staff'],
        'searchInvited' => ['except' => '', 'as' => 's_invited'],
    ];

    // Reset pagination when search/tab changes
    public function updatedSearchUsers() { $this->resetPage('usersPage'); }
    public function updatedSearchStaff() { $this->resetPage('staffPage'); }
    public function updatedSearchInvited() { $this->resetPage('invitedPage'); }

    public function setTab(string $tab) {
        $this->activeTab = $tab;
        match ($tab) {
             'users' => $this->resetPage('usersPage'),
             'staff' => $this->resetPage('staffPage'),
             'invited' => $this->resetPage('invitedPage'),
             default => $this->resetPage(),
         };
    }

    // --- Modal Control ---
    public function openInviteModal() {
        $this->reset(['inviteEmail', 'inviteRoleName']);
        $this->resetErrorBag();
        $this->showInviteModal = true;
    }
    public function closeInviteModal() {
        $this->showInviteModal = false;
        $this->reset(['inviteEmail', 'inviteRoleName']);
    }
    public function openEditUserModal(User $user) {
        $this->resetErrorBag();
        $this->editingUser = $user;
        $this->editFirstName = $user->first_name;
        $this->editLastName = $user->last_name;
        $this->editUsername = $user->username;
        $this->editEmail = $user->email;
        $this->editRole = $user->role;
        $this->editRoleName = $user->role_name ?? '';
        // Add other profile fields if needed
        $this->showEditUserModal = true;
    }
    public function closeEditUserModal() {
        $this->showEditUserModal = false;
        $this->reset(['editingUser', 'editFirstName', 'editLastName', 'editUsername', 'editEmail', 'editRole', 'editRoleName']);
    }

    /**
     * Define validation rules dynamically based on modal context.
     */
    protected function rules(): array
    {
         $rules = [];

         // Rules for Invite Modal
         if ($this->showInviteModal) {
             $rules['inviteEmail'] = ['required', 'email', 'max:255', 'unique:users,email', 'unique:staff_invitations,email,NULL,id,registered_at,NULL']; // Check users and pending invites
             $rules['inviteRoleName'] = ['required', 'string', 'exists:role_names,name'];
         }

         // Rules for Edit User Modal
         if ($this->showEditUserModal && $this->editingUser) {
             $rules['editFirstName'] = ['required', 'string', 'max:255'];
             $rules['editLastName'] = ['required', 'string', 'max:255'];
             $rules['editUsername'] = ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->editingUser->id)];
             $rules['editEmail'] = ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingUser->id)];
             $rules['editRole'] = ['required', 'string', Rule::in(['student', 'admin', 'superadmin'])];
             $rules['editRoleName'] = ['nullable', 'string', Rule::requiredIf($this->editRole === 'admin'), Rule::exists('role_names', 'name')];
             // Add rules for other profile fields if needed
         }

         return $rules;
    }

     /**
     * Define custom attribute names for validation messages.
     */
    protected function validationAttributes(): array
    {
         return [
             'inviteEmail' => 'invite email',
             'inviteRoleName' => 'assigned role name (invite)',
             'editFirstName' => 'first name',
             'editLastName' => 'last name',
             'editUsername' => 'username',
             'editEmail' => 'email',
             'editRole' => 'system role',
             'editRoleName' => 'assigned role name',
         ];
    }

    // --- Actions ---
    public function sendInvitation()
    {
        // Validate only invite fields using rules() method context
        $validated = $this->validate([
            'inviteEmail' => $this->rules()['inviteEmail'],
            'inviteRoleName' => $this->rules()['inviteRoleName'],
        ]);

        // Check if email already belongs to a registered user (redundant due to validation rule, but good practice)
        if (User::where('email', $validated['inviteEmail'])->exists()) {
            $this->addError('inviteEmail', 'This email address is already registered.');
            return;
        }
        // Check pending invitations (redundant due to validation rule)
        // if (StaffInvitation::where('email', $validated['inviteEmail'])->whereNull('registered_at')->exists()) { ... }

        $token = Str::random(64);
        $expiresAt = Carbon::now()->addDays(7);

        $invitation = StaffInvitation::create([
            'email' => $validated['inviteEmail'],
            'role_name' => $validated['inviteRoleName'],
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        try {
             Notification::route('mail', $validated['inviteEmail'])
                        ->notify(new StaffInvitationNotification($invitation));
            session()->flash('message', 'Invitation sent successfully to ' . $validated['inviteEmail']);
            $this->closeInviteModal();
        } catch (\Exception $e) {
            Log::error('Staff Invitation Email Failed: ' . $e->getMessage());
            // $invitation->delete(); // Optionally delete failed invite
            session()->flash('error', 'Could not send invitation email. Please check mail configuration.');
        }
    }

    public function updateUser()
    {
        if (!$this->editingUser) return;

        // Validate only edit fields using rules() method context
        $validated = $this->validate([
            'editFirstName' => $this->rules()['editFirstName'],
            'editLastName' => $this->rules()['editLastName'],
            'editUsername' => $this->rules()['editUsername'],
            'editEmail' => $this->rules()['editEmail'],
            'editRole' => $this->rules()['editRole'],
            'editRoleName' => $this->rules()['editRoleName'],
        ]);

        try {
            $updateData = [
                'first_name' => $validated['editFirstName'],
                'last_name' => $validated['editLastName'],
                'username' => $validated['editUsername'],
                'email' => $validated['editEmail'],
                'role' => $validated['editRole'],
                // Set role_name to null if role is not admin
                'role_name' => ($validated['editRole'] === 'admin') ? $validated['editRoleName'] : null,
            ];

            $this->editingUser->update($updateData);

            session()->flash('message', 'User information updated successfully.');
            $this->closeEditUserModal();
            $this->resetPage($this->activeTab == 'staff' ? 'staffPage' : 'usersPage');

        } catch (\Exception $e) {
             Log::error("Error updating user ID {$this->editingUser->id}: " . $e->getMessage());
             session()->flash('error', 'Could not update user information.');
        }
    }

    public function deleteInvitation(StaffInvitation $invitation) {
        if ($invitation->registered_at === null) {
            $invitation->delete();
            session()->flash('message', 'Invitation for ' . $invitation->email . ' deleted.');
        } else {
             session()->flash('error', 'Cannot delete an already registered invitation.');
        }
    }

     public function deleteUser(User $user)
     {
         if ($user->id === Auth::id()) { /* ... cannot delete self ... */ return; }
         if ($user->role === 'superadmin' && User::where('role', 'superadmin')->count() <= 1) { /* ... cannot delete last superadmin ... */ return; }

         try {
             $username = $user->username; // Get username before deleting
             $user->forceDelete(); // Or $user->delete(); for soft delete
             session()->flash('message', "User account '{$username}' deleted successfully.");
             $this->resetPage($this->activeTab == 'staff' ? 'staffPage' : 'usersPage');
         } catch (\Exception $e) {
             Log::error("Error deleting user ID {$user->id}: " . $e->getMessage());
             session()->flash('error', 'Could not delete user account.');
         }
     }


    public function render()
    {
        $roleNames = RoleName::orderBy('name')->pluck('name')->toArray();

        // All Users Tab Query
        $usersQuery = User::query()->orderBy('last_name')->orderBy('first_name');
        if (!empty($this->searchUsers)) { /* ... user search logic ... */ }
        $users = $usersQuery->paginate($this->perPage, ['*'], 'usersPage');

        // Registered Staff Tab Query
        $staffQuery = User::where('role', 'admin')->orderBy('last_name')->orderBy('first_name');
        if (!empty($this->searchStaff)) { /* ... staff search logic ... */ }
        $staff = $staffQuery->paginate($this->perPage, ['*'], 'staffPage');

        // Pending Invitations Tab Query
        $invitedQuery = StaffInvitation::whereNull('registered_at')->orderBy('created_at', 'desc');
         if (!empty($this->searchInvited)) { /* ... invited search logic ... */ }
        $invitations = $invitedQuery->paginate($this->perPage, ['*'], 'invitedPage');

        return view('livewire.superadmin.manage-users', [ // Point to correct view name
            'roleNames' => $roleNames,
            'users' => $users,
            'staff' => $staff,
            'invitations' => $invitations,
        ]);
    }
}