<header class="flex justify-between items-center bg-bri-blue text-white py-3 px-4 rounded-2xl shadow-md">
    <!-- Form Pencarian -->
    <form method="GET" action="{{-- URL form ini akan diisi oleh halaman konten --}}" class="flex-grow max-w-[250px]">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search files..." 
                   class="w-full bg-white text-gray-700 border border-gray-300 rounded-full py-2 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-bri-blue">
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-bri-blue">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>
    </form>

    @auth
        <!-- Dropdown Profile -->
        <div x-data="{ open: false }" class="relative ml-4">
            <button 
                @click="open = !open" 
                class="flex items-center space-x-3 text-sm font-medium focus:outline-none transition duration-150 ease-in-out rounded-full p-2 bg-bri-blue text-white hover:bg-bri-blue-dark"
                :class="{ 'bg-bri-blue-dark': open }">
                <img class="h-10 w-10 rounded-full object-cover border-2 border-white" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=00529B&background=EBF4FF" alt="{{ Auth::user()->name }}">
                <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                <div class="ml-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            <div x-show="open" @click.outside="open = false" x-cloak class="absolute z-50 mt-2 w-48 rounded-2xl shadow-lg origin-top-right right-0 bg-white overflow-hidden">
                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-900 hover:bg-blue-200 hover:text-gray ">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-900 hover:bg-blue-200 hover:text-gray active:bg-bri-blue-dark active:text-white ">Log Out</a>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Tombol Login/Register -->
        <div class="flex items-center space-x-4 ml-4">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-white hover:text-gray-200">Log in</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-3 bg-white text-bri-blue border border-transparent rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-gray-100">Register</a>
            @endif
        </div>
    @endauth
</header>
