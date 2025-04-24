<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Dynamically set title, fallback to app name --}}
    <title>{{ $title ?? config('app.name', 'Blazer SOS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles & Scripts -->
    {{-- Include compiled CSS/JS via Vite (ensure app.css includes Tailwind, app.js includes Alpine) --}}
    @vite(['resources/css/app.css'])
    @livewireStyles {{-- Include Livewire default styles --}}

    <style>
        /* Base body background (can be adjusted) */
        body {
            background-color: #f3f4f6; /* Light Gray */
            /* Or use your dark red if the whole app background should be dark: */
            /* background-color: #5F0104; */
        }
        /* Ensure main content area can scroll independently and takes available height */
        /* Adjust 16 (h-16) based on your actual top nav height if it changes */
        .main-content-area {
             height: calc(100vh - theme('spacing.16'));
        }
         /* Basic scrollbar styling for better aesthetics with dark theme (optional) */
        ::-webkit-scrollbar { width: 8px; height: 8px;}
        ::-webkit-scrollbar-track { background: #e5e7eb; border-radius: 10px; } /* Light gray track */
        ::-webkit-scrollbar-thumb { background: #9A382F; border-radius: 10px; } /* Navbar red thumb */
        ::-webkit-scrollbar-thumb:hover { background: #5F0104; } /* Sidebar red thumb on hover */

        /* Styles for when dark mode is preferred for the main content area */
        .dark body { /* Example if you add dark mode toggle later */
             background-color: #1f2937; /* Dark Gray */
        }
        .dark ::-webkit-scrollbar-track { background: #374151; } /* Darker gray track */
        .dark ::-webkit-scrollbar-thumb { background: #9A382F; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #5F0104; }

    </style>
</head>
<body class="font-sans antialiased">
    {{-- Main container using flexbox --}}
    <div class="min-h-screen flex bg-gray-100 dark:bg-gray-900"> {{-- Adjust background based on light/dark preference --}}

        <!-- ========== MAIN SIDEBAR ========== -->
        <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-[#5F0104] text-white flex flex-col shadow-lg"> {{-- Sidebar --}}
            {{-- Sidebar Header (Logo) --}}
            <div class="px-6 py-4 border-b border-white/10 flex items-center justify-start">
                 <a href="{{ route('app.dashboard') }}" wire:navigate class="flex items-center">
                    {{-- Make sure logo path is correct in public/images --}}
                    <img src="{{ asset('images/placeholder-logo.png') }}" alt="Blazer SOS Logo" class="h-8 w-auto mr-2">
                    <span class="text-xl font-semibold">Blazer SOS</span>
                </a>
            </div>

            {{-- Sidebar Navigation --}}
            <nav class="flex-grow p-4 space-y-1 overflow-y-auto">
                {{-- Use @auth directive to ensure user is logged in --}}
                @auth
                    {{-- Home/Bulletin Link (Always Visible when logged in) --}}
                    <a href="{{ route('app.dashboard') }}" wire:navigate
                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-[#9A382F]/70 transition-colors duration-150 group
                              {{ request()->routeIs('app.dashboard') ? 'bg-[#9A382F]' : '' }}">
                        {{-- Heroicon: home --}}
                        <svg class="h-5 w-5 mr-3 text-white/80 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                        Home / Bulletin
                    </a>

                    {{-- Role-Based Sidebar Sections - Check ACTUAL user role --}}
                    @if(in_array(Auth::user()->role, ['admin', 'superadmin']))
                        {{-- Admin Tools Section --}}
                        <span class="px-3 pt-4 pb-1 block text-xs font-semibold text-white/50 uppercase tracking-wider">Admin Tools</span>
                         {{-- Yearbook Platforms --}}
                        <a href="{{ route('admin.platforms.index') }}" wire:navigate {{-- Update href & route name --}}
                         class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-[#9A382F]/70 transition-colors duration-150 group
                                {{ request()->routeIs('admin.platforms.index') ? 'bg-[#9A382F]' : '' }}"> {{-- Update route name check --}}
                           {{-- Heroicon: cog --}}
                           <svg class="h-5 w-5 mr-3 text-white/80 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.566.379-1.566 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.566 2.6 1.566 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.566-.379 1.566-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                          Yearbook Platforms
                        </a>
                         {{-- Academic Structure --}}
                         <a href="{{ route('admin.academic-structure.index') }}" wire:navigate {{-- Update href & route name --}}
                            class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-[#9A382F]/70 transition-colors duration-150 group
                                   {{ request()->routeIs('admin.academic-structure.index') ? 'bg-[#9A382F]' : '' }}"> {{-- Update route name check --}}
                             <svg class="h-5 w-5 mr-3 text-white/80 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.998.998 0 011.07.019l3.398 2.017a.998.998 0 01.264 1.408l-1.206 2.035a.998.998 0 00.447 1.391l3.086 1.833a1 1 0 001.18-.001l3.086-1.833a.998.998 0 00.447-1.391l-1.206-2.035a.998.998 0 01.264-1.408l3.398-2.017a.998.998 0 011.07-.019L19.5 6.92a1 1 0 000-1.84l-7-3zM5 8.731l-1.12-.658L10 5.08l6.12 2.993L15 8.731l-5 2.953-5-2.953z" /><path d="M10 12.414l-3.122 1.84L10 16.182l3.122-1.928L10 12.414zM5 11.08l1.12 2.12 3.38 2a1 1 0 001 0l3.38-2L15 11.08l-5 2.952-5-2.952z" /></svg>
                             Academic Structure
                         </a>
                        {{-- Subscriptions --}}
                        <a href="{{ route('admin.subscriptions.index') }}" wire:navigate {{-- Update href and uncomment wire:navigate --}}
                        class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-[#9A382F]/70 transition-colors duration-150 group
                               {{ request()->routeIs('admin.subscriptions.index') ? 'bg-[#9A382F]' : '' }}"> {{-- Update route name check --}}
                          {{-- Heroicon: document-text --}}
                          <svg class="h-5 w-5 mr-3 text-white/80 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                         Subscriptions
                        </a>
                        {{-- Yearbook Repository --}}
                        <a href="{{ route('admin.repository.index') }}" wire:navigate {{-- Update href & route name --}}
                         class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-[#9A382F]/70 transition-colors duration-150 group
                                 {{ request()->routeIs('admin.repository.index') ? 'bg-[#9A382F]' : '' }}"> {{-- Update route check --}}
                           {{-- Heroicon: archive --}}
                           <svg class="h-5 w-5 mr-3 text-white/80 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" /><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" /></svg>
                          Yearbook Repository
                        </a>
                    @endif

                    {{-- Superadmin Only Section - Check ACTUAL user role --}}
                    @if(Auth::user()->role === 'superadmin')
                         <span class="px-3 pt-4 pb-1 block text-xs font-semibold text-white/50 uppercase tracking-wider">System Config</span>
                         {{-- Staff Management --}}
                         <a href="{{ route('superadmin.staff.index') }}" wire:navigate
                            class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-[#9A382F]/70 transition-colors duration-150 group {{ request()->routeIs('superadmin.staff.index') ? 'bg-[#9A382F]' : '' }}">
                            {{-- Heroicon: users --}}
                            <svg class="h-5 w-5 mr-3 text-white/80 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015.5-4.96A5 5 0 0111 16v1H1v-1a5 5 0 015-5z" /></svg>
                            Staff Management
                         </a>
                         <a href="{{ route('superadmin.roles.index') }}" wire:navigate
                            class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-[#9A382F]/70 transition-colors duration-150 group {{ request()->routeIs('superadmin.roles.index') ? 'bg-[#9A382F]' : '' }}">
                            {{-- Heroicon: tag --}}
                            <svg class="h-5 w-5 mr-3 text-white/80 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5a.997.997 0 01.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                            Manage Role Names
                         </a>
                         {{-- Add other superadmin links like Role Name Management here --}}
                    @endif

                    {{-- Student Section - Check ACTUAL user role --}}
                    @if(Auth::user()->role === 'student')
                         <span class="px-3 pt-4 pb-1 block text-xs font-semibold text-white/50 uppercase tracking-wider">My Yearbook</span>
                         {{-- Academic Area --}}
                         <a href="{{ route('student.academic') }}" wire:navigate {{-- Update href & uncomment wire:navigate --}}
                            class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-[#9A382F]/70 transition-colors duration-150 group
                                   {{ request()->routeIs('student.academic') ? 'bg-[#9A382F]' : '' }}"> {{-- Update route check --}}
                             {{-- Heroicon: library (alternative) or academic-cap --}}
                             <svg class="h-5 w-5 mr-3 text-white/80 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.496 2.132a1 1 0 00-.992 0l-7 4A1 1 0 003 7v10a1 1 0 00.252.658l4 3a1 1 0 001.496 0l4-3A.998.998 0 0013 17V7a1 1 0 00-.496-.868l-7-4zM6 9a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm.25 6.002l-.52.288V12.75l.52-.288a1.001 1.001 0 00-.004-1.738l-.516-.286v-1.37l.516.286a1 1 0 00.992 0l.516-.286v1.37l-.516.286a1 1 0 00.004 1.738l.52.288v2.538l-.52-.288a1 1 0 00-.992 0z" clip-rule="evenodd" /></svg>
                            Academic Area
                         </a>
                         {{-- Yearbook Profile --}}
                         <a href="{{ route('student.profile.edit') }}" wire:navigate {{-- Use correct route name and uncomment wire:navigate --}}
                         class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-[#9A382F]/70 transition-colors duration-150 group
                                {{ request()->routeIs('student.profile.edit') ? 'bg-[#9A382F]' : '' }}"> {{-- Also update active state check --}}
                           {{-- Heroicon: identification --}}
                           <svg class="h-5 w-5 mr-3 text-white/80 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm12 4a1 1 0 100-2H4a1 1 0 100 2h12zM4 10a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd" /></svg>
                          Yearbook Profile
                          </a>
                        {{-- Photos --}}
                         <a href="{{ route('student.photos') }}" wire:navigate {{-- Update href & uncomment wire:navigate --}}
                         class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-[#9A382F]/70 transition-colors duration-150 group
                                {{ request()->routeIs('student.photos') ? 'bg-[#9A382F]' : '' }}"> {{-- Update route name check --}}
                           {{-- Heroicon: photograph --}}
                           <svg class="h-5 w-5 mr-3 text-white/80 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" /></svg>
                          Photos
                        </a>
                        {{-- Subscription Status --}}
                         <a href="{{ route('student.subscription.status') }}" wire:navigate {{-- Update href & route name --}}
                         class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-[#9A382F]/70 transition-colors duration-150 group
                                {{ request()->routeIs('student.subscription.status') ? 'bg-[#9A382F]' : '' }}"> {{-- Update route name check --}}
                           {{-- Heroicon: check-circle --}}
                           <svg class="h-5 w-5 mr-3 text-white/80 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                          Subscription Status
                        </a>
                    @endif
                @else {{-- If not authenticated --}}
                     <p class="px-3 py-2 text-sm text-white/70">Please log in to see navigation.</p>
                @endauth {{-- End @auth check --}}
            </nav>

            {{-- Sidebar Footer --}}
             <div class="p-4 mt-auto border-t border-white/10">
                 @auth {{-- Show only if logged in --}}
                     <div class="text-xs text-white/60">
                         {{-- Display actual user data --}}
                         Role: <span class="font-medium text-white/80">{{ ucfirst(Auth::user()->role) }}</span>
                         <span class="mx-1">|</span>
                         User: <span class="font-medium text-white/80">{{ Auth::user()->username }}</span>
                     </div>
                 @else {{-- Show if logged out --}}
                     <div class="text-xs text-white/60">
                        Status: Not Logged In
                     </div>
                 @endauth
             </div>
        </aside>
        <!-- ========== END MAIN SIDEBAR ========== -->

        <!-- ========== CONTENT WRAPPER ========== -->
        {{-- Needs margin-left matching sidebar width (w-64 -> ml-64) --}}
        <div class="ml-64 flex-grow flex flex-col">

             <!-- ========== TOP NAVIGATION BAR ========== -->
             <header class="sticky top-0 z-20 bg-[#9A382F] text-white h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 shadow">
                 {{-- Left Side: Current Page Title --}}
                 <div>
                     <h1 class="text-lg font-semibold">
                         {{ $title ?? 'Dashboard' }} {{-- Title from the page component --}}
                     </h1>
                 </div>

                 {{-- Right Side: Profile Dropdown --}}
                 @auth {{-- Show dropdown only if logged in --}}
                     <div class="flex items-center space-x-4">
                         <!-- Profile dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            {{-- Dropdown Button (User Initials) --}}
                            <button @click="open = ! open" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-white transition">
                                {{-- Generate initials from actual user first_name and last_name --}}
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-[#5F0104] ring-1 ring-white/50 text-xs font-medium text-white uppercase">
                                    {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                                </span>
                            </button>

                            {{-- Dropdown Panel --}}
                            <div x-show="open"
                                 @click.outside="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none origin-top-right"
                                 style="display: none;"> <!-- style="display: none;" is important for Alpine initial state -->

                                {{-- Dropdown Links --}}
                                <a href="{{ route('user.profile') }}" wire:navigate {{-- Update route --}}
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile
                                </a>
                                <a href="{{ route('user.account.settings') }}" wire:navigate {{-- Update route and add wire:navigate --}}
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Account
                                </a>
                                <hr class="border-gray-200 my-1">

                                 {{-- *** USE STANDARD LOGOUT ROUTE *** --}}
                                 <form method="POST" action="{{ route('logout') }}">
                                     @csrf
                                     <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                         Log Out
                                     </button>
                                 </form>
                            </div>
                        </div>
                     </div>
                 @endauth
             </header>
             <!-- ========== END TOP NAVIGATION BAR ========== -->

             <!-- ========== MAIN CONTENT AREA ========== -->
             {{-- Takes remaining space, provides padding, and allows scrolling --}}
             <main class="flex-grow p-4 sm:p-6 lg:p-8 overflow-y-auto main-content-area">
                {{ $slot }} {{-- Where the Livewire page component content renders --}}
             </main>
             <!-- ========== END MAIN CONTENT AREA ========== -->

        </div>
        <!-- ========== END CONTENT WRAPPER ========== -->

    </div> {{-- End Main Flex Container --}}

    @livewireScripts {{-- Include Livewire scripts --}}
    {{-- Alpine JS is usually included via resources/js/app.js setup by Vite --}}
</body>
</html>