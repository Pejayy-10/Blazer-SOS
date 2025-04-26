@props(['platform' => null])

@php
    // Define default styles or logic here if needed, but keep it minimal in the component view itself
    $componentId = 'yearbook-header-' . uniqid(); // Example for unique IDs if needed later
@endphp

@if($platform)
    {{-- Root Element when platform IS available --}}
    <div class="relative w-full rounded-lg shadow-lg overflow-hidden mb-6 bg-[#2C0E0C]" style="aspect-ratio: 16 / 6;">

        {{-- Background & Overlay Wrapper --}}
        <div class="absolute inset-0 z-0">
            @if($platform->backgroundImageUrl)
                <img src="{{ $platform->backgroundImageUrl }}" alt="{{ $platform->name }} Background" class="w-full h-full object-cover opacity-40">
            @else
                {{-- Optional Placeholder Pattern --}}
                {{-- <div class="absolute inset-0 bg-pattern-dots opacity-10"></div> --}}
            @endif
            {{-- Darkening Overlay --}}
            <div class="absolute inset-0 bg-black opacity-20"></div>
        </div>

        {{-- Edit Image Button (Top Right, check permissions) --}}
        @auth
            @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                <button
                    type="button"
                    {{-- Dispatch event to open the separate upload modal component --}}
                    wire:click="$dispatch('open-background-upload-modal', { platformId: {{ $platform->id }} })"
                    class="absolute top-3 right-3 z-20 p-1.5 bg-black/40 text-white rounded-full hover:bg-black/60 focus:outline-none focus:ring-2 focus:ring-white"
                    title="Change Background Image">
                    {{-- Heroicon: photograph --}}
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" /></svg>
                </button>
            @endif
        @endauth

        {{-- Main Content Area --}}
        <div class="relative z-10 p-4 sm:p-6 md:p-8 h-full flex flex-col justify-between text-white"> 

             {{-- Top Section: Year/Theme --}}
             <div class="text-center mb-2 md:mb-4"> 
                <p class="text-xs sm:text-sm font-medium tracking-widest uppercase text-yellow-300/80 mb-1">YEARBOOK {{ $platform->year }}</p>
                @if($platform->theme_title)
                   <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-yellow-100/95 tracking-tight leading-tight" style="font-family: serif;"> 
                        @if(str_contains($platform->theme_title, ' - '))
                           <span>{{ Str::before($platform->theme_title, ' - ') }}</span><br class="hidden sm:block"> 
                           <span class="text-base sm:text-lg md:text-xl lg:text-2xl font-normal opacity-80">{{ Str::after($platform->theme_title, ' - ') }}</span>
                        @else
                            {{ $platform->theme_title }}
                        @endif
                    </h1>
                @endif
           </div>

            {{-- Bottom Section: Details & Status --}}
            <div class="flex flex-col space-y-3 sm:flex-row sm:justify-between sm:items-end sm:space-y-0"> 
                {{-- Left Side: Platform/School Info --}}
                <div class="text-left">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-white">{{ $platform->name }}</h2>
                    <p class="text-xs sm:text-sm text-gray-200/90">College Platform</p>
                    <p class="text-xs text-gray-300/80 mt-1">Western Mindanao State University</p>
                    <p class="text-xs text-gray-300/80">Baliwasan, Zamboanga City, Zamboanga del Sur</p>
               </div>
                {{-- Right Side: Status Badge --}}
                <div class="flex-shrink-0 self-center sm:self-end"> 
                    <span @class([
                       'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium shadow', // Base badge styles
                       'bg-gray-200 text-gray-800' => $platform->status === 'setup' || $platform->status === 'archived',
                       'bg-green-200 text-green-900' => $platform->status === 'open',
                       'bg-red-200 text-red-900' => $platform->status === 'closed',
                       'bg-blue-200 text-blue-900' => $platform->status === 'printing',
                   ])>
                        {{ ucfirst($platform->status) }}
                    </span>
                </div>
           </div> {{-- End Bottom Section Flex --}}

       </div> {{-- End Main Content Area --}}
    </div> {{-- End Root Element (Platform Exists) --}}

@else

    {{-- Root Element when platform is NULL --}}
    <div class="mb-6 p-4 text-center text-sm text-gray-500 bg-gray-100 dark:bg-gray-800 rounded-lg shadow">
        No active yearbook platform is currently set. Please contact an administrator.
    </div>

@endif