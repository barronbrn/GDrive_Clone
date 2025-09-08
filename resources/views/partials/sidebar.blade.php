<aside class="w-72 bg-white flex-col p-6 border-r border-gray-200 hidden md:flex shadow-lg">
    <div class="flex items-center space-x-4 mb-10">
        <div class="p-3 bg-bri-blue rounded-xl shadow-md">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0l3-3m0 0l3 3m-3-3v12">
                </path>
            </svg>
        </div>
        <span class="text-3xl font-bold text-bri-blue">DataBOX</span>
    </div>

    <div x-data="{ open: false }" class="relative mb-8">
        @auth
            <button @click="open = !open" 
                    class="w-full flex items-center justify-center space-x-2 bg-white text-gray-700 font-semibold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 ring-bri-blue border border-gray-200 transform hover:-translate-y-1">
                <span class="material-symbols-outlined">add_circle</span>
                <span>New</span>
                <span class="material-symbols-outlined transition-transform" :class="{ 'rotate-180': open }">expand_more</span>
            </button>

            <div x-show="open" @click.outside="open = false" x-cloak 
                 class="absolute z-10 mt-2 w-full bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95">
                <a href="#" @click.prevent="showUploadFileModal = true; open = false" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined mr-3">upload_file</span>
                    <span>Upload File</span>
                </a>
                <a href="#" @click.prevent="showCreateFolderModal = true; open = false" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined mr-3">create_new_folder</span>
                    <span>New Folder</span>
                </a>
            </div>
        @else
            <a href="{{ route('login') }}" 
               class="w-full flex items-center justify-center space-x-2 bg-bri-blue text-white font-semibold py-3 px-4 rounded-xl hover:bg-bri-blue-dark transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <span class="material-symbols-outlined">login</span>
                <span>Login to Create</span>
            </a>
        @endauth
    </div>

    <nav class="flex-1">
        <ul class="space-y-3">
            <li>
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-4 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard*') ? 'text-white font-bold bg-bri-blue shadow-lg' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                    <span class="material-symbols-outlined">folder_managed</span>
                    <span>My File</span>
                </a>
            </li>
            <li>
                <a href="{{ route('recent') }}" 
                   class="flex items-center space-x-4 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('recent') ? 'text-white font-bold bg-bri-blue shadow-lg' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                    <span class="material-symbols-outlined">history</span>
                    <span>Recent</span>
                </a>
            </li>
            <li>
                <a href="{{ route('trash') }}" 
                   class="flex items-center space-x-4 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('trash') ? 'text-white font-bold bg-bri-blue shadow-lg' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                    <span class="material-symbols-outlined">delete</span>
                    <span>Trash</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="mt-auto">
        <div class="p-4 bg-blue-50 rounded-xl text-center">
            <p class="text-sm font-semibold text-bri-blue italic">Melayani dengan setulus hati</p>
        </div>
    </div>
</aside>