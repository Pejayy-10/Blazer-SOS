<div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        {{-- Header --}}
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                My Yearbook Subscription Status
            </h2>
             <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                 Check the current status of your profile submission and payment.
             </p>
        </div>

        {{-- Main Content Area --}}
        <div class="px-4 py-5 sm:p-6 space-y-6">

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
                            {{-- Heroicon: check-circle (solid) --}}
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        @elseif($paymentStatus === 'pending' || $paymentStatus === 'partial')
                             {{-- Heroicon: clock (solid) --}}
                             <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                        @else
                             {{-- Heroicon: information-circle (solid) --}}
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
                        {{-- Optional: Add a link here if needed --}}
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                    Yes
                                </span>
                                @if($profile?->submitted_at)
                                    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400"> on {{ $profile->submitted_at->format('M d, Y') }}</span>
                                @endif
                            @else
                                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">
                                     No
                                 </span>
                            @endif
                         </dd>
                     </div>

                     {{-- Payment Status --}}
                     <div class="sm:col-span-1">
                         <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Status</dt>
                         <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">
                             @if($paymentStatus === 'paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                    Paid / Confirmed
                                </span>
                                 @if($profile?->paid_at)
                                    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400"> on {{ $profile->paid_at->format('M d, Y') }}</span>
                                @endif
                             @elseif($paymentStatus === 'pending')
                                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100">
                                     Pending Confirmation
                                 </span>
                            @elseif($paymentStatus === 'partial')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-700 dark:text-orange-100">
                                    Partial Payment
                                </span>
                             @else {{-- 'not_started' or other --}}
                                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                      Not Started
                                  </span>
                             @endif
                         </dd>
                     </div>
                 </dl>
            </div>

             {{-- Next Steps / Instructions Section --}}
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                 <h3 class="text-base font-medium text-gray-900 dark:text-gray-100">Next Steps</h3>
                 <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $nextStepMessage }}</p>

                 {{-- Add relevant links based on status --}}
                 @if(!$hasSubmittedProfile && $paymentStatus !== 'paid')
                      <div class="mt-4">
                         <a href="{{ route('student.profile.edit') }}" wire:navigate class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                             Go to Yearbook Profile Form
                         </a>
                     </div>
                 @endif
                 {{-- Add payment instructions link/info here if needed --}}
                 @if($paymentStatus === 'pending' && $hasSubmittedProfile)
                      <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                         <p class="text-sm text-gray-700 dark:text-gray-300">
                            Please visit the [Specify Office Name/Location] during office hours to make your payment. Your status will be updated by an administrator once verified.
                         </p>
                      </div>
                 @endif

            </div>

        </div> {{-- End Main Content Area --}}
    </div>
</div>