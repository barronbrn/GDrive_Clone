<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f0f4f8; /* Fallback color */
        }
        .bg-image-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-image: url('{{ asset('images/bg-mega-mendung.jpg') }}');
            background-size: cover;
            background-position: center;
            filter: brightness(70%);
            z-index: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-container {
            animation: fadeInUp 0.7s ease-out forwards;
        }

        .stagger-children > * {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
            animation-delay: var(--delay, 0s);
        }
    </style>
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