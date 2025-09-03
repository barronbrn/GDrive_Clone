<header class="flex justify-between items-center">
    <!-- Form Pencarian -->
    <form method="GET" action="{{-- URL form ini akan diisi oleh halaman konten --}}" class="flex-grow max-w-lg">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search files and folders..." class="w-full bg-white border border-gray-300 rounded-lg py-2 pl-10 pr-4 focus:outline-none focus:ring-2 ring-bri-blue">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-bri-blue">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </div>
    </form>

    @auth
        <!-- Dropdown Profile jika user sudah login -->
        <div x-data="{ open: false }" class="relative ml-4">
            <button @click="open = !open" class="flex items-center space-x-3 text-sm font-medium text-gray-700 hover:text-bri-blue focus:outline-none transition duration-150 ease-in-out">
                <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=00529B&background=EBF4FF" alt="{{ Auth::user()->name }}">
                <div>{{ Auth::user()->name }}</div>
                <div class="ml-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
            </button>
            <div x-show="open" @click.outside="open = false" x-cloak class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0">
                <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-white py-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</a></form>
                </div>
            </div>
        </div>
    @else
        <!-- Tombol Login/Register jika user adalah tamu -->
        <div class="flex items-center space-x-4 ml-4">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Log in</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-bri-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-bri-blue-dark">Register</a>
            @endif
        </div>
    @endauth
</header>
