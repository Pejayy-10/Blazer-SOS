<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Forgot Password')]
class ForgotPassword extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    public bool $emailSentSuccessfully = false;

    public function sendPasswordResetLink(): void
    {
        $validated = $this->validate();

        // Attempt to send the password reset link
        $status = Password::sendResetLink(
            ['email' => $validated['email']]
        );

        // Check if the password reset link was sent
        if ($status === Password::RESET_LINK_SENT) {
            $this->emailSentSuccessfully = true;
            // Reset the email field
            $this->reset('email');
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
