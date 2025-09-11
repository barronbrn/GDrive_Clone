
<aside class="w-72 bg-gray-50 flex flex-col p-8 border-r border-gray-200 h-full">
    <div class="flex items-center justify-center mb-12">
        <img src="{{ asset('images/logo-bri.png') }}" alt="BRI Logo" class="h-20 w-auto">
    </div>

    <div x-data="{ open: false }" class="relative mb-8">
        @auth
            <button @click="open = !open" 
                    class="w-full flex items-center justify-center space-x-2 bg-bri-blue text-white font-semibold py-3 px-4 rounded-lg hover:bg-bri-blue-dark transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 ring-bri-blue shadow-2xl hover:shadow-xl transform hover:-translate-y-0.5">
                <!-- Icon + -->
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-sm">Create New</span>
                <svg class="w-4 h-4 ml-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-bind:class="{ 'rotate-180': open }">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
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
                    <span class="material-symbols-outlined mr-3 text-base">upload_file</span>
                    <span>Upload File</span>
                </a>
                <a href="#" @click.prevent="showCreateFolderModal = true; open = false" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined mr-3 text-base">create_new_folder</span>
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


    <nav class="mt-8 flex-1">
        <ul class="space-y-4">
            <li>
                <a href="{{ route('file.index') }}" 
                   class="flex items-center space-x-4 p-3 rounded-lg transition-colors {{ request()->routeIs('file.index') || request()->routeIs('file.folder') ? 'bg-blue-100 text-bri-blue border-l-4 border-bri-blue font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 011.06 0l7.5 7.5a.75.75 0 11-1.06 1.06L12 4.81l-6.97 6.97a.75.75 0 01-1.06-1.06l7.5-7.5z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M12 5.659l-7.5 7.5V18a2.25 2.25 0 002.25 2.25h10.5A2.25 2.25 0 0019.5 18v-4.841l-7.5-7.5zM12 15a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0v-2.25a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-base">Dashboard</span>

                </a>
            </li>
            <li>
                <a href="{{ route('recent') }}" 

                   class="flex items-center space-x-4 p-3 rounded-lg transition-colors {{ request()->routeIs('recent') ? 'bg-blue-100 text-bri-blue border-l-4 border-bri-blue font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6.75a.75.75 0 00.22.53l3 3a.75.75 0 101.06-1.06L12.75 11.69V6z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-base">Recent</span>

                </a>
            </li>
            <li>
                <a href="{{ route('trash') }}" 

                   class="flex items-center space-x-4 p-3 rounded-lg transition-colors {{ request()->routeIs('trash') ? 'bg-blue-100 text-bri-blue border-l-4 border-bri-blue font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.841 48.841 0 013 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.32 0c1.188.036 2.347.177 3.465.364a48.883 48.883 0 013.32-.364C19.187 1.578 20.5 2.914 20.5 4.478zm-8.995 9.972a.75.75 0 001.06 0L12 12.31l1.435 1.435a.75.75 0 001.06-1.06L13.06 11.25l1.435-1.435a.75.75 0 00-1.06-1.06L12 10.19l-1.435-1.435a.75.75 0 00-1.06 1.06l1.435 1.435-1.435 1.435a.75.75 0 000 1.06z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-base">Trash</span>
                </a>
            </li>

        </ul>
    </nav>

    <div class="mt-auto text-center flex justify-center">
        <p class="text-sm font-semibold text-blue-800 bg-blue-200 rounded-full px-2 py-1 italic whitespace-nowrap">
            Serving with a whole heart
        </p>
    </div>
</aside>

