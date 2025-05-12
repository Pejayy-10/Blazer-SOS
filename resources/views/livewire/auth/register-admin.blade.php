{{-- This component uses the guest layout defined in its class --}}
<div class="flex flex-col md:flex-row min-h-screen">

    {{-- Left Column: Form/Message Area --}}
    <div class="w-full md:w-2/5 lg:w-1/3 bg-[#5F0104] text-white p-8 sm:p-12 flex flex-col justify-center">

        {{-- Logo --}}
        <div class="mb-10">
            <img src="{{ asset('images/placeholder-logo.png') }}" alt="{{ config('app.name', 'Blazer SOS') }} Logo"
            class="h-16 sm:h-20 w-auto inline-block"> {{-- Increased h-16/h-20 --}}
        </div>

        @if($isValidInvitation)
             {{-- Show Registration Form if Token is Valid --}}
            <h1 class="text-3xl sm:text-4xl font-bold mb-2">Admin Registration</h1>
            <p class="text-lg sm:text-xl mb-6 opacity-90">Complete your staff account setup.</p>

            <div class="bg-[#9A382F] p-6 sm:p-8 rounded-lg shadow-lg">
                 <form wire:submit.prevent="registerAdmin" novalidate>

                     {{-- Display Email (Read Only) --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1 opacity-80">Email Address</label>
                        <p class="mt-1 text-sm text-gray-100 p-2.5 bg-gray-700/50 rounded">{{ $email }}</p>
                    </div>

                    {{-- Display Assigned Role (Read Only) --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1 opacity-80">Assigned Role</label>
                        <p class="mt-1 text-sm text-gray-100 p-2.5 bg-gray-700/50 rounded">{{ $assignedRoleName }}</p>
                    </div>
                    <hr class="border-white/20 my-4">

                    {{-- First Name Input --}}
                    <div class="mb-4">
                        <label for="first_name" class="block text-sm font-medium mb-1 opacity-80">First Name</label>
                        <input type="text" id="first_name" wire:model.lazy="firstName" required
                               class="w-full p-2.5 rounded bg-[#5F0104] border @error('firstName') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white"
                               placeholder="Enter your first name" autocomplete="given-name">
                        @error('firstName') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                     {{-- Last Name Input --}}
                    <div class="mb-4">
                        <label for="last_name" class="block text-sm font-medium mb-1 opacity-80">Last Name</label>
                        <input type="text" id="last_name" wire:model.lazy="lastName" required
                               class="w-full p-2.5 rounded bg-[#5F0104] border @error('lastName') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white"
                               placeholder="Enter your last name" autocomplete="family-name">
                        @error('lastName') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Username Input --}}
                    <div class="mb-4">
                        <label for="reg_username" class="block text-sm font-medium mb-1 opacity-80">Username</label>
                        <input type="text" id="reg_username" wire:model.lazy="username" required
                               class="w-full p-2.5 rounded bg-[#5F0104] border @error('username') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white"
                               placeholder="Choose a username" autocomplete="username">
                        @error('username') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Password Input --}}
                    <div class="mb-4">
                        <label for="reg_password" class="block text-sm font-medium mb-1 opacity-80">Password</label>
                        <input type="password" id="reg_password" wire:model.lazy="password" required
                               class="w-full p-2.5 rounded bg-[#5F0104] border @error('password') border-red-500 @else border-transparent @enderror focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white"
                               placeholder="Create a password (min. 8 characters)" autocomplete="new-password">
                        @error('password') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Confirm Password Input --}}
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium mb-1 opacity-80">Confirm Password</label>
                        <input type="password" id="password_confirmation" wire:model.lazy="password_confirmation" required
                               class="w-full p-2.5 rounded bg-[#5F0104] border border-transparent focus:outline-none focus:ring-2 focus:ring-white/60 placeholder-white/50 text-white"
                               placeholder="Confirm your password" autocomplete="new-password">
                        {{-- Error for password_confirmation often shows under the 'password' field due to 'confirmed' rule --}}
                    </div>

                    {{-- Register Button --}}
                    <button type="submit"
                            class="w-full bg-[#D4B79F] text-[#5F0104] font-bold py-2.5 px-4 rounded-md hover:bg-[#cba98a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#9A382F] focus:ring-[#D4B79F] transition duration-200 ease-in-out disabled:opacity-50"
                            wire:loading.attr="disabled" wire:target="registerAdmin">
                        <span wire:loading.remove wire:target="registerAdmin">Complete Registration</span>
                        <span wire:loading wire:target="registerAdmin">Processing...</span>
                    </button>
                </form>
            </div>

        @else
            {{-- Show Error Message if Token is Invalid --}}
             <h1 class="text-3xl sm:text-4xl font-bold mb-2 text-yellow-300">Invalid Invitation</h1>
             <div class="bg-red-800/50 border border-red-700 text-white px-4 py-3 rounded relative" role="alert">
                 <strong class="font-bold">Error:</strong>
                 <span class="block sm:inline">{{ $errorMessage ?: 'The invitation link is invalid, expired, or has already been used.' }}</span>
             </div>
             <p class="mt-6 text-center">
                 <a href="{{ route('login') }}" wire:navigate class="font-bold text-white hover:underline">Return to Login</a>
             </p>
        @endif

         {{-- Optional: Privacy Notice or other links --}}
        <div class="text-center mt-8">
            <a href="#" class="text-sm text-white/70 hover:text-white hover:underline">Privacy Notice</a>
        </div>

    </div>

    {{-- Right Column: Image Area --}}
    <div class="hidden md:block md:w-3/5 lg:w-2/3 relative">
        <div class="absolute inset-0 bg-cover bg-center z-0" style="background-image: url('{{ asset('images/placeholder-school.jpg') }}');"></div>
        <div class="absolute inset-0 bg-[#5F0104] opacity-50 z-10"></div>
    </div>

</div>