<header class="flex justify-between items-center text-white py-4 px-6 rounded-2xl shadow-md" 
        style="
            background: linear-gradient(to bottom right, rgba(0, 82, 155, 0.95), rgba(0, 58, 112, 0.95)), url('{{ asset('images/bg-mega-mendung.jpg') }}');
            background-repeat: no-repeat, no-repeat;
            background-size: cover, cover;
            background-position: center, center;
            background-blend-mode: multiply, normal;
        ">
    <!-- Form Pencarian -->

    <form method="GET" action="{{ url()->current() }}" class="flex-grow max-w-[250px]">

        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Search files and folders..." 
                   class="w-full bg-white/20 text-white placeholder-white/70 border-none rounded-full py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-white/50 transition">
        </div>
    </form>

    @auth
        <!-- Dropdown Profile -->
        <div x-data="{ open: false }" class="relative ml-4">
            <button 
                @click="open = !open" 
                class="flex items-center space-x-2 text-sm font-medium focus:outline-none transition duration-150 ease-in-out rounded-full p-1 hover:bg-white/10">
                <img class="h-10 w-10 rounded-full object-cover border-2 border-white/50" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=FFFFFF&background=00529B" alt="{{ Auth::user()->name }}">
                <div class="hidden sm:block text-sm font-medium">{{ Auth::user()->name }}</div>
                <div class="ml-1 hidden sm:block">
                    <svg class="fill-current h-4 w-4 text-white/80" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            <div x-show="open" @click.outside="open = false" x-cloak class="absolute z-50 mt-2 w-48 rounded-xl shadow-lg origin-top-right right-0 bg-white overflow-hidden">
                <div class="py-1">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" 
                           class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</a>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Tombol Login/Register -->
        <div class="flex items-center space-x-4 ml-4">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-white hover:underline">Log in</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-white text-bri-blue border border-transparent rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-gray-100 transition">Register</a>
            @endif
        </div>
    @endauth
</header>