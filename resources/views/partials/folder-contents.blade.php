<nav class="flex items-center text-sm font-medium text-gray-500 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-bri-blue">My Files</a>
    @foreach ($breadcrumbs as $breadcrumb)
        <span class="mx-2">/</span>
        <a href="{{ $breadcrumb['route'] }}" class="hover:text-bri-blue">{{ $breadcrumb['name'] }}</a>
    @endforeach
</nav>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
    <h2 class="text-2xl font-bold mb-4 sm:mb-0">{{ $folder->name }}</h2>
    <div class="flex items-center space-x-2">
        <button @click="showUploadFileModal = true" class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Add File</button>
        <button @click="showCreateFolderModal = true" class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Add Folder</button>
        <form method="GET" action="{{ route('dashboard.folder', $folder) }}"><input type="hidden" name="search" value="{{ request('search') }}"><select name="sort_by" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-lg focus:ring-bri-blue focus:border-bri-blue"><option value="name_asc" @selected(request('sort_by', 'name_asc') == 'name_asc')>Name (A-Z)</option><option value="name_desc" @selected(request('sort_by') == 'name_desc')>Name (Z-A)</option><option value="date_desc" @selected(request('sort_by') == 'date_desc')>Date (Newest)</option><option value="date_asc" @selected(request('sort_by') == 'date_asc')>Date (Oldest)</option></select></form>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-500 px-6 py-4 border-b"><div class="col-span-5">Name</div><div class="col-span-3">Last Modified</div><div class="col-span-2">Size</div><div class="col-span-2"></div></div>

    @if($folders->isEmpty() && $files->isEmpty())
        <div class="p-6 text-center text-gray-500">Folder ini kosong.</div>
    @else
        @foreach ($folders as $subFolder)
            <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-gray-50 border-b last:border-b-0 group">
                <a href="{{ route('dashboard.folder', $subFolder) }}" class="col-span-12 md:col-span-5 flex items-center space-x-3"><div class="p-2 bg-blue-100 rounded-lg"><svg class="w-5 h-5 text-bri-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg></div><span class="font-medium truncate">{{ $subFolder->name }}</span></a>
                <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $subFolder->updated_at->format('d M, Y') }}</div>
                <div class="col-span-6 md:col-span-2 text-sm text-gray-500">—</div>
                <div class="col-span-12 md:col-span-2 text-right opacity-0 group-hover:opacity-100 transition-opacity">
                    <div x-data="{ open: false }" class="relative inline-block text-left"><button @click="open = !open" class="text-gray-500 hover:text-gray-700 p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-bri-blue"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"></path></svg></button><div x-show="open" @click.outside="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20"><div class="py-1"><a href="#" @click.prevent="showEditModal = true; editItem = { id: {{ $subFolder->id }}, name: '{{ addslashes($subFolder->name) }}', action: '{{ route('file.update', $subFolder) }}' }; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rename</a><form action="{{ route('file.delete', $subFolder) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus folder ini?')">@csrf @method('DELETE')<button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button></form></div></div></div>
                </div>
            </div>
        @endforeach
        @foreach ($files as $file)
            <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-gray-50 border-b last:border-b-0 group">
                <a href="{{ route('file.preview', $file) }}" target="_blank" class="col-span-12 md:col-span-5 flex items-center space-x-3"><div class="p-2 bg-blue-100 rounded-lg"><svg class="w-5 h-5 text-bri-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0011.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div><span class="font-medium truncate">{{ $file->name }}</span></a>
                <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $file->updated_at->format('d M, Y') }}</div>
                <div class="col-span-6 md:col-span-2 text-sm text-gray-500">{{ \Illuminate\Support\Number::fileSize($file->size) }}</div>
                <div class="col-span-12 md:col-span-2 text-right opacity-0 group-hover:opacity-100 transition-opacity">
                    <div x-data="{ open: false }" class="relative inline-block text-left"><button @click="open = !open" class="text-gray-500 hover:text-gray-700 p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-bri-blue"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"></path></svg></button><div x-show="open" @click.outside="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20"><div class="py-1"><a href="{{ route('file.download', $file) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Download</a><a href="#" @click.prevent="showEditModal = true; editItem = { id: {{ $file->id }}, name: '{{ addslashes($file->name) }}', action: '{{ route('file.update', $file) }}' }; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rename</a><form action="{{ route('file.delete', $file) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini?')">@csrf @method('DELETE')<button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button></form></div></div></div>
                </div>
            </div>
        @endforeach
    @endif
</div>