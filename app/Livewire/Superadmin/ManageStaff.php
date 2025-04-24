<?php

namespace App\Livewire\Superadmin;

use App\Models\RoleName;
use App\Models\StaffInvitation;
use App\Models\User;
use App\Notifications\StaffInvitationNotification; // We will create this Notification
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon; // Import Carbon

#[Layout('components.layouts.app')]
#[Title('Manage Staff')]
class ManageStaff extends Component
{
    use WithPagination;

    // Tabs
    public string $activeTab = 'staff'; // 'staff' or 'invited'

    // Invite Modal State & Form Fields
    public bool $showInviteModal = false;
    #[Rule('required|email|max:255')]
    public string $inviteEmail = '';
    #[Rule('required|string|exists:role_names,name')] // Ensure role name exists
    public string $inviteRoleName = '';

    // Search / Filtering
    public string $searchStaff = '';
    public string $searchInvited = '';
    public int $perPage = 10;

    protected $queryString = [
        'activeTab' => ['except' => 'staff', 'as' => 'tab'],
        'searchStaff' => ['except' => '', 'as' => 's_staff'],
        'searchInvited' => ['except' => '', 'as' => 's_invited'],
    ];

    // Reset pagination when search/tab changes
    public function updatedSearchStaff() { $this->resetPage('staffPage'); }
    public function updatedSearchInvited() { $this->resetPage('invitedPage'); }
    public function setTab(string $tab) {
        $this->activeTab = $tab;
        $this->resetPage($this->activeTab == 'staff' ? 'staffPage' : 'invitedPage');
    }


    // --- Invite Modal Actions ---
    public function openInviteModal()
    {
        $this->reset(['inviteEmail', 'inviteRoleName']);
        $this->resetErrorBag(); // Clear previous validation errors
        $this->showInviteModal = true;
    }

    public function closeInviteModal()
    {
        $this->showInviteModal = false;
        $this->reset(['inviteEmail', 'inviteRoleName']);
    }

    public function sendInvitation()
    {
        $validated = $this->validate([
            'inviteEmail' => 'required|email|max:255',
            'inviteRoleName' => 'required|string|exists:role_names,name',
        ]);

        // Check if email already belongs to a registered user
        if (User::where('email', $validated['inviteEmail'])->exists()) {
            $this->addError('inviteEmail', 'This email address is already registered.');
            return;
        }

        // Check if there's an existing *pending* invitation for this email
        if (StaffInvitation::where('email', $validated['inviteEmail'])->whereNull('registered_at')->exists()) {
            $this->addError('inviteEmail', 'An invitation has already been sent to this email address.');
            return;
        }

        // Generate unique token and expiry
        $token = Str::random(64);
        $expiresAt = Carbon::now()->addDays(7); // Invitation valid for 7 days

        // Create the invitation record
        $invitation = StaffInvitation::create([
            'email' => $validated['inviteEmail'],
            'role_name' => $validated['inviteRoleName'],
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        // Send Notification (Email) - Requires creating the Notification class
        try {
             // Use Laravel's Notification system
             Notification::route('mail', $validated['inviteEmail'])
                        ->notify(new StaffInvitationNotification($invitation));

            session()->flash('message', 'Invitation sent successfully to ' . $validated['inviteEmail']);
            $this->closeInviteModal();

        } catch (\Exception $e) {
            // Log the error if email sending fails
            \Log::error('Staff Invitation Email Failed: ' . $e->getMessage());
            // Optionally delete the invitation if email fails? Or allow resend?
            // $invitation->delete();
            session()->flash('error', 'Could not send invitation email. Please check mail configuration.');
        }
    }

     // --- Other Actions ---
    public function deleteInvitation(StaffInvitation $invitation)
    {
        if ($invitation->registered_at === null) { // Only delete pending
            $invitation->delete();
            session()->flash('message', 'Invitation for ' . $invitation->email . ' deleted.');
        } else {
             session()->flash('error', 'Cannot delete an already registered invitation.');
        }
    }

    // Optional: Resend Invitation logic would be similar to sendInvitation
    // public function resendInvitation(StaffInvitation $invitation) { ... }

    // Optional: Delete Registered Staff logic
    // public function deleteStaff(User $staff) { ... }


    public function render()
    {
        // Fetch Role Names for the dropdown
        $roleNames = RoleName::orderBy('name')->pluck('name')->toArray();

        // Fetch Registered Staff (Admins) with pagination and search
        $staffQuery = User::where('role', 'admin')->orderBy('last_name')->orderBy('first_name');
        if (!empty($this->searchStaff)) {
             $staffQuery->where(function($q) {
                 $q->where('first_name', 'like', '%' . $this->searchStaff . '%')
                   ->orWhere('last_name', 'like', '%' . $this->searchStaff . '%')
                   ->orWhere('username', 'like', '%' . $this->searchStaff . '%')
                   ->orWhere('email', 'like', '%' . $this->searchStaff . '%')
                   ->orWhere('role_name', 'like', '%' . $this->searchStaff . '%');
             });
        }
        $staff = $staffQuery->paginate($this->perPage, ['*'], 'staffPage'); // Use named page for pagination

        // Fetch Pending Invitations with pagination and search
        $invitedQuery = StaffInvitation::whereNull('registered_at')->orderBy('created_at', 'desc');
         if (!empty($this->searchInvited)) {
             $invitedQuery->where(function($q) {
                  $q->where('email', 'like', '%' . $this->searchInvited . '%')
                    ->orWhere('role_name', 'like', '%' . $this->searchInvited . '%');
             });
        }
        $invitations = $invitedQuery->paginate($this->perPage, ['*'], 'invitedPage'); // Use named page

        return view('livewire.superadmin.manage-staff', [
            'roleNames' => $roleNames,
            'staff' => $staff,
            'invitations' => $invitations,
        ]);
    }
}