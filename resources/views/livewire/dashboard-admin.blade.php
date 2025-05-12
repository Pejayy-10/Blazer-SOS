<div> {{-- The ONLY top-level element in this file --}}

    {{-- Include Header --}}
    <x-yearbook-header :platform="$activePlatform" />

    {{-- Admin specific content below header --}}
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Admin Dashboard</h2>

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Platform Status Card --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border-l-4 {{ $platformStatus === 'open' ? 'border-green-500' : ($platformStatus === 'N/A' || $platformStatus === 'closed' ? 'border-red-500' : 'border-gray-500') }}">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-500 dark:bg-indigo-600 rounded-md p-2">
                     {{-- Icons based on $platformStatus string --}}
                    @if($platformStatus === 'open')
                         <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @else
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                    @endif
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Platform Status</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $platformStatus !== 'N/A' ? ucfirst($platformStatus) : 'Not Active' }}
                    </p>
                </div>
            </div>
            {{-- Link for Platform Status Card --}}
            <div class="text-right mt-2">
                <a href="{{ route('admin.platforms.index') }}" wire:navigate class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Manage Platforms</a>
            </div>
        </div> {{-- End Platform Status Card --}}

        {{-- Pending Payments Card --}}
         <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border-l-4 border-yellow-500">
             <div class="flex items-center">
                 <div class="flex-shrink-0 bg-yellow-500 dark:bg-yellow-600 rounded-md p-2">
                      <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                 </div>
                 <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pending Payments</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-gray-100"> {{ $pendingPaymentsCount }} </p>
                </div>
            </div>
             <div class="text-right mt-2">
                 <a href="{{ route('admin.subscriptions.index', ['tab' => 'pending']) }}" wire:navigate class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">View Pending</a>
             </div>
        </div> {{-- End Pending Payments Card --}}

        {{-- Registered/Paid Card --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 dark:bg-green-600 rounded-md p-2">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Registered (Paid)</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-gray-100"> {{ $registeredPaidCount }} </p>
                </div>
            </div>
             <div class="text-right mt-2">
                 <a href="{{ route('admin.subscriptions.index', ['tab' => 'registered']) }}" wire:navigate class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">View Registered</a>
             </div>
        </div> {{-- End Registered/Paid Card --}}

         {{-- Total Profiles Card --}}
         <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border-l-4 border-blue-500">
             <div class="flex items-center">
                 <div class="flex-shrink-0 bg-blue-500 dark:bg-blue-600 rounded-md p-2">
                      <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                 </div>
                <div class="ml-4 flex-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Profiles Submitted</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-gray-100"> {{ $totalProfilesSubmitted }} </p>
                </div>
            </div>
              <div class="text-right mt-2">
                 <a href="{{ route('admin.repository.index') }}" wire:navigate class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">View Repository</a>
             </div>
        </div> {{-- End Total Profiles Card --}}

    </div> {{-- End Stats Grid --}}

    {{-- Include Bulletin Board --}}
    <div class="mt-8">
        @livewire('bulletin')
    </div>

    {{-- Include the Upload Modal Component (Ensure this component exists or remove this line if not needed) --}}
    {{-- @livewire('admin.upload-platform-background') --}}

</div> {{-- End Single Root Element Wrapper --}}