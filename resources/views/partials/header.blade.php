<header class="flex justify-between items-center text-white py-4 px-6 rounded-2xl shadow-xl" 
        style="
            background: linear-gradient(rgba(0, 82, 155, 0.2), rgba(0, 82, 155, 0.2)), url('{{ asset('images/bg-mega-mendung.jpg') }}');
            background-repeat: no-repeat, no-repeat;
            background-size: cover, cover;
            background-position: center, center;
            background-blend-mode: multiply, normal;
        ">
    <div class="relative z-10 flex items-center justify-between w-full">
        <!-- Left Section: Sidebar Toggle -->
        <button @click="isSidebarOpen = !isSidebarOpen; localStorage.setItem('isSidebarOpen', isSidebarOpen)"
                class="p-2 rounded-full text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50 transition">
            <span class="material-symbols-outlined" 
                  x-text="isSidebarOpen ? 'menu_open' : 'menu'"
                  x-transition:enter="transition ease-out duration-150"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100"
                  x-transition:leave="transition ease-in duration-150"
                  x-transition:leave-start="opacity-100"
                  x-transition:leave-end="opacity-0"></span>
        </button>

        <!-- Center Section: Search Bar -->
        <div class="flex-grow flex justify-center">
            <form method="GET" action="{{ url()->current() }}" class="max-w-md w-full" x-data="{ search: '{{ request()->get('search') }}' }">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" placeholder="Search files and folders..." x-model="search"
                           class="w-full bg-white/20 text-white placeholder-white/70 border-none rounded-full py-1 pl-10 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-white/50 transition">
                    <button type="button" x-show="search" @click="search = ''; $nextTick(() => $el.closest('form').submit())"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-white/70 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Section: Auth/Guest Dropdown -->
        @auth
            <!-- Dropdown Profile -->
            <div x-data="{ open: false, tooltip: false }" class="relative ml-4">
                <button 
                    @click="open = !open"
                    @mouseenter="tooltip = true"
                    @mouseleave="tooltip = false"
                    class="flex items-center justify-center h-10 w-10 rounded-full bg-white/20 text-white focus:outline-none transition duration-150 ease-in-out hover:bg-white/30 border-2 border-white/50">
                    <div class="font-bold text-xl">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                </button>
                <div 
                    x-show="tooltip && !open"
                    x-transition
                    class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-max bg-gray-800 text-white text-sm rounded-md px-3 py-1 pointer-events-none z-50">
                    {{ Auth::user()->name }}
                    <div class="text-xs text-gray-400">{{ Auth::user()->email }}</div>
                </div>

                <div x-show="open" @click.outside="open = false" x-cloak class="absolute z-50 mt-2 w-56 rounded-xl shadow-lg origin-top-right right-0 bg-white overflow-hidden ring-1 ring-black ring-opacity-5">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <span class="material-symbols-outlined text-base mr-2">account_circle</span>
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" 
                               class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <span class="material-symbols-outlined text-base mr-2">logout</span>
                                Log Out
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Tombol Login/Register -->
            <div class="flex items-center space-x-4 ml-4">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-white hover:text-gray-200 transition-colors">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-white text-bri-blue border border-transparent rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-gray-200 transition-colors">Register</a>
                @endif
            </div>
        @endauth
    </div>
</header>