<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="space-y-8">
    @if(Auth::check() && isset($recentItems) && $recentItems->isNotEmpty())
    <section>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Terakhir dibuka</h2>
        <div class="relative">
            <div class="overflow-x-auto pb-4 no-scrollbar">
                <div class="flex space-x-6">
                    @foreach ($recentItems as $item)
                        <a href="{{ $item->is_folder ? route('file.folder', $item) : route('file.preview', $item) }}" 
                           target="{{ $item->is_folder ? '_self' : '_blank' }}"
                           class="flex-shrink-0 w-64 bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200 group">
                            <div class="flex items-center space-x-3">
                                <x-file-icon :item="$item" />
                                <span class="font-medium truncate">{{ $item->name }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <section>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 sm:mb-0">All Files</h2>
            <div x-data="{ sortDirection: '{{ request('sort_direction', 'asc') }}' }" class="flex items-center space-x-2 text-sm">
                <a :href="'{{ url()->current() }}?sort_direction=' + (sortDirection === 'asc' ? 'desc' : 'asc') + '&modified={{ request('modified') }}&search={{ request('search') }}'" 
                   class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <span>Nama</span>
                    <span x-show="sortDirection === 'asc'" class="material-symbols-outlined ml-1 text-sm">arrow_upward</span>
                    <span x-show="sortDirection === 'desc'" class="material-symbols-outlined ml-1 text-sm">arrow_downward</span>
                </a>
                <form method="GET" action="{{ route('file.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'asc') }}">
                    <select name="modified" onchange="this.form.submit()" class="border-gray-300 rounded-lg focus:ring-bri-blue focus:border-bri-blue text-sm">
                        <option value="">Dimodifikasi</option>
                        <option value="today" @selected(request('modified') == 'today')>Hari ini</option>
                        <option value="week" @selected(request('modified') == 'week')>7 hari terakhir</option>
                        <option value="month" @selected(request('modified') == 'month')>Bulan ini</option>
                    </select>
                </form>
            </div>
        </div>

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
                            <div x-data="{ open: false }" class="relative inline-block text-left"><button @click="open = !open" class="text-black p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-bri-blue"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"></path></svg></button><div x-show="open" @click.outside="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20"><div class="py-1"><a href="{{ route('folder.download', $item) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Download</a><a href="#" @click.prevent="showEditModal = true; editItem = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', action: '{{ Route::has('file.update') ? route('file.update', $item) : '#' }}' }; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rename</a><form action="{{ Route::has('file.delete') ? route('file.delete', $item) : '#' }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus folder ini?')">@csrf @method('DELETE')<button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button></form></div></div></div>
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
                           <div x-data="{ open: false }" class="relative inline-block text-left"><button @click="open = !open" class="text-black p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-bri-blue"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"></path></svg></button><div x-show="open" @click.outside="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20"><div class="py-1"><a href="{{ Route::has('file.download') ? route('file.download', $item) : '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Download</a><a href="#" @click.prevent="showEditModal = true; editItem = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', action: '{{ Route::has('file.update') ? route('file.update', $item) : '#' }}' }; open = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Rename</a><form action="{{ Route::has('file.delete') ? route('file.delete', $item) : '#' }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini?')">@csrf @method('DELETE')<button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete</button></form></div></div></div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="p-6 text-center text-gray-500">No files or folders.</div>
            @endforelse
        </div>
    </section>
</div>