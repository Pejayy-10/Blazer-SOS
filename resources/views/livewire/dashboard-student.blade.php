<div> {{-- The ONLY top-level element in this file --}}

    {{-- Include Header --}}
    <x-yearbook-header :platform="$activePlatform" />

    {{-- Student Warning Box --}}
    @auth
        @if($profile && $profile->profile_submitted && (!$profile->college_id || !$profile->course_id))
             <div class="mb-4 p-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-800/50 dark:text-yellow-300 border border-yellow-300 dark:border-yellow-600" role="alert">
                <svg class="inline flex-shrink-0 mr-3 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span class="font-medium">Action Required:</span> Please complete your academic information (College & Course) on the
                <a href="{{ route('student.academic') }}" wire:navigate class="font-semibold underline hover:text-yellow-800 dark:hover:text-yellow-200">Academic Area</a> page to finalize your profile submission.
            </div>
        @endif
    @endauth

    {{-- Include Bulletin --}}
    @livewire('bulletin')

</div> {{-- End the single root element --}}