<nav class="flex items-center text-sm font-medium text-gray-500 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-bri-blue">My Drive</a>
</nav>

<div class="space-y-8">
    <section>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Folders</h2>
             <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-2">
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
                <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow relative group">
                    <a href="{{ route('dashboard.folder', $subFolder) }}" class="block absolute inset-0 z-10" title="{{ $subFolder->name }}"></a>
                    <div class="relative z-0">
                        <div class="flex justify-between items-center mb-4">
                            <div class="p-2 bg-blue-100 rounded-lg"><svg class="w-6 h-6 text-bri-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg></div>
                        </div>
                        <h3 class="font-semibold truncate">{{ $subFolder->name }}</h3>
                        <p class="text-sm text-gray-400">{{ $subFolder->created_at->format('d M, Y') }}</p>
                    </div>
                    <div x-data="{ open: false }" class="absolute top-2 right-2 z-20">
                        <button @click.stop.prevent="open = !open" class="text-gray-400 hover:text-gray-600 p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-bri-blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"></path></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg border">
                            <a href="#" @click.prevent="showEditModal = true; editItem = { id: {{ $subFolder->id }}, name: '{{ addslashes($subFolder->name) }}', action: '{{ route('file.update', $subFolder) }}' }; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rename</a>
                            <form action="{{ route('file.delete', $subFolder) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus folder ini?')"> @csrf @method('DELETE') <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button></form>
                        </div>
                    </div>
                </div>
            @empty
                @if($files->isEmpty())<div class="col-span-full bg-white p-6 rounded-lg text-center text-gray-500"><p>Tidak ada folder.</p></div>@endif
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
                        <x-file-icon :item="$file" />
                        <span class="font-medium truncate">{{ $file->name }}</span>
                    </a>
                    <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $file->updated_at->format('d M, Y') }}</div>
                    <div class="col-span-6 md:col-span-2 text-sm text-gray-500">{{ \Illuminate\Support\Number::fileSize($file->size) }}</div>
                    <div class="col-span-12 md:col-span-2 text-right">
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button @click="open = !open" class="text-gray-500 hover:text-gray-700 p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-bri-blue">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"></path></svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                                <div class="py-1" role="menu" aria-orientation="vertical">
                                    <a href="{{ route('file.download', $file) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Download</a>
                                    <a href="#" @click.prevent="showEditModal = true; editItem = { id: {{ $file->id }}, name: '{{ addslashes($file->name) }}', action: '{{ route('file.update', $file) }}' }; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rename</a>
                                    <form action="{{ route('file.delete', $file) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini?')">@csrf @method('DELETE')<button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button></form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                @if($folders->isEmpty())<div class="p-6 text-center text-gray-500"><p>Tidak ada file.</p></div>@endif
            @endforelse
        </div>
    </section>
</div>