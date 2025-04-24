<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title> <!-- Page Title -->

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- Include compiled CSS/JS via Vite -->
    @livewireStyles <!-- Include Livewire Styles -->

    <style>
        /* Optional: Add base styles here if needed */
        body {
            background-color: #5F0104; /* Base background color */
        }
    </style>

</head>
{{-- REMOVE flex classes from body if they were here --}}
<body class="font-sans antialiased">

    {{-- The Livewire component will now handle its own internal flex layout --}}
    {{ $slot }}

    @livewireScripts
</body>
</html>