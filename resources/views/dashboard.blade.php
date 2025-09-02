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
        :root { --bri-blue: #00529B; --bri-blue-dark: #003a70; }
        body { background-color: #f4f7fc; }
        .bg-bri-blue { background-color: var(--bri-blue); }
        .hover\:bg-bri-blue-dark:hover { background-color: var(--bri-blue-dark); }
        .text-bri-blue { color: var(--bri-blue); }
        .ring-bri-blue:focus { --tw-ring-color: var(--bri-blue); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans text-gray-800 antialiased" x-data="{ showCreateFolderModal: false, showUploadFileModal: false }">

    <div class="flex h-screen bg-gray-100">
        <aside class="w-64 bg-white flex flex-col p-6 border-r border-gray-200">
            <div class="flex items-center space-x-3 mb-10">
                <div class="p-2 bg-bri-blue rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0l3-3m0 0l3 3m-3-3v12"></path></svg>
                </div>
                <span class="text-2xl font-bold text-bri-blue">DataBOX</span>
            </div>

            <div x-data="{ open: false }" class="relative">
                @auth
                    <button @click="open = !open" class="w-full flex items-center justify-center space-x-2 bg-bri-blue text-white font-semibold py-3 px-4 rounded-lg hover:bg-bri-blue-dark transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 ring-bri-blue">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <span>Create New</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="absolute z-10 mt-2 w-full bg-white rounded-md shadow-lg border">
                        <a href="#" @click.prevent="showUploadFileModal = true; open = false" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <span>Upload File</span>
                            </div>
                        </a>
                        <a href="#" @click.prevent="showCreateFolderModal = true; open = false" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                <span>New Folder</span>
                            </div>
                        </a>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center space-x-2 bg-bri-blue text-white font-semibold py-3 px-4 rounded-lg hover:bg-bri-blue-dark transition-colors">
                        <span>Login to Create</span>
                    </a>
                @endauth
            </div>

            <nav class="mt-10 flex-1">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') || request()->routeIs('dashboard.folder') ? 'text-bri-blue font-semibold bg-blue-100' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100' }}">
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
                    
                    @auth
                    <li>
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-colors {{ request()->routeIs('profile.edit') ? 'text-bri-blue font-semibold bg-blue-100' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Profile</span>
                        </a>
                    </li>
                    @endauth
                    </ul>
            </nav>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto">
            <header class="flex justify-between items-center">
                <form method="GET" action="{{ route('dashboard') }}" class="flex-grow max-w-lg">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search files and folders..." class="w-full bg-white border border-gray-300 rounded-lg py-2 pl-10 pr-4 focus:outline-none focus:ring-2 ring-bri-blue">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-bri-blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>
                @auth
                    @include('layouts.navigation')
                @else
                    <div class="flex items-center space-x-4 ml-4">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-bri-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-bri-blue-dark">Register</a>
                        @endif
                    </div>
                @endauth
            </header>
            <div class="mt-8">
                <nav class="flex items-center text-sm font-medium text-gray-500 mb-4">
                    <a href="{{ route('dashboard') }}" class="hover:text-bri-blue">My Files</a>
                    @if($folder)
                        @foreach ($breadcrumbs as $breadcrumb)
                            <span class="mx-2">/</span>
                            <a href="{{ $breadcrumb['route'] }}" class="hover:text-bri-blue">{{ $breadcrumb['name'] }}</a>
                        @endforeach
                    @endif
                </nav>
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert"><p>{{ session('success') }}</p></div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <div class="space-y-8">
                    <section>
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold">Folders</h2>
                             <form method="GET" action="{{ $folder ? route('dashboard.folder', $folder) : route('dashboard') }}" class="flex items-center space-x-2">
                                 <input type="hidden" name="search" value="{{ request('search') }}">
                                 <select name="sort_by" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-lg focus:ring-bri-blue focus:border-bri-blue">
                                     <option value="name_asc" @selected(request('sort_by', 'name_asc') == 'name_asc')>Name (A-Z)</option>
                                     <option value="name_desc" @selected(request('sort_by') == 'name_desc')>Name (Z-A)</option>
                                     <option value="date_desc" @selected(request('sort_by') == 'date_desc')>Date (Newest)</option>
                                     <option value="date_asc" @selected(request('sort_by') == 'date_asc')>Date (Oldest)</option>
                                 </select>
                             </form>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                           @forelse ($folders as $subFolder)
                                <a href="{{ route('dashboard.folder', $subFolder) }}" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md hover:ring-2 hover:ring-bri-blue transition-all relative group">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="p-2 bg-blue-100 rounded-lg"><svg class="w-6 h-6 text-bri-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg></div>
                                        <form action="{{ route('file.delete', $subFolder) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus folder ini?')">@csrf @method('DELETE')<button type="submit" class="absolute top-2 right-2 text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity z-10"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                                    </div>
                                    <h3 class="font-semibold truncate">{{ $subFolder->name }}</h3>
                                    <p class="text-sm text-gray-400">{{ $subFolder->created_at->format('d M, Y') }}</p>
                                </a>
                            @empty
                                @if($files->isEmpty())
                                <div class="col-span-full bg-white p-6 rounded-lg text-center text-gray-500"><p>Folder ini kosong.</p></div>
                                @endif
                            @endforelse
                        </div>
                    </section>
                    <section>
                         <div class="flex justify-between items-center mb-4"><h2 class="text-2xl font-bold">Files</h2></div>
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-500 px-6 py-4 border-b"><div class="col-span-5">Name</div><div class="col-span-3">Last Modified</div><div class="col-span-2">Size</div><div class="col-span-2"></div></div>
                            @forelse ($files as $file)
                                <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-gray-50 border-b last:border-b-0 group">
                                    <a href="{{ route('file.preview', $file) }}" target="_blank" class="col-span-12 md:col-span-5 flex items-center space-x-3">
                                        <div class="p-2 bg-blue-100 rounded-lg"><svg class="w-5 h-5 text-bri-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0011.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div>
                                        <span class="font-medium truncate">{{ $file->name }}</span>
                                    </a>
                                    <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $file->updated_at->format('d M, Y') }}</div>
                                    <div class="col-span-6 md:col-span-2 text-sm text-gray-500">{{ \Illuminate\Support\Number::fileSize($file->size) }}</div>
                                    <div class="col-span-12 md:col-span-2 text-right flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('file.download', $file) }}" class="text-gray-400 hover:text-bri-blue p-1 rounded-full" title="Download"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></a>
                                        <form action="{{ route('file.delete', $file) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini?')">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-600 p-1 rounded-full" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>
                                    </div>
                                </div>
                            @empty
                                @if($folders->isEmpty())
                                <div class="p-6 text-center text-gray-500"><p>Tidak ada file di sini.</p></div>
                                @endif
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <div x-show="showCreateFolderModal" x-cloak @keydown.escape.window="showCreateFolderModal = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"><div @click.outside="showCreateFolderModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md"><h3 class="text-xl font-semibold mb-4">Create New Folder</h3><form action="{{ route('folder.create') }}" method="POST">@csrf<input type="hidden" name="parent_id" value="{{ $folder?->id }}"><input type="text" name="folder_name" placeholder="Enter folder name" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 ring-bri-blue" required><div class="mt-4 flex justify-end space-x-2"><button type="button" @click="showCreateFolderModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button><button type="submit" class="px-4 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark">Create</button></div></form></div></div>
    <div x-show="showUploadFileModal" x-cloak @keydown.escape.window="showUploadFileModal = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"><div @click.outside="showUploadFileModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md"><h3 class="text-xl font-semibold mb-4">Upload New File</h3><form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data">@csrf<input type="hidden" name="parent_id" value="{{ $folder?->id }}"><input type="file" name="file_upload" class="w-full border border-gray-300 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-bri-blue hover:file:bg-blue-100" required><div class="mt-4 flex justify-end space-x-2"><button type="button" @click="showUploadFileModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button><button type="submit" class="px-4 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark">Upload</button></div></form></div></div>

</body>
</html>