<x-guest-layout>
    <h2 class="text-3xl font-bold text-center text-white mb-2">Login to Your Account</h2>
    <p class="text-center text-gray-300 mb-8">Welcome Back!</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="e.g., user@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-bri-blue shadow-sm focus:ring-bri-blue" name="remember">
                <span class="ml-2 text-sm text-gray-300">{{ __('Remember me') }}</span>
            </label>

            {{-- @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-300 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bri-blue" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif --}}
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="w-full justify-center">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="text-center mt-4 text-sm text-gray-400">
            Don't Have an Account? 
            <a href="{{ route('register') }}" class="underline hover:text-white font-medium">
                Daftar
            </a>
        </div>
    </form>
</x-guest-layout>uest-layout>