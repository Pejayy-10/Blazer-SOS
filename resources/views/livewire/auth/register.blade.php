{{-- Modern Registration Page with Enhanced Design --}}
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

                <h1 class="text-4xl sm:text-5xl font-bold mb-2 tracking-tight">Create Account</h1>
                <p class="text-lg sm:text-xl opacity-80 font-light">Join Blazer SOS today</p>
            </div>

            {{-- Form Container --}}
            <div class="bg-[#9A382F]/90 backdrop-blur-sm p-7 sm:p-8 rounded-xl shadow-2xl border border-white/10 transition-all duration-300">
                <form wire:submit.prevent="register" novalidate class="space-y-5">
                    {{-- First Name Input --}}
                    <div class="space-y-1.5">
                        <label for="first_name" class="block text-sm font-medium opacity-90">First Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input
                                type="text" id="first_name" wire:model.lazy="firstName" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-[#5F0104]/80 border @error('firstName') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200"
                                placeholder="Enter your first name" autocomplete="given-name">
                        </div>
                        @error('firstName') <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Last Name Input --}}
                    <div class="space-y-1.5">
                        <label for="last_name" class="block text-sm font-medium opacity-90">Last Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input
                                type="text" id="last_name" wire:model.lazy="lastName" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-[#5F0104]/80 border @error('lastName') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200"
                                placeholder="Enter your last name" autocomplete="family-name">
                        </div>
                        @error('lastName') <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Username Input --}}
                    <div class="space-y-1.5">
                        <label for="reg_username" class="block text-sm font-medium opacity-90">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input
                                type="text" id="reg_username" wire:model.lazy="username" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-[#5F0104]/80 border @error('username') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200"
                                placeholder="Choose a username" autocomplete="username">
                        </div>
                        @error('username') <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email Input --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-sm font-medium opacity-90">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input
                                type="email" id="email" wire:model.lazy="email" required
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-[#5F0104]/80 border @error('email') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200"
                                placeholder="you@example.com" autocomplete="email">
                        </div>
                        @error('email') <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Password Input --}}
                    <div class="space-y-1.5">
                        <label for="reg_password" class="block text-sm font-medium opacity-90">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input
                                type="password" id="reg_password" wire:model.lazy="password" required
                                class="w-full pl-10 pr-10 py-2.5 rounded-lg bg-[#5F0104]/80 border @error('password') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200"
                                placeholder="Create a password" autocomplete="new-password">
                            <button type="button" onclick="togglePasswordVisibility('reg_password', 'password-eye-icon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/70 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" id="password-eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password') <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Confirm Password Input --}}
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-sm font-medium opacity-90">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input
                                type="password" id="password_confirmation" wire:model.lazy="password_confirmation" required
                                class="w-full pl-10 pr-10 py-2.5 rounded-lg bg-[#5F0104]/80 border @error('password_confirmation') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200"
                                placeholder="Confirm your password" autocomplete="new-password">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'confirm-eye-icon')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/70 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" id="confirm-eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation') <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Terms and Conditions Checkbox --}}
                    <div class="flex items-start mt-4">
                        <div class="flex items-center h-5">
                            <input id="terms" type="checkbox" wire:model.lazy="terms" class="h-4 w-4 rounded border-gray-300 text-[#D4B79F] focus:ring-[#D4B79F] bg-[#5F0104]/80 border-white/20">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="text-white/90">I agree to the <a href="#" class="text-[#D4B79F] hover:text-[#E5C8B0] underline">Terms of Service</a> and <a href="#" class="text-[#D4B79F] hover:text-[#E5C8B0] underline">Privacy Policy</a></label>
                            @error('terms') <span class="text-red-300 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Register Button --}}
                    <button type="submit" 
                        class="w-full bg-[#D4B79F] text-[#5F0104] font-bold py-3 px-4 rounded-lg hover:bg-[#E5C8B0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#9A382F] focus:ring-[#D4B79F] transition duration-200 ease-in-out flex items-center justify-center disabled:opacity-70 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0 mt-2" 
                        wire:loading.attr="disabled" 
                        wire:target="register">
                        <span wire:loading.remove wire:target="register">
                            Create Account
                            <svg class="ml-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                        <span wire:loading wire:target="register" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-[#5F0104]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg> 
                            Creating Account...
                        </span>
                    </button>
                </form>
            </div>

            {{-- Login Link --}}
            <div class="mt-6 text-center md:text-left">
                <p class="text-white/80">
                    Already have an account?
                    <a href="{{ route('login') }}" wire:navigate class="font-bold text-[#D4B79F] hover:text-[#E5C8B0] hover:underline transition-colors duration-200 ml-1">Sign In</a>
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
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-lg">Join Blazer SOS</h2>
                <p class="text-xl md:text-2xl text-white/90 drop-shadow-md">Create your account to access all features</p>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Password Visibility Toggle --}}
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
