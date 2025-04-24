<div>
    {{-- Flash Messages --}}
    <div class="mb-4">
        @if (session()->has('message'))
            <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('message') }}
            </div>
        @endif
         @if (session()->has('info')) {{-- Different color for info --}}
             <div class="p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                 {{ session('info') }}
             </div>
        @endif
        @if (session()->has('error'))
             <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                 {{ session('error') }}
             </div>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <form wire:submit.prevent="updateAccount">
            <div class="px-4 py-5 sm:p-6 space-y-6">
                 <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Account Settings</h2>

                 {{-- Email (Read Only) --}}
                 <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    {{-- Display as text or disabled input --}}
                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 p-2.5 bg-gray-100 dark:bg-gray-700 rounded-md">{{ $email }}</p>
                    {{-- Or: <input type="email" id="email" value="{{ $email }}" disabled class="mt-1 block w-full rounded-md border-gray-300 ... disabled:opacity-75"> --}}
                 </div>

                 {{-- Password Change Section --}}
                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 dark:border-yellow-600 rounded-r-lg">
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                        Fill in the new password fields below <strong class="font-medium">only</strong> when you wish to update your current password. You must also provide your current password to confirm the change.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                     {{-- New Password --}}
                     <div class="sm:col-span-3">
                         <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password <span class="text-xs text-gray-500 dark:text-gray-400">— Optional</span></label>
                         <input type="password" id="password" wire:model.lazy="password" autocomplete="new-password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                         @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     </div>

                    {{-- Confirm New Password --}}
                     <div class="sm:col-span-3">
                         <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password <span class="text-xs text-gray-500 dark:text-gray-400">— Optional</span></label>
                         <input type="password" id="password_confirmation" wire:model.lazy="password_confirmation" autocomplete="new-password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                         {{-- Error shown via 'password' field's 'confirmed' rule --}}
                     </div>

                     {{-- Current Password (Required ONLY if changing) --}}
                     <div class="sm:col-span-6">
                          <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password <span class="text-xs text-gray-500 dark:text-gray-400">— Required if changing</span></label>
                         <input type="password" id="current_password" wire:model.lazy="current_password" autocomplete="current-password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                          @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                     </div>
                </div>

            </div>

             {{-- Actions Footer --}}
            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 text-right">
                <button type="submit"
                        wire:loading.attr="disabled" wire:target="updateAccount"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-50">
                     <svg wire:loading wire:target="updateAccount" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle> <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path> </svg>
                     <span>Submit Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>