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

  <!-- Custom Styles -->
  <style>
    body {
      /* Background gradasi tetap */
      background: linear-gradient(to bottom right, #0f172a, #1e3a8a, #1e40af);

      /* Tambahan motif Mega Mendung */
      background-image: 
        url('/images/bg-mega-mendung.jpg'),
        linear-gradient(to bottom right, #0f172a, #1e3a8a, #1e40af);

      background-repeat: repeat;
      background-size: 1420px, cover; /* Perbesar ukuran motif */
      background-blend-mode: overlay;
    }

    .container-wrapper {
      position: relative;
      width: 100%;
      max-width: 800px;
      height: 400px;
      margin: auto;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container-left,
    .container-right {
      position: relative;
      width: 100%;
      max-width: 400px;
      height: 100%;
      background-color: white;
      padding: 1.5rem;
    }

    .container-left {
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;

      border-top-left-radius: 1rem;
      border-bottom-left-radius: 1rem;
      box-shadow: -4px 0 15px rgba(0, 0, 0, 0.1);
    }

    .container-left img {
      width: 450px;
      height: auto;
      margin-bottom: 1rem;
    }

    .container-left p {
      text-align: center;
      color: #374151;
    }

    .container-right {
      background-color: white;
      color: black;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;

      border-top-right-radius: 1rem;
      border-bottom-right-radius: 1rem;
      box-shadow: 4px 0 15px rgba(0, 0, 0, 0);
    }
  </style>
</head>
<body class="font-sans antialiased text-black">
  <div class="min-h-screen flex items-center justify-center">
    <div class="container-wrapper">
      
      <!-- Kontainer Kiri -->
      <div class="container-left">
        <img src="/images/logo-bri.PNG" alt="Logo BRI">
      </div>

      <!-- Kontainer Kanan -->
      <div class="container-right">
        <div class="w-full max-w-sm">
          <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium mb-1">Email</label>
              <input id="email" type="email" name="email" required
                class="w-full bg-transparent border-b-2 border-pink-500 text-black placeholder-gray-500 focus:outline-none focus:border-pink-400" />
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium mb-1">Password</label>
              <input id="password" type="password" name="password" required
                class="w-full bg-transparent border-b-2 border-purple-500 text-black placeholder-gray-500 focus:outline-none focus:border-purple-400" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-sm">
              <label class="flex items-center">
                <input type="checkbox" name="remember" class="mr-2 rounded">
                Remember me
              </label>
              <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">
                Forgot password?
              </a>
            </div>

            <!-- Submit Button -->
            <div>
              <button type="submit"
                class="w-full py-2 px-4 bg-blue-700 text-white font-semibold rounded-md hover:bg-blue-800 transition">
                Login
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</body>
</html>
