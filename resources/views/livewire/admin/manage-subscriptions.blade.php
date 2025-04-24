<div> {{-- Single root element --}}

    {{-- Flash Messages --}}
    <div class="mb-4">
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

    {{-- Header and Search --}}
    <div class="mb-4 md:flex md:items-center md:justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2 md:mb-0">Manage Subscriptions</h2>
        <div>
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search Name, Username, Email, Course..."
                   class="w-full md:w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 sm:text-sm">
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
            <button wire:click="setTab('pending')"
                    class="py-3 px-1 border-b-2 text-sm font-medium transition-colors duration-150
                           {{ $activeTab === 'pending' ? 'border-[#9A382F] text-[#9A382F] dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                Pending Payment
            </button>
            <button wire:click="setTab('registered')"
                    class="py-3 px-1 border-b-2 text-sm font-medium transition-colors duration-150
                           {{ $activeTab === 'registered' ? 'border-[#9A382F] text-[#9A382F] dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                Registered & Paid
            </button>
             {{-- <button wire:click="setTab('no_writeup')" class="..." > Paid (No Write-up) </button> --}} {{-- Disabled --}}
            <button wire:click="setTab('deleted')"
                    class="py-3 px-1 border-b-2 text-sm font-medium transition-colors duration-150
                           {{ $activeTab === 'deleted' ? 'border-[#9A382F] text-[#9A382F] dark:text-red-400 dark:border-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' }}">
                Deleted / Archived
            </button>
        </nav>
    </div>

    {{-- Table Area --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student Name</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Username</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">College / Course</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Submitted</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($profiles as $profile)
                        <tr wire:key="profile-{{ $profile->id }}">
                            {{-- Student Name --}}
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $profile->user->first_name }} {{ $profile->user->last_name }}
                            </td>
                             {{-- Username --}}
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $profile->user->username }}
                            </td>

                            {{-- CORRECTED College / Course --}}
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{-- Access name via relationship, provide fallback --}}
                                {{ $profile->college?->name ?? ($profile->college_id ? 'College ID: '.$profile->college_id : 'N/A') }}
                                <br class="sm:hidden">
                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $profile->course?->name ?? ($profile->course_id ? 'Course ID: '.$profile->course_id : 'N/A') }}
                                    {{-- Optionally add Major: {{ $profile->major?->name ?? '' }} --}}
                                </span>
                            </td>

                            {{-- Submitted Date --}}
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $profile->submitted_at ? $profile->submitted_at->format('Y-m-d H:i') : ($profile->profile_submitted ? 'Yes' : 'No') }}
                            </td>

                            {{-- Status Badges --}}
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                @if($activeTab === 'deleted')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        Deleted
                                    </span>
                                @elseif($profile->payment_status === 'paid')
                                     <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                         Paid {{ $profile->paid_at ? '('.$profile->paid_at->format('Y-m-d').')' : '' }}
                                     </span>
                                @elseif($profile->payment_status === 'pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100">
                                        Pending Payment
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ ucfirst($profile->payment_status) }}
                                    </span>
                                @endif
                                {{-- Show 'No Write-up' badge if applicable (even for paid) --}}
                                @if(!$profile->profile_submitted && $activeTab !== 'deleted')
                                     <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">
                                         No Write-up
                                     </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                {{-- View Details Link (Applies to most tabs) --}}
                                <a href="{{ route('admin.subscriptions.show', ['profile' => $profile->id]) }}" wire:navigate
                                    class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300" title="View Details">
                                     <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                </a>

                                {{-- Actions specific to Tab --}}
                                @if($activeTab === 'pending')
                                    <button wire:click="confirmPayment({{ $profile->id }})" wire:confirm="Are you sure you want to mark this payment as confirmed?"
                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="Confirm Payment">
                                        <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </button>
                                     <button wire:click="deleteProfile({{ $profile->id }})" wire:confirm="Are you sure you want to delete this profile? This can be undone."
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Delete Profile">
                                        <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </button>
                                @elseif($activeTab === 'registered')
                                     {{-- Delete action still available on registered tab --}}
                                     <button wire:click="deleteProfile({{ $profile->id }})" wire:confirm="Are you sure you want to delete this profile? This can be undone."
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Delete Profile">
                                        <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </button>
                                {{-- @elseif($activeTab === 'no_writeup') --}}
                                    {{-- Add relevant actions later --}}
                                @elseif($activeTab === 'deleted')
                                    <button wire:click="restoreProfile({{ $profile->id }})" wire:confirm="Are you sure you want to restore this profile?"
                                            class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300" title="Restore Profile">
                                        <svg class="inline h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    </button>
                                    {{-- Optional: Add Permanent Delete here later if needed --}}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                No student profiles found for this category{{ !empty($search) ? ' matching your search' : '' }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        @if ($profiles->hasPages())
            <div class="p-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $profiles->links() }} {{-- Standard pagination should work here --}}
            </div>
        @endif

    </div> {{-- End Table Area --}}

</div> {{-- End Single Root Element --}}