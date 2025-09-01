<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cloud Storage - Dashboard</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bri-blue: #00529B;
            --bri-blue-dark: #003a70;
            --bri-accent-orange: #F7941D;
            --bri-accent-orange-dark: #e0851a;
        }
        body { background-color: #f4f7fc; }
        .bg-bri-blue { background-color: var(--bri-blue); }
        .hover\:bg-bri-blue-dark:hover { background-color: var(--bri-blue-dark); }
        .text-bri-blue { color: var(--bri-blue); }
        .ring-bri-blue:focus { --tw-ring-color: var(--bri-blue); }
    </style>
</head>
<body class="font-sans text-gray-800 antialiased">

    <div class="flex h-screen bg-gray-100">
        <aside class="w-64 bg-white flex flex-col p-6 border-r border-gray-200">
            <div class="flex items-center space-x-3 mb-10">
                <div class="p-2 bg-bri-blue rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0l3-3m0 0l3 3m-3-3v12"></path></svg>
                </div>
                <span class="text-2xl font-bold text-bri-blue">DataBOX</span>
            </div>

            @auth
                <button class="w-full flex items-center justify-center space-x-2 bg-bri-blue text-white font-semibold py-3 px-4 rounded-lg hover:bg-bri-blue-dark transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 ring-bri-blue">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>Create New</span>
                </button>
            @else
                <a href="{{ route('login') }}" class="w-full flex items-center justify-center space-x-2 bg-bri-blue text-white font-semibold py-3 px-4 rounded-lg hover:bg-bri-blue-dark transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    <span>Login to Create</span>
                </a>
            @endauth

            <nav class="mt-10 flex-1">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'text-bri-blue font-semibold bg-blue-100' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('recent') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-colors {{ request()->routeIs('recent') ? 'text-bri-blue font-semibold bg-blue-100' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Recent</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('trash') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-colors {{ request()->routeIs('trash') ? 'text-bri-blue font-semibold bg-blue-100' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Trash</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto">
            <header class="flex justify-between items-center">
                <div class="relative w-full max-w-lg">
                    <input type="text" placeholder="Search files and folders..." class="w-full bg-white border border-gray-300 rounded-lg py-2 pl-10 pr-4 focus:outline-none focus:ring-2 ring-bri-blue">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                @auth
                    @include('layouts.navigation')
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-bri-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-bri-blue-dark focus:outline-none focus:ring-2 focus:ring-offset-2 ring-bri-blue transition ease-in-out duration-150">Register</a>
                        @endif
                    </div>
                @endauth
            </header>

            <div class="mt-8">
                <div class="space-y-8">
                    <section>
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold">Folders</h2>
                            <button class="flex items-center space-x-1 text-gray-500 text-sm">
                                <span>Name</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                            @forelse ($folders as $folder)
                                <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="p-2 bg-blue-100 rounded-lg"><svg class="w-6 h-6 text-bri-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg></div>
                                        <button class="text-gray-400 hover:text-gray-600">...</button>
                                    </div>
                                    <h3 class="font-semibold">{{ $folder['name'] }}</h3>
                                    <p class="text-sm text-gray-400">{{ $folder['date'] }}</p>
                                    <p class="text-sm text-gray-400 mt-2">{{ $folder['file_count'] }} Files</p>
                                </div>
                            @empty
                                <div class="col-span-full bg-white p-6 rounded-lg text-center text-gray-500">
                                    @auth
                                        <p>Folder Anda masih kosong.</p>
                                    @else
                                        <p>Login untuk melihat folder Anda.</p>
                                    @endauth
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <section>
                         <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold">Files</h2>
                             @auth
                                <a href="#" class="text-bri-blue font-semibold text-sm hover:underline">View All</a>
                             @endauth
                        </div>
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-500 px-6 py-4 border-b">
                                <div class="col-span-4">Name</div>
                                <div class="col-span-2">Members</div>
                                <div class="col-span-3">Last Edit</div>
                                <div class="col-span-2">Size</div>
                            </div>
                            @forelse ($files as $file)
                                <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-gray-50 border-b last:border-b-0">
                                    <div class="col-span-11 md:col-span-4 flex items-center space-x-3">
                                        <div class="p-2 bg-blue-100 rounded-lg"><svg class="w-5 h-5 text-bri-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0011.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div>
                                        <span class="font-medium">{{ $file['name'] }}</span>
                                    </div>
                                    <div class="col-span-6 md:col-span-2 text-sm text-gray-500"><span class="md:hidden font-semibold mr-2">Members:</span>{{ $file['members'] }}</div>
                                    <div class="col-span-6 md:col-span-3 text-sm text-gray-500"><span class="md:hidden font-semibold mr-2">Last Edit:</span>{{ $file['last_edit'] }}</div>
                                    <div class="col-span-6 md:col-span-2 text-sm text-gray-500"><span class="md:hidden font-semibold mr-2">Size:</span>{{ $file['size'] }}</div>
                                    <div class="col-span-1 text-right"><button class="text-gray-400 hover:text-gray-600">...</button></div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-500">
                                    @auth
                                        <p>Belum ada file.</p>
                                    @else
                                        <p>Login untuk melihat file Anda.</p>
                                    @endauth
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>
</html>