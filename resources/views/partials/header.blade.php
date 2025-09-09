
<header class="flex justify-between items-center text-white py-4 px-6 rounded-2xl shadow-md bg-blue-800" 
        style="
            background-image: url('{{ asset('images/bg-mega-mendung.jpg') }}');
            background-blend-mode: multiply;
            background-size: cover;
            background-position: center;
        ">
    <div class="absolute inset-0 bg-bri-blue opacity-80"></div>
    <div class="relative z-10 flex justify-between items-center w-full">
        <!-- Form Pencarian -->
        <form method="GET" action="{{ url()->current() }}" class="flex-grow max-w-md">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-300">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari di DataBOX..." 
                       class="w-full bg-white/20 text-white placeholder-gray-300 border-none rounded-full py-2 pl-12 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-white/50 transition">
            </div>
        </form>

        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" name="search" placeholder="Search files and folders..." 
                   class="w-full bg-white/40 text-black placeholder-black border border-white/50 rounded-full py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-white/50 transition">
        </div>
    </form>

                <div x-show="open" @click.outside="open = false" x-cloak 
                     class="absolute z-50 mt-2 w-56 rounded-xl shadow-lg origin-top-right right-0 bg-white ring-1 ring-black ring-opacity-5 overflow-hidden"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95">
                    <div class="py-2">
                        <div class="px-4 py-2 border-b border-gray-200">
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <span class="material-symbols-outlined mr-3">person</span>
                            <span>Profil</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-500 hover:bg-gray-100 transition-colors">
                               <span class="material-symbols-outlined mr-3">logout</span>
                               <span>Log Out</span>
                            </button>
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
