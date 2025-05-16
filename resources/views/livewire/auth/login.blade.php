{{-- Modern Login Page with Enhanced Design --}}
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
            <div class="mb-8 flex flex-col items-center md:items-start">
                <img src="{{ asset('images/placeholder-logo.png') }}" alt="{{ config('app.name', 'Blazer SOS') }} Logo"
                     class="h-20 sm:h-24 w-auto mb-6 transition-all duration-300 hover:scale-105">

                <h1 class="text-4xl sm:text-5xl font-bold mb-2 tracking-tight">Welcome Back</h1>
                <p class="text-lg sm:text-xl opacity-80 font-light">Sign in to Blazer SOS</p>
            </div>

            {{-- Form Container --}}
            <div class="bg-[#9A382F]/90 backdrop-blur-sm p-7 sm:p-8 rounded-xl shadow-2xl border border-white/10 transition-all duration-300">
                <form wire:submit.prevent="authenticate" novalidate class="space-y-6">
                    {{-- Username Input --}}
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-medium opacity-90">Username or Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input
                                type="text" id="username" wire:model.lazy="username" required
                                class="w-full pl-10 pr-4 py-3 rounded-lg bg-[#5F0104]/80 border @error('username') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200"
                                placeholder="Enter your username or email" autocomplete="username">
                        </div>
                        @error('username') <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Password Input --}}
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium opacity-90">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input
                                type="password" id="password" wire:model.lazy="password" required
                                class="w-full pl-10 pr-4 py-3 rounded-lg bg-[#5F0104]/80 border @error('password') border-red-500 @else border-white/10 @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white transition-all duration-200"
                                placeholder="Enter your password" autocomplete="current-password">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-white/70 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" id="password-eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password') <span class="text-red-300 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Remember Me Checkbox --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" wire:model="remember" type="checkbox" 
                                class="h-4 w-4 rounded border-gray-300 text-[#D4B79F] focus:ring-[#D4B79F] bg-[#5F0104]/80 border-white/20">
                            <label for="remember_me" class="ml-2 block text-sm text-white opacity-90">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" wire:navigate class="text-sm text-[#D4B79F] hover:text-[#E5C8B0] transition-colors duration-200">Forgot password?</a>
                    </div>

                    {{-- Login Button --}}
                    <button type="submit" 
                        class="w-full bg-[#D4B79F] text-[#5F0104] font-bold py-3 px-4 rounded-lg hover:bg-[#E5C8B0] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#9A382F] focus:ring-[#D4B79F] transition duration-200 ease-in-out flex items-center justify-center disabled:opacity-70 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0" 
                        wire:loading.attr="disabled" 
                        wire:target="authenticate">
                        <span wire:loading.remove wire:target="authenticate">
                            Sign In 
                            <svg class="ml-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                        <span wire:loading wire:target="authenticate" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-[#5F0104]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg> 
                            Signing In...
                        </span>
                    </button>
                </form>
            </div>

            {{-- Register Link --}}
            <div class="mt-8 text-center md:text-left">
                <p class="text-white/80">
                    Don't have an account?
                    <a href="{{ route('register') }}" wire:navigate class="font-bold text-[#D4B79F] hover:text-[#E5C8B0] hover:underline transition-colors duration-200 ml-1">Create Account</a>
                </p>
            </div>
        </div>

        {{-- Bottom Content: Privacy Notice --}}
        <div class="text-center md:text-left mt-8 relative z-10">
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

{{-- JavaScript for Password Visibility Toggle --}}
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('password-eye-icon');
        
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
