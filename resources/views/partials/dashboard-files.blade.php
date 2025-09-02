<nav class="flex items-center text-sm font-medium text-gray-500 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-bri-blue">My Files</a>
    @foreach ($breadcrumbs as $breadcrumb)
        <span class="mx-2">/</span>
        <a href="{{ $breadcrumb['route'] }}" class="hover:text-bri-blue">{{ $breadcrumb['name'] }}</a>
    @endforeach
</nav>

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
                    <div class="flex justify-between items-center mb-4"><div class="p-2 bg-blue-100 rounded-lg"><svg class="w-6 h-6 text-bri-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg></div><form action="{{ route('file.delete', $subFolder) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus folder ini?')">@csrf @method('DELETE')<button type="submit" class="absolute top-2 right-2 text-gray-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity z-10"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form></div>
                    <h3 class="font-semibold truncate">{{ $subFolder->name }}</h3>
                    <p class="text-sm text-gray-400">{{ $subFolder->created_at->format('d M, Y') }}</p>
                </a>
            @empty
                @if($files->isEmpty())<div class="col-span-full bg-white p-6 rounded-lg text-center text-gray-500"><p>Folder ini kosong.</p></div>@endif
            @endforelse
        </div>
    </section>
    <section>
         <div class="flex justify-between items-center mb-4"><h2 class="text-2xl font-bold">Files</h2></div>
        <div class="bg-white rounded-lg shadow-sm">
            <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-500 px-6 py-4 border-b"><div class="col-span-5">Name</div><div class="col-span-3">Last Modified</div><div class="col-span-2">Size</div><div class="col-span-2"></div></div>
            @forelse ($files as $file)
                <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-gray-50 border-b last:border-b-0 group">
                    <a href="{{ route('file.preview', $file) }}" target="_blank" class="col-span-12 md:col-span-5 flex items-center space-x-3"><div class="p-2 bg-blue-100 rounded-lg"><svg class="w-5 h-5 text-bri-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0011.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div><span class="font-medium truncate">{{ $file->name }}</span></a>
                    <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $file->updated_at->format('d M, Y') }}</div>
                    <div class="col-span-6 md:col-span-2 text-sm text-gray-500">{{ \Illuminate\Support\Number::fileSize($file->size) }}</div>
                    <div class="col-span-12 md:col-span-2 text-right flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity"><a href="{{ route('file.download', $file) }}" class="text-gray-400 hover:text-bri-blue p-1 rounded-full" title="Download"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></a><form action="{{ route('file.delete', $file) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini?')">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-600 p-1 rounded-full" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form></div>
                </div>
            @empty
                @if($folders->isEmpty())<div class="p-6 text-center text-gray-500"><p>Tidak ada file di sini.</p></div>@endif
            @endforelse
        </div>
    </section>
</div>
