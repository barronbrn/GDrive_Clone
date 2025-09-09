<div x-init="currentFolderId = {{ $folder->id }}">
    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center text-sm font-medium text-gray-500 mb-6">
        <a href="{{ Route::has('dashboard') ? route('dashboard') : '/' }}" class="hover:text-bri-blue transition-colors">My Files</a>
        @foreach ($breadcrumbs as $breadcrumb)
            <span class="mx-2 text-gray-400">/</span>
            <a href="{{ $breadcrumb['route'] }}" class="hover:text-bri-blue transition-colors">{{ $breadcrumb['name'] }}</a>
        @endforeach
    </nav>

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">{{ $folder->name }}</h2>
        <div class="flex items-center space-x-3">
            <button @click="showUploadFileModal = true" class="flex items-center px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined mr-2 text-base">upload_file</span>
                <span>Add File</span>
            </button>
            <button @click="showCreateFolderModal = true" class="flex items-center px-4 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                <span class="material-symbols-outlined mr-2 text-base">create_new_folder</span>
                <span>Add Folder</span>
            </button>
            <div x-data="{ sortDirection: '{{ request('sort_direction', 'asc') }}' }" class="flex items-center space-x-3 text-sm">
                <a :href="'{{ url()->current() }}?sort_direction=' + (sortDirection === 'asc' ? 'desc' : 'asc') + '&modified={{ request('modified') }}&search={{ request('search') }}'" 
                   class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                    <span>Nama</span>
                    <span x-show="sortDirection === 'asc'" class="material-symbols-outlined ml-1 text-base">arrow_upward</span>
                    <span x-show="sortDirection === 'desc'" class="material-symbols-outlined ml-1 text-base">arrow_downward</span>
                </a>
                <form method="GET" action="{{ route('file.folder', $folder) }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'asc') }}">
                    <select name="modified" onchange="this.form.submit()" class="border-gray-300 rounded-lg focus:ring-2 focus:ring-bri-blue focus:border-bri-blue text-sm transition">
                        <option value="">Dimodifikasi</option>
                        <option value="today" @selected(request('modified') == 'today')>Hari ini</option>
                        <option value="week" @selected(request('modified') == 'week')>7 hari terakhir</option>
                        <option value="month" @selected(request('modified') == 'month')>Bulan ini</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- File List -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-500 px-6 py-4 border-b">
            <div class="col-span-5">Name</div>
            <div class="col-span-3">Last Modified</div>
            <div class="col-span-2">Size</div>
            <div class="col-span-2"></div>
        </div>

        @forelse ($items as $item)
            @if ($item->is_folder)
                <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-gray-50 border-b last:border-b-0 group">
                    <a href="{{ route('file.folder', $item) }}" class="col-span-12 md:col-span-5 flex items-center space-x-3">
                        <x-file-icon :item="$item" />
                        <span class="font-medium truncate">{{ $item->name }}</span>
                    </a>
                    <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $item->updated_at->format('d M, Y') }}</div>
                    <div class="col-span-6 md:col-span-2 text-sm text-gray-500">{{ \Illuminate\Support\Number::fileSize($item->getFolderSize()) }}</div>
                    <div class="col-span-12 md:col-span-2 text-right">
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button @click="open = !open" class="text-black p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-bri-blue">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                                <div class="py-1">
                                    <a href="{{ route('folder.download', $item) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Download</a>
                                    <a href="#" @click.prevent="showEditModal = true; editItem = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', action: '{{ Route::has('file.update') ? route('file.update', $item) : '#' }}' }; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rename</a>
                                    <form action="{{ route('file.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus folder ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-gray-50 border-b last:border-b-0 group">
                    <a href="{{ route('file.preview', $item) }}" target="_blank" class="col-span-12 md:col-span-5 flex items-center space-x-3">
                        <x-file-icon :item="$item" />
                        <span class="font-medium truncate">{{ $item->name }}</span>
                    </a>
                    <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $item->updated_at->format('d M, Y') }}</div>
                    <div class="col-span-6 md:col-span-2 text-sm text-gray-500">{{ \Illuminate\Support\Number::fileSize($item->size) }}</div>
                    <div class="col-span-12 md:col-span-2 text-right">
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button @click="open = !open" class="text-black p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-bri-blue">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                                <div class="py-1">
                                    <a href="{{ Route::has('file.download') ? route('file.download', $item) : '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Download</a>
                                    <a href="#" @click.prevent="showEditModal = true; editItem = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', action: '{{ Route::has('file.update') ? route('file.update', $item) : '#' }}' }; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rename</a>
                                    <form action="{{ route('file.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="p-6 text-center text-gray-500">This folder is empty.</div>
        @endforelse
    </div>
</div>
