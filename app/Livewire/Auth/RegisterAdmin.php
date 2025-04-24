<?php

namespace App\Livewire\Auth;

use App\Models\StaffInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL; // Needed for verifying signed URL
use Illuminate\Auth\Events\Registered;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon; // For checking expiration

#[Layout('components.layouts.guest')] // Use guest layout
#[Title('Admin Registration')]
class RegisterAdmin extends Component
{
    // Passed from route
    public string $token = '';

    // Loaded from valid invitation
    public ?StaffInvitation $invitation = null;
    public string $email = ''; // Pre-fill email
    public string $assignedRoleName = '';

    // Form fields
    #[Rule('required|string|max:255')]
    public string $firstName = '';
    #[Rule('required|string|max:255')]
    public string $lastName = '';
    #[Rule('required|string|max:255|unique:users,username')]
    public string $username = '';
    #[Rule('required|string|min:8|confirmed')]
    public string $password = '';
    public string $password_confirmation = '';

    // State flags
    public bool $isValidInvitation = false;
    public string $errorMessage = '';

    /**
     * Validate the invitation token when component mounts.
     */
    public function mount(string $token)
    {
        $this->token = $token;

        // Verify the signed URL signature first (basic check)
        if (! URL::hasValidSignature(request())) {
             $this->errorMessage = 'Invalid or expired invitation link.';
             return;
         }

        // Find the invitation by token
        $this->invitation = StaffInvitation::where('token', $this->token)->first();

        // Further checks
        if (!$this->invitation) {
            $this->errorMessage = 'Invitation not found.';
        } elseif ($this->invitation->registered_at !== null) {
            $this->errorMessage = 'This invitation has already been used.';
        } elseif ($this->invitation->expires_at->isPast()) {
            $this->errorMessage = 'This invitation has expired.';
        } else {
            // Invitation is valid
            $this->isValidInvitation = true;
            $this->email = $this->invitation->email; // Pre-fill email
            $this->assignedRoleName = $this->invitation->role_name; // Get role name
        }
    }

    /**
     * Register the invited admin user.
     */
    public function registerAdmin()
    {
        // Ensure invitation is still valid before proceeding
        if (!$this->isValidInvitation || !$this->invitation) {
             session()->flash('error', 'Cannot register: Invalid or expired invitation.');
             return redirect()->route('login'); // Redirect if something went wrong
        }

        // Validate form input
        $validated = $this->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'username' => $validated['username'],
            'email' => $this->invitation->email, // Use email from invitation
            'password' => Hash::make($validated['password']),
            'role' => 'admin', // Assign 'admin' role
            'role_name' => $this->invitation->role_name, // Assign descriptive role name
            'email_verified_at' => now(), // Mark email as verified
        ]);

        // Mark invitation as used
        $this->invitation->update(['registered_at' => now()]);

        event(new Registered($user)); // Dispatch event
        Auth::login($user); // Log the new admin in
        request()->session()->regenerate(); // Regenerate session

        // Redirect to dashboard
        session()->flash('message', 'Registration successful! Welcome aboard.'); // Add success message
        $this->redirect(route('app.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register-admin');
    }
}