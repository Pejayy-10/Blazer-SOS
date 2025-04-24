<div>
    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('admin.subscriptions.index') }}" wire:navigate
           class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            <svg class="h-5 w-5 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            Back to Subscriptions
        </a>
    </div>

    {{-- Main Content Card --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
             <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                 Subscription Details: {{ $profile->user->first_name }} {{ $profile->user->last_name }} ({{ $profile->user->username }})
             </h3>
             <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                 Detailed yearbook profile information.
             </p>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200 dark:sm:divide-gray-700">

                {{-- Basic Info Section --}}
                <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $profile->user->first_name }} {{ $profile->user->last_name }}</dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Username</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $profile->user->username }}</dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $profile->user->email }}</dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nickname</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $profile->nickname ?? 'N/A' }}</dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Age / Birth Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                        {{ $profile->age ?? 'N/A' }}
                        @if($profile->birth_date)
                         ({{ $profile->birth_date->format('M d, Y') }})
                        @endif
                    </dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $profile->contact_number ?? 'N/A' }}</dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2 whitespace-pre-wrap">{{ $profile->address ?? 'N/A' }}</dd> {{-- whitespace-pre-wrap preserves line breaks --}}
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Parents</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                        Mother: {{ $profile->mother_name ?? 'N/A' }} <br>
                        Father: {{ $profile->father_name ?? 'N/A' }}
                    </dd>
                </div>

                {{-- Academic Section --}}
                <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">College</dt>
                    {{-- CORRECTED DISPLAY --}}
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                        {{ $profile->college?->name ?? ($profile->college_id ? 'College ID: '.$profile->college_id : 'N/A') }}
                        {{ $profile->college?->abbreviation ? "({$profile->college->abbreviation})" : '' }}
                    </dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Course</dt>
                    {{-- CORRECTED DISPLAY --}}
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                        {{ $profile->course?->name ?? ($profile->course_id ? 'Course ID: '.$profile->course_id : 'N/A') }}
                        {{ $profile->course?->abbreviation ? "({$profile->course->abbreviation})" : '' }}
                    </dd>
                </div>
                 {{-- Display Major (Added) --}}
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Major</dt>
                    {{-- CORRECTED DISPLAY --}}
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                        {{ $profile->major?->name ?? ($profile->major_id ? 'Major ID: '.$profile->major_id : 'N/A') }}
                    </dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Year & Section</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $profile->year_and_section ?? 'N/A' }}</dd>
                </div>

                 {{-- Yearbook Content Section --}}
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Affiliations</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                        <ul class="list-disc list-inside">
                            @if($profile->affiliation_1) <li>{{ $profile->affiliation_1 }}</li> @endif
                            @if($profile->affiliation_2) <li>{{ $profile->affiliation_2 }}</li> @endif
                            @if($profile->affiliation_3) <li>{{ $profile->affiliation_3 }}</li> @endif
                            @if(!$profile->affiliation_1 && !$profile->affiliation_2 && !$profile->affiliation_3) N/A @endif
                        </ul>
                    </dd>
                </div>
                <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Awards</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2 whitespace-pre-wrap">{{ $profile->awards ?? 'N/A' }}</dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mantra / Quote</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2 whitespace-pre-wrap">{{ $profile->mantra ?? 'N/A' }}</dd>
                </div>

                 {{-- Status & Options Section --}}
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Profile Submitted</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                        {{ $profile->profile_submitted ? 'Yes' : 'No' }}
                        @if($profile->submitted_at) ({{ $profile->submitted_at->format('M d, Y H:i') }}) @endif
                    </dd>
                 </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $profile->payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100' : ($profile->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200') }}">
                            {{ ucfirst($profile->payment_status) }}
                        </span>
                        @if($profile->paid_at) ({{ $profile->paid_at->format('M d, Y H:i') }}) @endif
                    </dd>
                 </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Selected Package</dt> {{-- Renamed Label --}}
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                        @if($profile->subscription_type === 'full_package')
                            Full Package (₱2,300)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Includes profile spot in dividers & physical yearbook copy.</p>
                        @elseif($profile->subscription_type === 'inclusions_only')
                            Inclusions Only (₱1,500)
                             <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Includes profile spot in dividers (No physical copy).</p>
                        @else
                             {{ $profile->subscription_type ?? 'N/A' }}
                        @endif
                    </dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jacket Size</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $profile->jacket_size ?? 'N/A' }}</dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Internal Contact</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $profile->active_contact_number_internal ?? 'N/A' }}</dd>
                </div>
                 <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Record Created</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $profile->created_at->format('M d, Y H:i') }}</dd>
                </div>
                <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $profile->updated_at->diffForHumans() }}</dd>
                </div>

            </dl>
        </div>
    </div>
</div>