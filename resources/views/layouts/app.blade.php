<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }" x-init="$watch('sidebarOpen', value => localStorage.setItem('sidebarOpen', value))" class="flex h-screen bg-white overflow-hidden">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navigation -->
                <!-- Top Navigation Removed -->

                <!-- Main Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-white">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white border-b border-gray-200">
                            <div class="py-2 px-6">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <div>
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
