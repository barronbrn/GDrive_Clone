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

            <div class="group relative border-b border-gray-200 last:border-b-0">
                <a href="{{ $item->is_folder ? route('file.folder', $item) : route('file.preview', $item) }}" 
                   target="{{ $item->is_folder ? '_self' : '_blank' }}"
                   class="grid grid-cols-12 gap-4 items-center px-6 py-3 hover:bg-gray-200 z-10">
                    
                    <div class="col-span-5 flex items-center space-x-4">
                        <x-file-icon :item="$item" class="w-8 h-8" />
                        <span class="font-medium text-gray-800 truncate">{{ $item->name }}</span>
                    </div>

                    <div class="col-span-3 text-sm text-gray-500 hidden md:block">{{ $item->updated_at->format('d M, Y') }}</div>
                    <div class="col-span-2 text-sm text-gray-500 hidden md:block">{{ $item->is_folder ? '—' : \Illuminate\Support\Number::fileSize($item->size) }}</div>
                </a>
                
                <!-- Menu aksi (Unduh, Ganti Nama, Hapus) -->
                <div class="absolute top-1/2 right-4 -translate-y-1/2 flex items-center justify-end space-x-2 z-20">
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
    /* Pastikan item menu dropdown solid dan dapat diklik saat di-hover */
    .dropdown-menu-item:hover {
        background-color: #f3f4f6 !important; /* Tailwind's gray-100 */
        opacity: 1 !important;
        visibility: visible !important;
        display: block !important; /* Ensure it's not hidden by display:none */
    }
</style>

<style>
    /* Pastikan item menu dropdown solid dan dapat diklik saat di-hover */
    .dropdown-menu-item:hover {
        background-color: #f3f4f6 !important; /* Tailwind's gray-100 */
        opacity: 1 !important;
        visibility: visible !important;
        display: block !important; /* Ensure it's not hidden by display:none */
    }
</style>
