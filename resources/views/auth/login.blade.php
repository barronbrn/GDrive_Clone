<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
<div>
    <x-input-label for="email" :value="__('Email')" class="text-white" />
    <x-text-input 
        id="email" 
        class="block mt-1 w-full rounded-lg border border-white bg-white text-gray-900 placeholder-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-400 shadow-inner transition duration-300" 
        type="email" 
        name="email" 
        :value="old('email')" 
        required 
        autofocus 
        autocomplete="username" 
        placeholder="Enter your email"
    />
    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
</div>

<!-- Password -->
<div class="mt-4">
    <x-input-label for="password" :value="__('Password')" class="text-white" />

    <x-text-input 
        id="password" 
        class="block mt-1 w-full rounded-lg border border-white bg-white text-gray-900 placeholder-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-400 shadow-inner transition duration-300"
        type="password"
        name="password"
        required 
        autocomplete="current-password"
        placeholder="Enter your password"
    />

    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
</div>


        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center text-white">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-700 text-[#00529B] shadow-sm focus:ring-[#00529B]" name="remember">
                <span class="ms-2 text-sm">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-white hover:text-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 bg-[#00529B] hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 transition duration-300 text-white">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
