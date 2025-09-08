<div>
    <!-- Breadcrumbs -->
    <nav class="flex items-center text-sm font-medium text-gray-500 mb-4">
        <a href="{{ route('file.index') }}" class="hover:text-bri-blue">My Files</a>
        @if(isset($breadcrumbs))
            @foreach ($breadcrumbs as $breadcrumb)
                <span class="mx-2">/</span>
                <a href="{{ $breadcrumb['route'] }}" class="hover:text-bri-blue">{{ $breadcrumb['name'] }}</a>
            @endforeach
        @endif
    </nav>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">{{ $folder->name ?? 'My Files' }}</h2>
        <div class="flex items-center space-x-2">
            <!-- Sorting & filtering bisa ditaruh di sini -->
        </div>
    </div>

    <!-- Recent Items (hanya di root) -->
    @if(!isset($folder))
    <section class="mb-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent</h3>
        @if(isset($recentItems) && $recentItems->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($recentItems as $item)
                    <a href="{{ $item->is_folder ? route('file.folder', $item) : route('file.preview', $item) }}" 
                       target="{{ $item->is_folder ? '_self' : '_blank' }}"
                       class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200 group">
                        <div class="flex items-center space-x-3">
                            <x-file-icon :item="$item" />
                            <span class="font-semibold text-gray-800 truncate flex-1">{{ $item->name }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 py-8">No recent files.</div>
        @endif
    </section>
    @endif

    <!-- File & Folder List -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-600 px-6 py-4 border-b border-gray-200">
            <div class="col-span-5 pl-4">Nama</div>
            <div class="col-span-3">Terakhir diubah</div>
            <div class="col-span-2">Ukuran</div>
            <div class="col-span-2"></div>
        </div>

        @forelse ($items as $item)
            <div class="group relative border-b border-gray-200 last:border-b-0">
                <a href="{{ $item->is_folder ? route('file.folder', $item) : route('file.preview', $item) }}" 
                   target="{{ $item->is_folder ? '_self' : '_blank' }}"
                   class="grid grid-cols-12 gap-4 items-center px-6 py-3 hover:bg-gray-50 transition-colors duration-200">
                    
                    <div class="col-span-5 flex items-center space-x-4">
                        <x-file-icon :item="$item" class="w-8 h-8" />
                        <span class="font-medium text-gray-800 truncate">{{ $item->name }}</span>
                    </div>

                    <div class="col-span-3 text-sm text-gray-500 hidden md:block">{{ $item->updated_at->format('d M, Y') }}</div>
                    <div class="col-span-2 text-sm text-gray-500 hidden md:block">{{ $item->is_folder ? '—' : \Illuminate\Support\Number::fileSize($item->size) }}</div>
                </a>
                
                <!-- Menu aksi (Download, Rename, Delete) -->
                <div class="absolute top-1/2 right-4 -translate-y-1/2 flex items-center justify-end space-x-2">
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click.stop.prevent="open = !open" 
                                class="text-gray-500 hover:text-gray-700 p-2 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bri-blue transition-colors opacity-0 group-hover:opacity-100">
                            <span class="material-symbols-outlined">more_vert</span>
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
                                <form action="{{ route('file.delete', $item) }}" method="POST" 
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
            <div class="p-8 text-center text-gray-500">
                <span class="material-symbols-outlined text-6xl text-gray-300">folder_open</span>
                <p class="mt-4 text-lg">{{ isset($folder) && $folder ? 'Folder ini kosong.' : 'Tidak ada file atau folder.' }}</p>
            </div>
        @endforelse
    </div>
</div>
