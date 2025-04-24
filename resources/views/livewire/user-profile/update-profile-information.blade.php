<div>
    {{-- Flash Messages --}}
    <div class="mb-4">
        @if (session()->has('message')) <div class="p-4 text-sm text-green-700 bg-green-100..." role="alert">{{ session('message') }}</div> @endif
        @if (session()->has('error')) <div class="p-4 text-sm text-red-700 bg-red-100..." role="alert">{{ session('error') }}</div> @endif
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <form wire:submit.prevent="updateProfile">
            {{-- Form Header --}}
             <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Update Profile</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update your personal profile information. Provide your current password to save changes.</p>
            </div>

            {{-- Form Body --}}
            <div class="px-4 py-5 sm:p-6 space-y-6">

                 {{-- Name Fields --}}
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name *</label>
                        <input type="text" id="first_name" wire:model.lazy="first_name" required autocomplete="given-name"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm..."> {{-- Add input styles --}}
                         @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-3">
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name *</label>
                        <input type="text" id="last_name" wire:model.lazy="last_name" required autocomplete="family-name"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm...">
                         @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                     <div class="sm:col-span-3">
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Middle Name <span class="text-xs text-gray-500 dark:text-gray-400">— Optional</span></label>
                        <input type="text" id="middle_name" wire:model.lazy="middle_name" autocomplete="additional-name"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm...">
                         @error('middle_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="suffix" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Suffix <span class="text-xs text-gray-500 dark:text-gray-400">— Optional</span></label>
                        <input type="text" id="suffix" wire:model.lazy="suffix" autocomplete="honorific-suffix" placeholder="e.g., Jr., Sr., III"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm...">
                         @error('suffix') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                     <div class="sm:col-span-1">
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                        <select id="gender" wire:model="gender" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10..."> {{-- Add select styles --}}
                            <option value="">-- Select --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                        </select>
                        @error('gender') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Contact & Address Fields --}}
                 <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="birthdate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birthdate</label>
                        <input type="date" id="birthdate" wire:model.lazy="birthdate" autocomplete="bday"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm...">
                        @error('birthdate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-3">
                         <label for="contact_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number</label>
                        <input type="tel" id="contact_number" wire:model.lazy="contact_number" autocomplete="tel"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm...">
                        @error('contact_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-6">
                        <label for="address_line" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address <span class="text-xs text-gray-500 dark:text-gray-400">— House No., Street, Barangay</span></label>
                         <input type="text" id="address_line" wire:model.lazy="address_line" autocomplete="street-address"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm...">
                        @error('address_line') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="sm:col-span-6">
                         <label for="city_province" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City, Province</label>
                         <input type="text" id="city_province" wire:model.lazy="city_province" autocomplete="address-level2" {{-- Or adjust autocomplete --}}
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm...">
                        @error('city_province') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                 </div>

                 {{-- Password Verification --}}
                 <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                     <label for="current_password_profile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password * <span class="text-xs text-gray-500 dark:text-gray-400">— Required to Save Changes</span></label>
                     <input type="password" id="current_password_profile" wire:model.lazy="current_password" required autocomplete="current-password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm...">
                      @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                 </div>

            </div>

             {{-- Actions Footer --}}
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 text-right">
                <button type="submit"
                        wire:loading.attr="disabled" wire:target="updateProfile"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-50">
                      <svg wire:loading wire:target="updateProfile" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle> <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path> </svg>
                     <span>Submit Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>