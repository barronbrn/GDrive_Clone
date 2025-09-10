<div x-init="currentFolderId = {{ $folder->id }}">
    @include('partials.breadcrumbs')

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
                   class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors shadow-md hover:shadow-lg">
                    <span>Name</span>
                    <span x-show="sortDirection === 'asc'" class="material-symbols-outlined ml-1 text-base">arrow_upward</span>
                    <span x-show="sortDirection === 'desc'" class="material-symbols-outlined ml-1 text-base">arrow_downward</span>
                </a>
                <form method="GET" action="{{ route('file.folder', $folder) }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'asc') }}">
                    <select name="modified" onchange="this.form.submit()" class="border-gray-300 rounded-lg focus:ring-2 focus:ring-bri-blue focus:border-bri-blue text-sm transition shadow-md hover:bg-gray-100 hover:shadow-lg">
                        <option value="">Modified</option>
                        <option value="today" @selected(request('modified') == 'today')>Today</option>
                        <option value="week" @selected(request('modified') == 'week')>Last 7 days</option>
                        <option value="month" @selected(request('modified') == 'month')>This month</option>
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
                        <x-file-actions-dropdown :item="$item" />
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
                        <x-file-actions-dropdown :item="$item" />
                    </div>
                </div>
            @endif
        @empty
            <div class="p-6 text-center text-gray-500">This folder is empty.</div>
        @endforelse
    </div>
</div>
