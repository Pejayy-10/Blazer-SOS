<?php

namespace App\Livewire\Auth;

// Keep existing imports
use Illuminate\Support\Facades\Auth; // We will use this now
use Illuminate\Validation\ValidationException; // For throwing login errors
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Login')]
class Login extends Component
{
    #[Rule('required|string')]
    public string $username = ''; // Input can be username or email

    #[Rule('required|string')]
    public string $password = '';

    #[Rule('boolean')] // No longer nullable, just true/false
    public bool $remember = false;

    /**
     * Attempt to authenticate the user using Laravel's Auth system.
     */
    public function authenticate(): void
    {
        // Validate the form input first
        $this->validate();

        // Determine if the login input is an email or username
        $fieldType = filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Prepare credentials for authentication attempt
        $credentials = [
            $fieldType => $this->username,
            'password' => $this->password,
        ];

        // Attempt to authenticate the user
        if (!Auth::attempt($credentials, $this->remember)) {
            // If authentication fails, throw a validation exception
            // This will automatically add the error message to the 'username' field
            // using the standard 'auth.failed' translation key.
            throw ValidationException::withMessages([
                // You can use 'email' or 'username' here, 'username' might be more generic
                'username' => __('auth.failed'),
            ]);

            // Alternative: Use addError if you prefer not to throw an exception directly
            // $this->addError('username', __('auth.failed'));
            // return; // Need to return if using addError
        }

        // Authentication successful, regenerate the session ID
        request()->session()->regenerate();

        // Redirect to the main application dashboard
        $this->redirect(route('app.dashboard'), navigate: true);
    }

    public function render()
    {
         // View remains the same
        return view('livewire.auth.login');
    }
}