<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bri.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>
<body class="font-sans antialiased text-gray-900">
    <div class="bg-image-container"></div>
    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        <div class="w-full max-w-4xl mx-auto animate-container">
            <div class="bg-white/95 backdrop-blur-sm shadow-2xl rounded-2xl overflow-hidden grid md:grid-cols-2">
                <!-- Left Side: Branding -->

                <div class="p-12 bg-white text-gray-800 flex flex-col justify-center items-center text-center relative stagger-children">
                    <div class="relative z-10">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome!</h2>
                        <a href="/" class="inline-block mb-2">
                            <img src="{{ asset('images/logo-bri.png') }}" alt="DataBOX Logo" class="w-64 h-auto">
                        </a>
                        <p class="text-gray-600">A secure and reliable cloud storage solution from BRI.</p>                    </div>

                </div>

                <!-- Right Side: Form -->
                <div class="p-12 bg-blue-900 text-white stagger-children" style="--delay: 0.2s;">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>