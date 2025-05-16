<div class="flex flex-col md:flex-row">
    {{-- Left Column: Form Area (Similar to login page) --}}
    <div class="w-full md:w-2/3 lg:w-1/2 mx-auto bg-gradient-to-br from-[#5F0104] to-[#7A1518] text-white p-8 sm:p-12 flex flex-col justify-between relative overflow-hidden rounded-xl shadow-xl">
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
                     class="h-16 w-auto mb-4 transition-all duration-300 hover:scale-105">

                <h1 class="text-3xl sm:text-4xl font-bold mb-1 tracking-tight">Change Password</h1>
                <p class="text-lg opacity-80 font-light">Update your account security</p>
            </div>

            {{-- Flash Messages --}}
            <div class="mb-6">
                @if (session()->has('message'))
                    <div class="p-4 text-sm text-[#5F0104] bg-[#D4B79F] rounded-lg" role="alert">
                        <span class="font-medium">Success!</span> {{ session('message') }}
                    </div>
                @endif
                @if (session()->has('info'))
                    <div class="p-4 text-sm text-[#5F0104] bg-[#D4B79F]/70 rounded-lg" role="alert">
                        <span class="font-medium">Note:</span> {{ session('info') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="p-4 text-sm text-white bg-red-700 rounded-lg" role="alert">
                        <span class="font-medium">Error!</span> {{ session('error') }}
                    </div>
                @endif
            </div>

            {{-- Form Container --}}
            <div class="bg-[#9A382F]/90 backdrop-blur-sm p-7 sm:p-8 rounded-xl shadow-2xl border border-white/10 transition-all duration-300">
                <form wire:submit.prevent="updateAccount" class="space-y-6">
                    {{-- Email (Read Only) --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-sm font-medium opacity-90">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" value="{{ $email }}" disabled
                                   class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-[#5F0104]/80 border border-white/10 focus:outline-none text-white/75 transition-all duration-200 disabled:opacity-60">
                        </div>
                    </div>

                    {{-- Current Password --}}
                    <div class="space-y-1.5">
                        <label for="current_password" class="block text-sm font-medium opacity-90">Current Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="current_password" wire:model.lazy="current_password" autocomplete="current-password"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-[#5F0104]/80 border @error('current_password') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200"
                                   placeholder="Enter your current password">
                            <button type="button" onclick="togglePasswordVisibility('current_password', 'current-eye-icon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/70 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" id="current-eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('current_password') <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="space-y-1.5">
                        <label for="password" class="block text-sm font-medium opacity-90">New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" wire:model.lazy="password" autocomplete="new-password"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-[#5F0104]/80 border @error('password') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200"
                                   placeholder="Enter your new password">
                            <button type="button" onclick="togglePasswordVisibility('password', 'password-eye-icon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/70 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" id="password-eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password') <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Confirm New Password --}}
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-sm font-medium opacity-90">Confirm New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password_confirmation" wire:model.lazy="password_confirmation" autocomplete="new-password"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-[#5F0104]/80 border @error('password_confirmation') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200" 
                                   placeholder="Confirm your new password">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'confirm-eye-icon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/70 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" id="confirm-eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Update Button --}}
                    <button type="submit" 
                        class="w-full bg-[#D4B79F] text-[#5F0104] font-bold py-3 px-4 rounded-lg hover:bg-[#E5C8B0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#9A382F] focus:ring-[#D4B79F] transition duration-200 ease-in-out flex items-center justify-center disabled:opacity-70 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0 mt-4" 
                        wire:loading.attr="disabled" 
                        wire:target="updateAccount">
                        <span wire:loading.remove wire:target="updateAccount">
                            Update Password
                            <svg class="ml-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </span>
                        <span wire:loading wire:target="updateAccount" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-[#5F0104]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg> 
                            Updating Password...
                        </span>
                    </button>
                </form>
            </div>

            {{-- Security Note --}}
            <div class="mt-6 text-sm text-white/70 p-4 bg-[#5F0104]/50 rounded-lg border border-white/10">
                <p class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    For security reasons, please use a strong password with a mix of letters, numbers, and special characters. Update your password regularly to maintain account security.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Add toggle password visibility script --}}
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }
</script>