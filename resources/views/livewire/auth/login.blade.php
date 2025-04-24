{{-- Add flex classes and min-height HERE --}}
<div class="flex flex-col md:flex-row min-h-screen">

    {{-- Left Column: Form Area (NO CHANGE NEEDED INSIDE THIS COLUMN) --}}
    <div class="w-full md:w-2/5 lg:w-1/3 bg-[#5F0104] text-white p-8 sm:p-12 flex flex-col justify-between">
        <!-- Top Content: Logo & Welcome -->
        <div>
            {{-- Verify this path: public/images/placeholder-logo.svg --}}
            <img src="{{ asset('images/placeholder-logo.png') }}" alt="Blazer SOS Logo" class="h-8 sm:h-10 w-auto mb-10 sm:mb-16">

            <h1 class="text-4xl sm:text-5xl font-bold mb-2">Welcome!</h1>
            <p class="text-lg sm:text-xl mb-8 opacity-90">The New Blazer SOS</p>

            <!-- Form Container -->
            <div class="bg-[#9A382F] p-6 sm:p-8 rounded-lg shadow-lg">
                <form wire:submit.prevent="authenticate" novalidate>
                    {{-- ... (rest of the form code remains the same) ... --}}
                     <!-- Username Input -->
                    <div class="mb-5">
                        <label for="username" class="block text-sm font-medium mb-1 opacity-80">Username or Email</label>
                        <input
                            type="text" id="username" wire:model.lazy="username" required
                            class="w-full p-2.5 rounded bg-[#5F0104] border @error('username') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white"
                            placeholder="Enter your username or email" autocomplete="username">
                        @error('username') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <!-- Password Input -->
                    <div class="mb-5">
                         <label for="password" class="block text-sm font-medium mb-1 opacity-80">Password</label>
                         <input
                            type="password" id="password" wire:model.lazy="password" required
                            class="w-full p-2.5 rounded bg-[#5F0104] border @error('password') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white"
                            placeholder="Enter your password" autocomplete="current-password">
                         @error('password') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                     <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between mb-6">
                         <div class="flex items-center">
                            <input id="remember_me" wire:model="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-[#5F0104] focus:ring-[#9A382F] bg-[#5F0104]">
                            <label for="remember_me" class="ml-2 block text-sm text-white opacity-90">Remember me</label>
                        </div>
                    </div>
                    <!-- Login Button -->
                    <button type="submit" class="w-full bg-[#D4B79F] text-[#5F0104] font-bold py-2.5 px-4 rounded-md hover:bg-[#cba98a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#9A382F] focus:ring-[#D4B79F] transition duration-200 ease-in-out flex items-center justify-center disabled:opacity-50" wire:loading.attr="disabled" wire:target="authenticate">
                         <span wire:loading.remove wire:target="authenticate">Log In <svg class="ml-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></span>
                         <span wire:loading wire:target="authenticate"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-[#5F0104]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...</span>
                    </button>
                </form>
            </div>

            <!-- Register Link -->
            <p class="mt-8 text-center text-sm text-white/80">
                Don't have an account?
                <a href="{{ route('register') }}" wire:navigate class="font-bold text-white hover:underline">REGISTER</a>
            </p>
        </div>

        <!-- Bottom Content: Privacy Notice -->
        <div class="text-center md:text-left mt-8">
            <a href="#" class="text-sm text-white/70 hover:text-white hover:underline">Privacy Notice</a>
        </div>
    </div>

    {{-- Right Column: Image Area (NO CHANGE NEEDED INSIDE THIS COLUMN) --}}
    <div class="hidden md:block md:w-3/5 lg:w-2/3 relative">
        <!-- Background Image -->
        <div
            class="absolute inset-0 bg-cover bg-center z-0"
            {{-- Verify this path: public/images/placeholder-school.jpg --}}
            style="background-image: url('{{ asset('images/placeholder-school.jpg') }}');">
        </div>
        <!-- Reddish Overlay -->
        <div class="absolute inset-0 bg-[#5F0104] opacity-50 z-10"></div>
    </div>

</div> {{-- End of the single root wrapper --}}