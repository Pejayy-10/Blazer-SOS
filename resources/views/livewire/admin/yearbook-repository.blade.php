<div>
     <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Yearbook Repository</h2>

     {{-- Filters Section --}}
    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-4 items-end">
            {{-- Search --}}
            <div class="lg:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" id="search" wire:model.lazy="search" placeholder="Name, Username, Nickname..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>

             {{-- Platform Filter --}}
            <div>
                <label for="filterPlatform" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Yearbook Platform</label>
                <select id="filterPlatform" wire:model="filterPlatformId" wire:change="$refresh"
                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                    <option value="">-- All Platforms --</option>
                    @foreach ($platforms as $platform)
                        <option value="{{ $platform->id }}">{{ $platform->year }} - {{ $platform->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- College Filter --}}
            <div>
                <label for="filterCollege" class="block text-sm font-medium text-gray-700 dark:text-gray-300">College</label>
                <select id="filterCollege" wire:model="filterCollegeId" wire:change="$refresh"
                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                    <option value="">-- All Colleges --</option>
                    @foreach ($colleges as $college)
                        <option value="{{ $college->id }}">{{ $college->abbreviation ?? $college->name }}</option>
                    @endforeach
                </select>
            </div>

             {{-- Course Filter --}}
            <div>
                <label for="filterCourse" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Course</label>
                <select id="filterCourse" wire:model="filterCourseId" wire:change="$refresh" {{ empty($filterCollegeId) ? 'disabled' : '' }}
                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <option value="">-- All Courses --</option>
                    {{-- Use $courses collection passed from render --}}
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->abbreviation ?? $course->name }}</option>
                    @endforeach
                </select>
            </div>

             {{-- Payment Status Filter --}}
            <div>
                <label for="filterPaymentStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status</label>
                <select id="filterPaymentStatus" wire:model="filterPaymentStatus" wire:change="$refresh"
                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                    <option value="">-- All Statuses --</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    {{-- <option value="partial">Partial</option> --}}
                </select>
            </div>

             {{-- Add Major filter dropdown here if needed --}}

            {{-- Clear Filters Button --}}
            <div class="pt-5"> 
                <button type="button" wire:click="resetFilters"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white dark:bg-gray-600 dark:border-gray-500 px-3 py-2 text-sm font-medium leading-4 text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 w-full">
                     <svg class="h-4 w-4 mr-1.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" > <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /> </svg>
                    Clear Filters
                </button>
            </div>

        </div>
    </div>

    {{-- Export Section --}}
    <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-900 rounded-lg shadow">
        <div class="md:flex md:items-center md:justify-between">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2 md:mb-0">Export Data</h3>
            <div class="flex items-center space-x-3">
                <div>
                     <label for="exportFormat" class="sr-only">Export Format</label>
                     <select id="exportFormat" wire:model="exportFormat"
                             class="rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                         <option value="xlsx">Excel (XLSX)</option>
                         <option value="csv">CSV</option>
                         <option value="json">JSON</option>
                     </select>
                </div>
                <button type="button" wire:click="exportData" wire:loading.attr="disabled" wire:target="exportData"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 disabled:opacity-50">
                    <svg wire:loading.remove wire:target="exportData" class="h-5 w-5 mr-1.5 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    <span wire:loading.remove wire:target="exportData">Export Filtered Data</span>
                    <span wire:loading wire:target="exportData">Exporting...</span>
                </button>
            </div>
        </div>
        @error('exportFormat') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>
    {{-- End Export Section --}}


     {{-- Profile Cards Grid --}}
     @if($profiles->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($profiles as $profile)
                <div wire:key="profile-card-{{ $profile->id }}"
                     class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col">
                    {{-- Profile Picture --}}
                    <div class="aspect-w-1 aspect-h-1 w-full bg-gray-200 dark:bg-gray-700">
                        @php
                            // Use the eager-loaded relationship, access first() on the collection
                            $firstPhoto = $profile->user->yearbookPhotos->first();
                            $photoUrl = $firstPhoto ? $firstPhoto->url : asset('images/placeholder-avatar.png');
                        @endphp
                        <img src="{{ $photoUrl }}" alt="{{ $profile->user->first_name ?? 'Student' }}'s photo"
                             class="object-cover w-full h-full">
                    </div>
                    {{-- Card Body --}}
                    <div class="p-4 flex-grow flex flex-col justify-between">
                         <div>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate" title="{{ $profile->user->first_name ?? '' }} {{ $profile->user->last_name ?? '' }}">
                                {{ $profile->user->first_name ?? 'N/A' }} {{ $profile->user->last_name ?? '' }}
                            </p>
                             {{-- Access relationships loaded via with() --}}
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $profile->college?->abbreviation ?? $profile->college?->name ?? 'N/A College' }}
                            </p>
                             <p class="text-xs text-gray-500 dark:text-gray-500 truncate" title="{{ $profile->course->name ?? '' }}">
                                 {{ $profile->course?->abbreviation ?? $profile->course?->name ?? 'N/A Course' }}
                             </p>
                             @if($profile->major)
                                 <p class="text-xs text-indigo-600 dark:text-indigo-400 truncate" title="{{ $profile->major->name }}">
                                     Major: {{ $profile->major->name }}
                                 </p>
                             @endif
                        </div>
                         <div class="mt-3 text-right">
                              <a href="{{ route('admin.subscriptions.show', ['profile' => $profile->id]) }}" wire:navigate
                                 class="text-sm font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                 View Details →
                              </a>
                         </div>
                    </div>
                </div>
            @endforeach
        </div>
     @else
        <div class="text-center py-12 px-4 bg-white dark:bg-gray-800 rounded-lg shadow">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"> <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /> </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No Profiles Found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No submitted profiles match the current filters.</p>
         </div>
     @endif


    {{-- Pagination Links --}}
    @if ($profiles->hasPages())
        <div class="mt-6">
            {{ $profiles->links() }}
        </div>
    @endif

</div>