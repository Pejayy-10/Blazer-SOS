<div x-data="{ showInviteModal: $wire.entangle('showInviteModal') }"> {{-- Root div --}}

    {{-- MOVE FLASH MESSAGES HERE (Immediately inside root div) --}}
    <div class="mb-4"> {{-- Optional wrapper for spacing --}}
        @if (session()->has('message'))
            <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
             <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                 {{ session('error') }}
             </div>
        @endif
    </div>
    {{-- END FLASH MESSAGES --}}

    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Manage Staff</h2>

    {{-- Tab Navigation --}}
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
            {{-- Staff Tab --}}
            <button wire:click="setTab('staff')"
                    class="py-3 px-1 border-b-2 text-sm font-medium transition-colors duration-150
                           {{ $activeTab === 'staff' ? 'border-[#9A382F] text-[#9A382F] dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                Registered Staff
            </button>
             {{-- Invited Tab --}}
            <button wire:click="setTab('invited')"
                    class="py-3 px-1 border-b-2 text-sm font-medium transition-colors duration-150
                           {{ $activeTab === 'invited' ? 'border-[#9A382F] text-[#9A382F] dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                Pending Invitations
            </button>
        </nav>
    </div>

    {{-- Conditional Content Area --}}
    <div class="mt-6">

        {{-- REGISTERED STAFF TAB --}}
        <div x-show="$wire.activeTab === 'staff'" style="display: {{ $activeTab === 'staff' ? 'block' : 'none' }};">
            <div class="mb-4">
                <input type="text" wire:model.debounce.300ms="searchStaff" placeholder="Search Staff Name, Username, Role..."
                       class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Username</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role Name</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($staff as $adminUser)
                                <tr wire:key="staff-{{ $adminUser->id }}">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $adminUser->first_name }} {{ $adminUser->last_name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $adminUser->username }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $adminUser->email }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $adminUser->role_name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                        {{-- Add Edit/Delete Staff actions later if needed --}}
                                        <button class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 disabled:opacity-50 disabled:cursor-not-allowed" title="Edit Staff (Not Implemented)" disabled>
                                            <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 disabled:opacity-50 disabled:cursor-not-allowed" title="Delete Staff (Not Implemented)" disabled>
                                             <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No registered staff found{{ !empty($searchStaff) ? ' matching your search' : '' }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                 {{-- Pagination --}}
                @if ($staff->hasPages())
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        {{ $staff->links('livewire.pagination-links') }} {{-- Use custom view for named pages --}}
                    </div>
                @endif
            </div>
        </div>

        {{-- PENDING INVITATIONS TAB --}}
        <div x-show="$wire.activeTab === 'invited'" style="display: {{ $activeTab === 'invited' ? 'block' : 'none' }};">
             <div class="mb-4 md:flex md:items-center md:justify-between">
                {{-- Search Input --}}
                <input type="text" wire:model.debounce.300ms="searchInvited" placeholder="Search Invited Email or Role..."
                       class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm mb-2 md:mb-0">
                {{-- Invite Button --}}
                <button type="button" @click="showInviteModal = true"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-[#9A382F] py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-[#5F0104] focus:outline-none focus:ring-2 focus:ring-[#9A382F] focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                     <svg class="h-5 w-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 11a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1v-1z" /></svg>
                    Invite New Staff
                </button>
             </div>
             <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                         <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Invited Email</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Assigned Role</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sent</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Expires</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                         <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                             @forelse ($invitations as $invite)
                                 <tr wire:key="invite-{{ $invite->id }}">
                                     <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $invite->email }}</td>
                                     <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $invite->role_name }}</td>
                                     <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $invite->created_at->diffForHumans() }}</td>
                                     <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $invite->expires_at->format('Y-m-d') }}</td>
                                     <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                         <button wire:click="deleteInvitation({{ $invite->id }})" wire:confirm="Are you sure you want to delete this pending invitation for {{ $invite->email }}?"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Delete Invitation">
                                             <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                         </button>
                                         {{-- Add Resend button/action later --}}
                                         <button class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 disabled:opacity-50 disabled:cursor-not-allowed" title="Resend Invitation (Not Implemented)" disabled>
                                              <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 14a1 1 0 01-.707-.293l-3-3a1 1 0 111.414-1.414L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3A1 1 0 0110 14z M10 2a8 8 0 100 16 8 8 0 000-16z" /></svg>
                                         </button>
                                     </td>
                                 </tr>
                             @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No pending invitations found{{ !empty($searchInvited) ? ' matching your search' : '' }}.
                                    </td>
                                </tr>
                            @endforelse
                         </tbody>
                    </table>
                </div>
                 {{-- Pagination --}}
                @if ($invitations->hasPages())
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        {{ $invitations->links('livewire.pagination-links') }} {{-- Use custom view for named pages --}}
                    </div>
                @endif
            </div>
        </div>

    </div> {{-- End Conditional Content Area --}}


    {{-- Invite Staff Modal --}}
    <div x-show="showInviteModal"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true"
         style="display: none;" {{-- Hide initially --}}
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- Overlay --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-opacity-80"></div>

        {{-- Modal Panel --}}
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div @click.outside="showInviteModal = false"
                 class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                <form wire:submit.prevent="sendInvitation">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                             <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                                {{-- Heroicon: mail --}}
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Invite New Staff Member
                                </h3>
                                <div class="mt-4 space-y-4">
                                    {{-- Email Input --}}
                                    <div>
                                        <label for="inviteEmail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Staff Email Address</label>
                                        <input type="email" id="inviteEmail" wire:model.lazy="inviteEmail" required
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                                               placeholder="staffmember@example.com">
                                        @error('inviteEmail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                     {{-- Role Name Dropdown --}}
                                     <div>
                                        <label for="inviteRoleName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Role Name</label>
                                        <select id="inviteRoleName" wire:model="inviteRoleName" required
                                                class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                                            <option value="">-- Select Role --</option>
                                            @foreach($roleNames as $roleName)
                                                <option value="{{ $roleName }}">{{ $roleName }}</option>
                                            @endforeach
                                        </select>
                                        @error('inviteRoleName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                wire:loading.attr="disabled" wire:target="sendInvitation"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#9A382F] text-base font-medium text-white hover:bg-[#5F0104] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9A382F] sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                            <span wire:loading.remove wire:target="sendInvitation">Send Invitation</span>
                             <span wire:loading wire:target="sendInvitation">Sending...</span>
                        </button>
                        <button type="button" @click="showInviteModal = false" wire:loading.attr="disabled"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> {{-- End Modal --}}

</div>