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
            <!-- Sorting and filtering controls can go here -->
        </div>
    </div>

    <!-- Recent Items (only on root) -->
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


    <!-- File and Folder List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-600 px-6 py-4 border-b border-gray-200">
            <div class="col-span-6">Name</div>
            <div class="col-span-3">Last Modified</div>
            <div class="col-span-2">Size</div>
            <div class="col-span-1"></div>
        </div>

        @forelse ($items as $item)
            <div class="grid grid-cols-12 gap-4 items-center px-6 py-3 hover:bg-gray-50 border-b border-gray-200 last:border-b-0 group">
                <a href="{{ $item->is_folder ? route('file.folder', $item) : route('file.preview', $item) }}" 
                   target="{{ $item->is_folder ? '_self' : '_blank' }}"
                   class="col-span-11 md:col-span-6 flex items-center space-x-4">
                    <x-file-icon :item="$item" />
                    <span class="font-medium truncate">{{ $item->name }}</span>
                </a>
                <div class="hidden md:block col-span-3 text-sm text-gray-500">{{ $item->updated_at->format('d M, Y') }}</div>
                <div class="hidden md:block col-span-2 text-sm text-gray-500">{{ $item->is_folder ? '—' : \Illuminate\Support\Number::fileSize($item->size) }}</div>
                <div class="col-span-1 text-right">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="text-gray-500 p-1 rounded-full focus:outline-none hover:bg-gray-200 transition opacity-0 group-hover:opacity-100">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                            <div class="py-1">
                                <a href="{{ $item->is_folder ? route('folder.download', $item) : route('file.download', $item) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Download</a>
                                <a href="#" @click.prevent="showEditModal = true; editItem = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', action: '{{ route('file.update', $item) }}' }; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rename</a>
                                <form action="{{ route('file.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">This folder is empty.</div>
        @endforelse
    </div>
</div>