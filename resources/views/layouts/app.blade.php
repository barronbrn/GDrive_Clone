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
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">



        

            <body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white flex flex-col p-6 border-r border-gray-200">
            <div class="flex items-center space-x-3 mb-10">
                <div class="p-2 bg-[#00529B] rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2-2 0 012-2m14 0V9a2-2 0 00-2-2M5 11V9a2-2 0 012-2m0 0l3-3m0 0l3 3m-3-3v12">
                        </path>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-[#00529B]">DataBOX</span>
            </div>

            <nav class="mt-10 flex-1">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="block p-3 rounded-lg transition-colors
                            {{ request()->routeIs('dashboard*') ? 'text-[#00529B] font-semibold bg-blue-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('recent') }}"
                            class="block p-3 rounded-lg transition-colors
                            {{ request()->routeIs('recent') ? 'text-[#00529B] font-semibold bg-blue-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Recent
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('trash') }}"
                            class="block p-3 rounded-lg transition-colors
                            {{ request()->routeIs('trash') ? 'text-[#00529B] font-semibold bg-blue-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Trash
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.edit') }}"
                            class="block p-3 rounded-lg transition-colors
                            {{ request()->routeIs('profile.edit') ? 'text-[#00529B] font-semibold bg-blue-100' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Profile
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col">
            <!-- Header biru -->
            @if (isset($header))
                <header class="">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                            {{ $header }}
                        <!-- User dropdown pindahkan ke sini -->
                        @include('layouts.navigation')
                    </div>
                </header>
            <!-- Breadcrumb -->
           <!-- Breadcrumb -->
                <nav class="text-sm text-gray-500 mt-2 ml-8" aria-label="Breadcrumb">
                    <ol class="list-reset flex">
                        <li>
                            <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                        </li>
                        <li><span class="mx-2">/</span></li>
                        <li class="text-sm text-gray-500  font-medium">Profile</li>
                    </ol>
                </nav>

            @endif

            <!-- Konten -->
            <main class="p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
