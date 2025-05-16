<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

{{-- Modern Forgot Password Page with Design Matching Login --}}
<div class="flex flex-col md:flex-row min-h-screen">

    {{-- Left Column: Form Area --}}
    <div class="w-full md:w-2/5 lg:w-1/3 bg-gradient-to-br from-[#5F0104] to-[#7A1518] text-white p-8 sm:p-12 flex flex-col justify-between relative overflow-hidden">
        {{-- Decorative Elements --}}
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full filter blur-3xl -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-[#D4B79F] rounded-full filter blur-3xl -ml-32 -mb-32"></div>
        </div>

        {{-- Content Container with z-index to appear above decorative elements --}}
        <div class="relative z-10">
            {{-- Logo & Welcome --}}
            <div class="mb-6 flex flex-col items-center md:items-start">
                <img src="{{ asset('images/placeholder-logo.png') }}" alt="{{ config('app.name', 'Blazer SOS') }} Logo"
                     class="h-20 sm:h-24 w-auto mb-6 transition-all duration-300 hover:scale-105">

                <h1 class="text-3xl sm:text-4xl font-bold mb-2 tracking-tight">Forgot Password</h1>
                <p class="text-lg sm:text-xl opacity-80 font-light">Enter your email to reset your password</p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-6 p-4 text-sm text-[#5F0104] bg-[#D4B79F] rounded-lg" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Form Container --}}
            <div class="bg-[#9A382F]/90 backdrop-blur-sm p-7 sm:p-8 rounded-xl shadow-2xl border border-white/10 transition-all duration-300">
                <form wire:submit="sendPasswordResetLink" class="space-y-5">
                    {{-- Email Input --}}
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium opacity-90">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input
                                type="email" id="email" wire:model="email" required autofocus
                                class="w-full pl-10 pr-4 py-3 rounded-lg bg-[#5F0104]/80 border @error('email') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200"
                                placeholder="Enter your email address">
                        </div>
                        @error('email') <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Reset Button --}}
                    <button type="submit" 
                        class="w-full bg-[#D4B79F] text-[#5F0104] font-bold py-3 px-4 rounded-lg hover:bg-[#E5C8B0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#9A382F] focus:ring-[#D4B79F] transition duration-200 ease-in-out flex items-center justify-center disabled:opacity-70 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0" 
                        wire:loading.attr="disabled" 
                        wire:target="sendPasswordResetLink">
                        <span wire:loading.remove wire:target="sendPasswordResetLink">
                            Send Password Reset Link
                            <svg class="ml-2 inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <span wire:loading wire:target="sendPasswordResetLink" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-[#5F0104]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg> 
                            Sending...
                        </span>
                    </button>
                </form>
            </div>

            {{-- Login Link --}}
            <div class="mt-6 text-center md:text-left">
                <p class="text-white/80">
                    Remember your password?
                    <a href="{{ route('login') }}" wire:navigate class="font-bold text-[#D4B79F] hover:text-[#E5C8B0] hover:underline transition-colors duration-200 ml-1">Back to Login</a>
                </p>
            </div>
        </div>

        {{-- Bottom Content: Privacy Notice --}}
        <div class="text-center md:text-left mt-6 relative z-10">
            <a href="#" class="text-sm text-white/70 hover:text-white hover:underline transition-colors duration-200">Privacy Notice</a>
            <span class="mx-2 text-white/40">•</span>
            <a href="#" class="text-sm text-white/70 hover:text-white hover:underline transition-colors duration-200">Terms of Service</a>
        </div>
    </div>

    {{-- Right Column: Image Area --}}
    <div class="hidden md:block md:w-3/5 lg:w-2/3 relative">
        {{-- Background Image --}}
        <div
            class="absolute inset-0 bg-cover bg-center z-0"
            style="background-image: url('{{ asset('images/placeholder-school.jpg') }}');">
        </div>
        
        {{-- Enhanced Overlay with Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-[#5F0104]/70 to-[#5F0104]/40 z-10"></div>
        
        {{-- Content Overlay --}}
        <div class="absolute inset-0 flex flex-col justify-center items-center z-20 p-12">
            <div class="max-w-xl text-center">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-lg">Blazer SOS</h2>
                <p class="text-xl md:text-2xl text-white/90 drop-shadow-md">The New Blazer Yearbook Subscription System</p>
            </div>
        </div>
    </div>
</div>
