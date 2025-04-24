<?php

namespace App\Livewire\UserProfile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Illuminate\Validation\Rules\Password; // Use dedicated Password rule object
use Illuminate\Validation\ValidationException; // To manually add errors
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Account Settings')]
class UpdateAccountInformation extends Component
{
    // Display Only
    public string $email = '';

    // Password Update Fields
    public string $current_password = '';
    public string $password = ''; // Corresponds to 'New Password'
    public string $password_confirmation = ''; // Corresponds to 'Confirm New Password'

    /**
     * Load the user's email when component mounts.
     */
    public function mount()
    {
        $this->email = Auth::user()->email;
    }

    /**
     * Define validation rules dynamically or via method for conditional logic.
     * Using a method provides more flexibility here.
     */
    protected function rules(): array
    {
        // Password rules are only required if a new password is being entered
        if (!empty($this->password) || !empty($this->current_password) || !empty($this->password_confirmation)) {
            return [
                'current_password' => ['required', 'string'], // Basic required rule here
                'password' => ['required', 'string', Password::min(8), 'confirmed'], // Use Password rule object, checks password_confirmation field
                // 'password_confirmation' rule is implicitly handled by 'confirmed' on 'password'
            ];
        }
        // If no password fields are filled, no rules apply
        return [];
    }

    /**
     * Define custom validation messages.
     */
    protected function messages(): array
    {
         return [
            'current_password.required' => 'The current password field is required when changing password.',
            'password.required' => 'The new password field is required when changing password.',
            'password.min' => 'The new password must be at least 8 characters.',
            'password.confirmed' => 'The new password confirmation does not match.',
         ];
    }

    /**
     * Update the user's password if provided and validated.
     */
    public function updateAccount()
    {
        $user = Auth::user();

        // Only proceed with password validation/update if new password field is filled
        if (!empty($this->password)) {

            // Validate password fields explicitly
            $this->validate($this->rules()); // Use rules defined in the rules() method

            // Manually check if the current password matches the one in the database
            if (!Hash::check($this->current_password, $user->password)) {
                // Throw validation exception to add error specifically to current_password
                 throw ValidationException::withMessages([
                     'current_password' => __('auth.password'), // Uses standard "password incorrect" message
                 ]);
                 // Or use addError:
                 // $this->addError('current_password', __('auth.password'));
                 // return; // Need to return if using addError
            }

            // Update the password
            $user->forceFill([
                'password' => Hash::make($this->password),
            ])->save();

             // Clear password fields after successful update
            $this->reset(['current_password', 'password', 'password_confirmation']);

            session()->flash('message', 'Password updated successfully.');

        } else {
             // Check if only current_password was filled without a new password
            if (!empty($this->current_password)) {
                 $this->addError('password', 'Please enter a new password if you wish to change it.');
                 return;
            }
            // If no password fields filled, maybe flash a message saying nothing was changed?
            session()->flash('info', 'No password information was entered. No changes made.');
        }
    }


    public function render()
    {
        return view('livewire.user-profile.update-account-information');
    }
}