<div x-data="{
    activeTab: 'current',
    showPastYearbookModal: false,
    fadeIn: true,
    init() {
        setTimeout(() => this.fadeIn = false, 10);
    }
}">
    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" 
             x-init="setTimeout(() => show = false, 5000)" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="mb-4 p-4 rounded-md bg-green-50 dark:bg-green-900/50 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200">
            {{ session('message') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div x-data="{ show: true }" 
             x-init="setTimeout(() => show = false, 5000)" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="mb-4 p-4 rounded-md bg-red-50 dark:bg-red-900/50 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        {{-- Header with Tabs --}}
        <div class="border-b border-gray-200 dark:border-gray-700">
            <div class="px-4 pt-5 sm:px-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                    My Yearbook Subscriptions
                </h2>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    Manage your yearbook subscriptions for current and past years.
                </p>
            </div>
            
            {{-- Tabs --}}
            <div class="px-4 sm:px-6 mt-3">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="activeTab = 'current'" 
                                :class="{'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'current', 
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'current'}"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition duration-150 ease-in-out focus:outline-none">
                            Current Year
                        </button>
                        <button @click="activeTab = 'past'" 
                                :class="{'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'past', 
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'past'}"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition duration-150 ease-in-out focus:outline-none">
                            Past Years
                        </button>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div class="px-4 py-5 sm:p-6 space-y-6">
            
            {{-- Current Year Tab Content --}}
            <div x-show="activeTab === 'current'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-2"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform translate-x-2">
                
                @if($activePlatform)
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $activePlatform->name }} ({{ $activePlatform->year }})
                        </h3>
                    </div>
                    
                    {{-- Overall Status Message Box --}}
                    <div class="p-4 rounded-md border
                        @if($paymentStatus === 'paid' && $hasSubmittedProfile) bg-green-50 border-green-300 dark:bg-green-900/50 dark:border-green-700
                        @elseif($paymentStatus === 'pending' || $paymentStatus === 'partial') bg-yellow-50 border-yellow-300 dark:bg-yellow-900/50 dark:border-yellow-700
                        @else bg-blue-50 border-blue-300 dark:bg-blue-900/50 dark:border-blue-700
                        @endif">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                {{-- Icon based on status --}}
                                @if($paymentStatus === 'paid' && $hasSubmittedProfile)
                                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                @elseif($paymentStatus === 'pending' || $paymentStatus === 'partial')
                                    <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                                @else
                                    <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                @endif
                            </div>
                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                <p class="text-sm font-medium
                                    @if($paymentStatus === 'paid' && $hasSubmittedProfile) text-green-800 dark:text-green-200
                                    @elseif($paymentStatus === 'pending' || $paymentStatus === 'partial') text-yellow-800 dark:text-yellow-200
                                    @else text-blue-800 dark:text-blue-200
                                    @endif">
                                    {{ $overallStatusMessage }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Detailed Status Grid --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            {{-- Profile Submitted Status --}}
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Profile Submitted</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">
                                    @if($hasSubmittedProfile)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100"> Yes </span>
                                        @if($profile?->submitted_at) <span class="ml-2 text-xs text-gray-500 dark:text-gray-400"> on {{ $profile->submitted_at->format('M d, Y') }}</span> @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100"> No </span>
                                    @endif
                                </dd>
                            </div>

                            {{-- Payment Status --}}
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Status</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">
                                    @if($paymentStatus === 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100"> Paid / Confirmed </span>
                                        @if($profile?->paid_at) <span class="ml-2 text-xs text-gray-500 dark:text-gray-400"> on {{ $profile->paid_at->format('M d, Y') }}</span> @endif
                                    @elseif($paymentStatus === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100"> Pending Confirmation </span>
                                    @elseif($paymentStatus === 'partial')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-700 dark:text-orange-100"> Partial Payment </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200"> Not Started </span>
                                    @endif
                                </dd>
                            </div>

                            {{-- Selected Package --}}
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Selected Package</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">
                                    @if($profile?->subscription_type === 'full_package')
                                        Full Package (₱2,300)
                                    @elseif($profile?->subscription_type === 'inclusions_only')
                                        Inclusions Only (₱1,500)
                                    @else
                                        {{ $profile?->subscription_type ? Str::title(str_replace('_', ' ', $profile->subscription_type)) : 'Not Selected' }}
                                    @endif
                                </dd>
                            </div>

                            {{-- Jacket Size --}}
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jacket Size</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $profile?->jacket_size ?? 'Not Selected' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Academic Info Status --}}
                    <div class="sm:col-span-2 border-t dark:border-gray-700 pt-4"> 
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Academic Info (College/Course)</dt>
                        <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">
                            @if($profile && $profile->college_id && $profile->course_id)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                    Complete
                                </span>
                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400"> ({{ $profile->college?->name ?? 'N/A' }} - {{ $profile->course?->name ?? 'N/A' }})</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100">
                                    Incomplete
                                </span>
                                <a href="{{ route('student.academic') }}" wire:navigate class="ml-2 text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium transition-colors duration-200">
                                    Go to Academic Area →
                                </a>
                            @endif
                        </dd>
                    </div>

                    {{-- Next Steps / Instructions Section --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-base font-medium text-gray-900 dark:text-gray-100">Next Steps:</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $nextStepMessage }}</p>

                        {{-- Link to Profile Form (if needed) --}}
                        @if(!$hasSubmittedProfile && $paymentStatus !== 'paid')
                            <div class="mt-4">
                                <a href="{{ route('student.profile.edit') }}" wire:navigate 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    Go to Yearbook Profile Form
                                </a>
                            </div>
                        @endif

                        {{-- Payment Instructions --}}
                        @if($paymentStatus === 'pending' && $hasSubmittedProfile)
                            <div class="mt-4 space-y-4">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">Payment Options:</p>

                                {{-- Option 1: Over-the-Counter --}}
                                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200">
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">1. Over-the-Counter Payment:</p>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Please visit the <strong class="font-semibold">[Specify Office Name/Location, e.g., School Publication Office]</strong> during office hours <strong class="font-semibold">([Specify Hours, e.g., 9 AM - 4 PM, Mon-Fri])</strong> to make your payment directly.
                                    </p>
                                </div>

                                {{-- Option 2: Bank Transfer --}}
                                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200">
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">2. Bank Transfer / Online Payment:</p>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        You can transfer the payment to the following account:
                                    </p>
                                    <ul class="mt-1 list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1 pl-2">
                                        <li><strong>Bank Name:</strong> [Specify Bank Name, e.g., BDO Unibank]</li>
                                        <li><strong>Account Name:</strong> [Specify Exact Account Name]</li>
                                        <li><strong>Account Number:</strong> [Specify Account Number]</li>
                                        <li><strong>Amount Due:</strong>
                                            @if($profile?->subscription_type === 'full_package') ₱2,300
                                            @elseif($profile?->subscription_type === 'inclusions_only') ₱1,500
                                            @else [Please contact office for amount]
                                            @endif
                                        </li>
                                    </ul>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        <strong class="font-semibold text-red-600 dark:text-red-400">Important:</strong> After transferring, please send a clear screenshot or photo of your proof of payment (transaction receipt) to the <strong class="font-semibold">[Specify Messenger Page/Contact Name, e.g., Official Blazer SOS FB Page Messenger]</strong>. Include your Full Name and Student ID Number in the message.
                                    </p>
                                </div>

                                {{-- Confirmation Note --}}
                                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        For both methods, your subscription status will be updated to "Paid / Confirmed" by an administrator once your payment has been verified. Please allow [Specify Timeframe, e.g., 1-2 working days] for verification after submitting proof via Messenger.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-200">No Active Yearbook Platform</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            There is currently no active yearbook platform for the current year.
                            Please check back later or contact the administrator.
                        </p>
                    </div>
                @endif
            </div>
            
            {{-- Past Years Tab Content --}}
            <div x-show="activeTab === 'past'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-2"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform translate-x-2">
                
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    My Past Yearbook Subscriptions
                </h3>
                
                {{-- Current subscriptions to past yearbooks --}}
                @if($userSubscriptions && $userSubscriptions->filter(fn($sub) => $sub->yearbookPlatform?->isPastYear())->count() > 0)
                    <div class="mb-6 overflow-hidden border dark:border-gray-700 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Year</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Platform</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subscription Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-800">
                                @foreach($userSubscriptions->filter(fn($sub) => $sub->yearbookPlatform?->isPastYear()) as $subscription)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $subscription->yearbookPlatform->year }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $subscription->yearbookPlatform->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($subscription->payment_status === 'paid')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                                    Paid
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $subscription->submitted_at ? $subscription->submitted_at->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($subscription->payment_status === 'pending')
                                                <button wire:click="cancelSubscription({{ $subscription->id }})" wire:confirm="Are you sure you want to cancel this subscription? This action cannot be undone."
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150 focus:outline-none">
                                                    Cancel
                                                </button>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">No actions</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 mb-6">You don't have any subscriptions to past yearbooks.</p>
                @endif
                
                {{-- Available past yearbooks for subscription --}}
                @if($pastYearbookPlatforms && $pastYearbookPlatforms->count() > 0)
                    <div class="border-t dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Available Past Yearbooks
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($pastYearbookPlatforms as $platform)
                                @if(!$userSubscriptions->contains('yearbook_platform_id', $platform->id))
                                    <div class="border dark:border-gray-700 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b dark:border-gray-700">
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $platform->name }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $platform->year }}</p>
                                        </div>
                                        <div class="p-4">
                                            @if($platform->theme_title)
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                                    <span class="font-medium">Theme:</span> {{ $platform->theme_title }}
                                                </p>
                                            @endif
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                                <span class="font-medium">Available Stock:</span> {{ $platform->stock->available_stock }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                                <span class="font-medium">Price:</span> ₱{{ number_format($platform->stock->price, 2) }}
                                            </p>
                                            <button wire:click="$set('selectedPlatformId', {{ $platform->id }}); subscribeToPastYearbook()"
                                                    wire:loading.attr="disabled"
                                                    wire:target="subscribeToPastYearbook"
                                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                                                <span wire:loading.remove wire:target="subscribeToPastYearbook">Subscribe</span>
                                                <span wire:loading wire:target="subscribeToPastYearbook">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Processing...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="border-t dark:border-gray-700 pt-6">
                        <p class="text-gray-500 dark:text-gray-400">No past yearbooks available for subscription at this time.</p>
                    </div>
                @endif
            </div>
            
        </div> {{-- End Main Content Area --}}
    </div>
</div>