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
<body class="font-sans text-white antialiased bg-gradient-to-br from-[#0f172a] via-[#1e3a8a] to-[#1e40af]">
    <div class="min-h-screen flex items-center justify-center">
        <div class="flex w-full max-w-4xl bg-white/10 backdrop-blur-md border border-white/30 shadow-xl rounded-xl overflow-hidden">
            
            <!-- Left Container -->
            <div class="w-1/2 bg-white flex flex-col justify-center items-center px-10 py-12">
                <h1 class="text-4xl font-bold text-black mb-4">Login</h1>
                <p class="text-gray-700 text-center text-base">
                    By logging in you agree to the ridiculously long terms that you didn’t bother to read.
                </p>
            </div>

            <!-- Right Container -->
            <div class="w-1/2 bg-gray-900 flex flex-col justify-center px-10 py-12">
                <form method="POST" action="{{ route('login') }}" class="space-y-6 text-white">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                        <input id="email" type="email" name="email" required
                            class="w-full bg-transparent border-b-2 border-pink-500 text-white placeholder-gray-400 focus:outline-none focus:border-pink-400" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium mb-1">Password</label>
                        <input id="password" type="password" name="password" required
                            class="w-full bg-transparent border-b-2 border-red-500 text-white placeholder-gray-400 focus:outline-none focus:border-red-400" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="mr-2 rounded">
                            Remember me
                        </label>
                        <a href="{{ route('password.request') }}" class="text-blue-300 hover:underline">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
                            Login
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</body>
</html>