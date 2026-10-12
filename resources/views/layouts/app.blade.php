<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body>
        <div class="app-layout" x-data="{ sidebarOpen: false }">
            <x-navigation.sidebar :role="Auth::check() ? Auth::user()->role : 'guest'" />

            <div class="app-main-content">
                @if (isset($header))
                    {{ $header }}
                @endif
                
                <main class="app-content-area animate-fade-in">
                    {{ $slot }}
                </main>
            </div>
        </div>
        <x-ui.toast-notifications />
        @stack('scripts')
    </body>
</html>
