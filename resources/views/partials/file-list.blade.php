<div>


    <!-- File & Folder List -->
    <div class="flex flex-col sm:flex-row justify-end items-start sm:items-center mb-4">
        <!-- Layout buttons -->
        <div class="flex rounded-lg border border-gray-300 overflow-hidden">
            <button @click="layoutView = 'list'" :class="{'bg-gray-200': layoutView === 'list'}" class="p-2 hover:bg-gray-100">
                <span class="material-symbols-outlined text-gray-600">view_list</span>
            </button>
            <button @click="layoutView = 'grid'" :class="{'bg-gray-200': layoutView === 'grid'}" class="p-2 hover:bg-gray-100 border-l border-gray-300">
                <span class="material-symbols-outlined text-gray-600">grid_view</span>
            </button>
        </div>
        <!-- Sorting options -->
        <div x-data="{ sortDirection: '{{ request('sort_direction', 'asc') }}' }" class="flex items-center space-x-3 text-sm ml-3">
            <a :href="'{{ url()->current() }}?sort_direction=' + (sortDirection === 'asc' ? 'desc' : 'asc') + '&modified={{ request('modified') }}&search={{ request('search') }}'"
               class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                <span>Nama</span>
                <span x-show="sortDirection === 'asc'" class="material-symbols-outlined ml-1 text-base">arrow_upward</span>
                <span x-show="sortDirection === 'desc'" class="material-symbols-outlined ml-1 text-base">arrow_downward</span>
            </a>
            <form method="GET" action="{{ route('file.index') }}">
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

    <div x-data="{ selectedItemMenu: null }" :class="{'bg-white rounded-xl shadow-lg border border-gray-200': layoutView === 'list', 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4': layoutView === 'grid'}">

        <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-600 px-6 py-4 border-b border-gray-200">
            <div class="col-span-5 pl-4">Nama</div>
            <div class="col-span-3">Terakhir diubah</div>
            <div class="col-span-2">Ukuran</div>
            <div class="col-span-2"></div>
        </div>

        @forelse ($items as $item)
            {{-- List View --}}
            <div x-show="layoutView === 'list'">
                @if ($item->is_folder)
                    <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <a href="{{ route('file.folder', $item) }}" class="col-span-12 md:col-span-5 flex items-center space-x-3">
                            <x-file-icon :item="$item" />
                            <span class="font-medium truncate">{{ $item->name }}</span>
                        </a>
                        <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $item->updated_at->format('d M, Y') }}</div>
                        <div class="col-span-6 md:col-span-2 text-sm text-gray-500">{{ \Illuminate\Support\Number::fileSize($item->getFolderSize()) }}</div>
                        <div class="col-span-12 md:col-span-2 text-right">
                            <div class="relative inline-block text-left">
                                <button @click.stop.prevent="selectedItemMenu = (selectedItemMenu === {{ $item->id }} ? null : {{ $item->id }})"
                                        class="text-gray-500 hover:text-gray-700 p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bri-blue transition-colors">
                                    <span class="material-symbols">more_vert</span>
                                </button>
                                <div x-show="selectedItemMenu === {{ $item->id }}" @click.outside="selectedItemMenu = null" x-cloak class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20 focus:outline-none" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95">
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

                    <div class="col-span-3 text-sm text-gray-500 hidden md:block">{{ $item->updated_at->format('d M, Y') }}</div>
                    <div class="col-span-2 text-sm text-gray-500 hidden md:block">{{ $item->is_folder ? '—' : \Illuminate\Support\Number::fileSize($item->size) }}</div>
                </a>
                
                <!-- Menu aksi (Download, Rename, Delete) -->
                <div class="absolute top-1/2 right-4 -translate-y-1/2 flex items-center justify-end space-x-2">
                    <x-file-actions-dropdown :item="$item" />
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-500 bg-white rounded-xl shadow-lg border border-gray-200">
                <span class="material-symbols-outlined text-7xl text-gray-400 mb-4">folder_open</span>
                <p class="text-xl font-semibold text-gray-700">{{ isset($folder) && $folder ? 'This folder is empty.' : 'No files or folders found.' }}</p>
                <p class="text-sm text-gray-500 mt-2">Start by uploading a new file or creating a new folder.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    /* Ensure dropdown menu items are solid and clickable on hover */
    .dropdown-menu-item:hover {
        background-color: #f3f4f6 !important; /* Tailwind's gray-100 */
        opacity: 1 !important;
        visibility: visible !important;
        display: block !important; /* Ensure it's not hidden by display:none */
    }
</style>

<style>
    /* Ensure dropdown menu items are solid and clickable on hover */
    .dropdown-menu-item:hover {
        background-color: #f3f4f6 !important; /* Tailwind's gray-100 */
        opacity: 1 !important;
        visibility: visible !important;
        display: block !important; /* Ensure it's not hidden by display:none */
    }
</style>
