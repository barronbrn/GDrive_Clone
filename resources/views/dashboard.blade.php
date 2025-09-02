<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if(request()->routeIs('recent')) Recent Items
        @elseif(request()->routeIs('trash')) Trash
        @else Dashboard @endif
        - DataBOX
    </title>
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
                <div class="p-2 bg-bri-blue rounded-lg"><svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0l3-3m0 0l3 3m-3-3v12"></path></svg></div>
                <span class="text-2xl font-bold text-bri-blue">DataBOX</span>
            </div>

            <div x-data="{ open: false }" class="relative"> @auth <button @click="open = !open" class="w-full flex items-center justify-center space-x-2 bg-bri-blue text-white font-semibold py-3 px-4 rounded-lg hover:bg-bri-blue-dark transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 ring-bri-blue"><span>Create New</span> <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button> <div x-show="open" @click.outside="open = false" x-cloak class="absolute z-10 mt-2 w-full bg-white rounded-md shadow-lg border"> <a href="#" @click.prevent="showUploadFileModal = true; open = false" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100"><div class="flex items-center space-x-2"><span>Upload File</span></div></a> <a href="#" @click.prevent="showCreateFolderModal = true; open = false" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100"><div class="flex items-center space-x-2"><span>New Folder</span></div></a></div> @else <a href="{{ route('login') }}" class="w-full flex items-center justify-center space-x-2 bg-bri-blue text-white font-semibold py-3 px-4 rounded-lg hover:bg-bri-blue-dark transition-colors"><span>Login to Create</span></a> @endauth</div>

            <nav class="mt-10 flex-1">
                <ul class="space-y-2">
                    <li><a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-colors {{ request()->routeIs('dashboard*') ? 'text-bri-blue font-semibold bg-blue-100' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100' }}"><span>Dashboard</span></a></li>
                    <li><a href="{{ route('recent') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-colors {{ request()->routeIs('recent') ? 'text-bri-blue font-semibold bg-blue-100' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100' }}"><span>Recent</span></a></li>
                    <li><a href="{{ route('trash') }}" class="flex items-center space-x-3 p-3 rounded-lg transition-colors {{ request()->routeIs('trash') ? 'text-bri-blue font-semibold bg-blue-100' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-100' }}"><span>Trash</span></a></li>
                    {{-- Menu Profile di sidebar dihapus untuk menghindari duplikasi --}}
                </ul>
            </nav>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto">
            <header class="flex justify-between items-center">
                <form method="GET" action="{{ $folder ? route('dashboard.folder', $folder) : route('dashboard') }}" class="flex-grow max-w-lg">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search files and folders..." class="w-full bg-white border border-gray-300 rounded-lg py-2 pl-10 pr-4 focus:outline-none focus:ring-2 ring-bri-blue">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-bri-blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>

                @auth
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
                    <div class="flex items-center space-x-4 ml-4"><a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">Log in</a> @if (Route::has('register'))<a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-bri-blue border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-bri-blue-dark">Register</a>@endif</div>
                @endauth
            </header>

            <div class="mt-8">
                @if (session('success'))<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert"><p>{{ session('success') }}</p></div>@endif
                
                {{-- Konten Dinamis --}}
                @if(request()->routeIs('recent'))
                    @include('partials.recent-list', ['items' => $recentItems ?? collect()])
                @elseif(request()->routeIs('trash'))
                    @include('partials.trash-list', ['items' => $trashedItems ?? collect()])
                @else
                    @include('partials.dashboard-files', [
                        'folders' => $folders ?? collect(), 
                        'files' => $files ?? collect(),
                        'folder' => $folder ?? null,
                        'breadcrumbs' => $breadcrumbs ?? collect()
                    ])
                @endif
            </div>
        </main>
    </div>

    <div x-show="showCreateFolderModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"><div @click.outside="showCreateFolderModal" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md"><h3 class="text-xl font-semibold mb-4">Create New Folder</h3><form action="{{ route('folder.create') }}" method="POST">@csrf<input type="hidden" name="parent_id" value="{{ $folder?->id ?? null }}"><input type="text" name="folder_name" placeholder="Enter folder name" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 ring-bri-blue" required><div class="mt-4 flex justify-end space-x-2"><button type="button" @click="showCreateFolderModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button><button type="submit" class="px-4 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark">Create</button></div></form></div></div>
    <div x-show="showUploadFileModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"><div @click.outside="showUploadFileModal" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md"><h3 class="text-xl font-semibold mb-4">Upload New File</h3><form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data">@csrf<input type="hidden" name="parent_id" value="{{ $folder?->id ?? null }}"><input type="file" name="file_upload" class="w-full border border-gray-300 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-bri-blue hover:file:bg-blue-100" required><div class="mt-4 flex justify-end space-x-2"><button type="button" @click="showUploadFileModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button><button type="submit" class="px-4 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark">Upload</button></div></form></div></div>
</body>
</html>