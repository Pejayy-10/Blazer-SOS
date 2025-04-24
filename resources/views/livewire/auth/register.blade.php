{{-- Add flex classes and min-height HERE --}}
<div class="flex flex-col md:flex-row min-h-screen">

    {{-- Left Column: Form Area (NO CHANGE NEEDED INSIDE THIS COLUMN) --}}
    <div class="w-full md:w-2/5 lg:w-1/3 bg-[#5F0104] text-white p-8 sm:p-12 flex flex-col justify-between">
        {{-- ... (all the registration form content) ... --}}
         <div>
            <img src="{{ asset('images/placeholder-logo.png') }}" alt="Blazer SOS Logo" class="h-8 sm:h-10 w-auto mb-10 sm:mb-16">
            <h1 class="text-4xl sm:text-5xl font-bold mb-2">Register</h1>
            <p class="text-lg sm:text-xl mb-8 opacity-90">Create your Blazer SOS account</p>
            <div class="bg-[#9A382F] p-6 sm:p-8 rounded-lg shadow-lg">
                 <form wire:submit.prevent="register" novalidate>
                    {{-- All form fields... --}}
                    <!-- First Name Input -->
                    <div class="mb-4">
                        <label for="first_name" class="block text-sm font-medium mb-1 opacity-80">First Name</label>
                        <input type="text" id="first_name" wire:model.lazy="firstName" required class="w-full p-2.5 rounded bg-[#5F0104] border @error('firstName') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white" placeholder="Enter your first name" autocomplete="given-name">
                        @error('firstName') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    {{-- Other fields... --}}
                    <!-- Last Name Input -->
                    <div class="mb-4">
                        <label for="last_name" class="block text-sm font-medium mb-1 opacity-80">Last Name</label>
                        <input type="text" id="last_name" wire:model.lazy="lastName" required class="w-full p-2.5 rounded bg-[#5F0104] border @error('lastName') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white" placeholder="Enter your last name" autocomplete="family-name">
                        @error('lastName') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                     <!-- Username Input -->
                    <div class="mb-4">
                        <label for="reg_username" class="block text-sm font-medium mb-1 opacity-80">Username</label>
                        <input type="text" id="reg_username" wire:model.lazy="username" required class="w-full p-2.5 rounded bg-[#5F0104] border @error('username') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white" placeholder="Choose a username" autocomplete="username">
                        @error('username') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <!-- Email Input -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium mb-1 opacity-80">Email Address</label>
                        <input type="email" id="email" wire:model.lazy="email" required class="w-full p-2.5 rounded bg-[#5F0104] border @error('email') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white" placeholder="you@example.com" autocomplete="email">
                        @error('email') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <!-- Password Input -->
                    <div class="mb-4">
                        <label for="reg_password" class="block text-sm font-medium mb-1 opacity-80">Password</label>
                        <input type="password" id="reg_password" wire:model.lazy="password" required class="w-full p-2.5 rounded bg-[#5F0104] border @error('password') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white" placeholder="Create a password" autocomplete="new-password">
                        @error('password') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                     <!-- Confirm Password Input -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium mb-1 opacity-80">Confirm Password</label>
                        <input type="password" id="password_confirmation" wire:model.lazy="password_confirmation" required class="w-full p-2.5 rounded bg-[#5F0104] border @error('password_confirmation') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white" placeholder="Confirm your password" autocomplete="new-password">
                        @error('password_confirmation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    {{-- End of other fields --}}
                    <button type="submit" class="w-full bg-[#D4B79F] text-[#5F0104] font-bold py-2.5 px-4 rounded-md hover:bg-[#cba98a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#9A382F] focus:ring-[#D4B79F] transition duration-200 ease-in-out disabled:opacity-50" wire:loading.attr="disabled" wire:target="register">
                        <span wire:loading.remove wire:target="register">Register</span>
                        <span wire:loading wire:target="register"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-[#5F0104] inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...</span>
                    </button>
                </form>
            </div>
             <p class="mt-8 text-center text-sm text-white/80">
                 Already have an account?
                 <a href="{{ route('login') }}" wire:navigate class="font-bold text-white hover:underline">LOG IN</a>
             </p>
         </div>
         <div class="text-center md:text-left mt-8">
             <a href="#" class="text-sm text-white/70 hover:text-white hover:underline">Privacy Notice</a>
         </div>
    </div>

    {{-- Right Column: Image Area (NO CHANGE NEEDED INSIDE THIS COLUMN) --}}
    <div class="hidden md:block md:w-3/5 lg:w-2/3 relative">
        <div
            class="absolute inset-0 bg-cover bg-center z-0"
             {{-- Verify this path: public/images/placeholder-school.jpg --}}
            style="background-image: url('{{ asset('images/placeholder-school.jpg') }}');">
        </div>
        <div class="absolute inset-0 bg-[#5F0104] opacity-50 z-10"></div>
    </div>

</div> {{-- End of the single root wrapper --}}