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
            <!-- Layout buttons -->
            <div class="flex rounded-lg border border-gray-300 overflow-hidden">
                <button @click="layoutView = 'list'" :class="{'bg-gray-200': layoutView === 'list'}" class="p-2 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-gray-600">view_list</span>
                </button>
                <button @click="layoutView = 'grid'" :class="{'bg-gray-200': layoutView === 'grid'}" class="p-2 hover:bg-gray-100 border-l border-gray-300">
                    <span class="material-symbols-outlined text-gray-600">grid_view</span>
                </button>
            </div>
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
    <div :class="{'bg-white rounded-lg shadow-sm': layoutView === 'list', 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4': layoutView === 'grid'}">
        <div :class="{'hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-500 px-6 py-4 border-b': layoutView === 'list', 'hidden': layoutView === 'grid'}">
            <div class="col-span-5">Name</div>
            <div class="col-span-3">Last Modified</div>
            <div class="col-span-2">Size</div>
            <div class="col-span-2"></div>
        </div>

        @forelse ($items as $item)
            {{-- List View --}}
            <div x-show="layoutView === 'list'">
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
                                <button @click="open = !open" class="text-gray-500 hover:text-gray-700 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bri-blue transition-colors opacity-100">
                                    <span class="material-symbols">more_vert</span>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20 focus:outline-none" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95">
                                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                                        <a href="{{ route('folder.download', $item) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <span class="material-symbols-outlined mr-3">download</span>
                                            <span>Download</span>
                                        </a>
                                        <a href="#" @click.prevent="showEditModal = true; editItem = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', action: '{{ Route::has('file.update') ? route('file.update', $item) : '#' }}' }; open = false" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <span class="material-symbols-outlined mr-3">drive_file_rename_outline</span>
                                            <span>Rename</span>
                                        </a>
                                        <form action="{{ route('file.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus folder ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" role="menuitem">
                                                <span class="material-symbols-outlined mr-3">delete</span>
                                                <span>Delete</span>
                                            </button>
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
                                <button @click="open = !open" class="text-gray-500 hover:text-gray-700 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-bri-blue">
                                    <span class="material-symbols">more_vert</span>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                                    <div class="py-1" role="menu" aria-orientation="vertical">
                                        <a href="{{ Route::has('file.download') ? route('file.download', $item) : '#' }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <span class="material-symbols-outlined mr-3">download</span>
                                            <span>Download</span>
                                        </a>
                                        <a href="#" @click.prevent="showEditModal = true; editItem = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', action: '{{ Route::has('file.update') ? route('file.update', $item) : '#' }}' }; open = false" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <span class="material-symbols-outlined mr-3">drive_file_rename_outline</span>
                                            <span>Rename</span>
                                        </a>
                                        <form action="{{ route('file.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" role="menuitem">
                                                <span class="material-symbols-outlined mr-3">delete</span>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Grid View --}}
            <div x-show="layoutView === 'grid'" class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow relative group">
                <a href="{{ $item->is_folder ? route('file.folder', $item) : route('file.preview', $item) }}"
                   target="{{ $item->is_folder ? '_self' : '_blank' }}"
                   class="block">
                    <div class="flex justify-center mb-4">
                        <x-file-icon :item="$item" class="w-16 h-16" />
                    </div>
                    <h3 class="font-semibold text-gray-800 truncate text-center">{{ $item->name }}</h3>
                    <p class="text-sm text-gray-500 text-center">{{ $item->updated_at->format('d M, Y') }}</p>
                    <p class="text-sm text-gray-500 text-center">{{ $item->is_folder ? '—' : \Illuminate\Support\Number::fileSize($item->size) }}</p>
                </a>
                <!-- Action menu for grid view -->
                <div class="absolute top-2 right-2">
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click.stop.prevent="open = !open"
                                class="text-gray-500 hover:text-gray-700 p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bri-blue transition-colors opacity-100">
                            <span class="material-symbols">more_vert</span>
                        </button>
                        <div x-show="open"
                             @click.outside="open = false"
                             x-cloak
                             class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20 focus:outline-none"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                                @if (!$item->is_folder)
                                    <a href="{{ route('file.download', $item) }}"
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        <span class="material-symbols-outlined mr-3">download</span>
                                        <span>Download</span>
                                    </a>
                                @endif
                                <a href="#"
                                   @click.prevent="showEditModal = true; editItem = { id: {{ $item->id }}, name: @js($item->name), action: '{{ route('file.update', $item) }}' }; open = false"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    <span class="material-symbols-outlined mr-3">drive_file_rename_outline</span>
                                    <span>Rename</span>
                                </a>
                                <form action="{{ route('file.destroy', $item) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this {{ $item->is_folder ? 'folder' : 'file' }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100" role="menuitem">
                                        <span class="material-symbols-outlined mr-3">delete</span>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500">This folder is empty.</div>
        @endforelse
    </div>
</div>