<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ route('file.index') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <svg viewBox="0 0 629 483" xmlns="http://www.w3.org/2000/svg" class="h-16 w-auto bg-gray-800 dark:bg-gray-200"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path fill="#FF2D20" d="M320 0c-17.7 0-32 14.3-32 32V64H256c-17.7 0-32 14.3-32 32v32H192c-17.7 0-32 14.3-32 32v32H128c-17.7 0-32 14.3-32 32v32H64c-17.7 0-32 14.3-32 32v32H0v64H64c17.7 0 32-14.3 32-32v-32h32c17.7 0 32-14.3 32-32v-32h32c17.7 0 32-14.3 32-32v-32h32c17.7 0 32-14.3 32-32V96h32c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM565.3 274.7c-19.5-19.5-45.3-30.4-73-30.4s-53.5 10.9-73 30.4l-16.6 16.6c-1.5 1.5-2.4 3.5-2.4 5.6V376c0 13.3 10.7 24 24 24h32c13.3 0 24-10.7 24-24v-42.7l16.6-16.6c1.5-1.5 3.5-2.4 5.6-2.4h42.7c13.3 0 24-10.7 24-24V274.7zM496 160c-17.7 0-32 14.3-32 32v32h-32c-17.7 0-32 14.3-32 32v32h-32c-17.7 0-32 14.3-32 32v32h-32c-17.7 0-32 14.3-32 32v32h-32v64h32c17.7 0 32-14.3 32-32v-32h32c17.7 0 32-14.3 32-32v-32h32c17.7 0 32-14.3 32-32v-32h32c17.7 0 32-14.3 32-32V192h32c17.7 0 32-14.3 32-32V160h-64zM629 352v64h-64c-17.7 0-32 14.3-32 32v32h-32c-17.7 0-32 14.3-32 32v32H352v-64h64c17.7 0 32-14.3 32-32v-32h32c17.7 0 32-14.3 32-32v-32h32c17.7 0 32-14.3 32-32V352h64zM352 483v-64h-64c-17.7 0-32 14.3-32 32v32h-32c-17.7 0-32 14.3-32 32v32H128v-64h64c17.7 0 32-14.3 32-32v-32h32c17.7 0 32-14.3 32-32v-32h32c17.7 0 32-14.3 32-32V419h32c17.7 0 32-14.3 32-32V387h-64z" class="fill-current text-gray-500"/></svg>
                </div>

                <div class="mt-16">
                    <div class="flex justify-center text-gray-600 dark:text-gray-400">
                        <div class="text-sm">
                            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>