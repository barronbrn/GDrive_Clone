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
  min-height: 100vh;
  margin: 0;
  background-image:
    url('/images/bg-mega-mendung.jpg'),
    linear-gradient(to bottom right, #0f172a, #1e3a8a, #1e40af);
  background-repeat: no-repeat, no-repeat;
  background-size: cover, cover;
  background-position: center, center;
  background-attachment: fixed; /* opsional, bikin efek parallax */
  background-blend-mode: overlay;
}


    .container-wrapper {
      position: relative;
      width: 100%;
      max-width: 800px;
      margin: auto;
      display: flex;
      justify-content: center;
      align-items: stretch;
      padding: 2rem 0;
      background: transparent;
    }

    .container-left,
    .container-right {
      width: 50%;
      background-color: white;
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .container-left {
      border-top-left-radius: 1rem;
      border-bottom-left-radius: 1rem;
      box-shadow: -4px 0 15px rgba(0, 0, 0, 0.1);
    }

    .container-left img {
      width: 360px; 
      height: auto;
    }

    .container-right {
      color: black;
      border-top-right-radius: 1rem;
      border-bottom-right-radius: 1rem;
      box-shadow: 4px 0 15px rgba(0, 0, 0, 0);
    }

    .error-text {
      font-size: 0.8rem;
      color: red;
      margin-top: 0.3rem;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
      .container-wrapper {
        flex-direction: column;
        max-width: 400px;
      }

      .container-left,
      .container-right {
        width: 100%;
        border-radius: 1rem;
        box-shadow: none;
      }

      .container-left img {
        width: 240px; /* Disesuaikan agar tetap proporsional di layar kecil */
      }
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
          <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium mb-1">Nama Lengkap</label>
              <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full bg-transparent border-b-2 border-pink-500 text-black placeholder-gray-500 focus:outline-none focus:border-pink-400" />
              @error('name')
                <p class="error-text">{{ $message }}</p>
              @enderror
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium mb-1">Email</label>
              <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="w-full bg-transparent border-b-2 border-pink-500 text-black placeholder-gray-500 focus:outline-none focus:border-pink-400" />
              @error('email')
                <p class="error-text">{{ $message }}</p>
              @enderror
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium mb-1">Password</label>
              <input id="password" type="password" name="password" required
                class="w-full bg-transparent border-b-2 border-purple-500 text-black placeholder-gray-500 focus:outline-none focus:border-purple-400" />
              @error('password')
                <p class="error-text">{{ $message }}</p>
              @enderror
            </div>

            <!-- Confirm Password -->
            <div>
              <label for="password_confirmation" class="block text-sm font-medium mb-1">Konfirmasi Password</label>
              <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full bg-transparent border-b-2 border-purple-500 text-black placeholder-gray-500 focus:outline-none focus:border-purple-400" />
            </div>

            <!-- Submit Button -->
            <div>
              <button type="submit"
                class="w-full py-2 px-4 bg-blue-700 text-white font-semibold rounded-md hover:bg-blue-800 transition">
                Daftar
              </button>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-3 text-sm">
              Sudah punya akun?
              <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">
                Login
              </a>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</body>
</html>
