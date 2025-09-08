<div x-init="currentFolderId = {{ $folder->id }}">
<nav class="flex items-center text-sm font-medium text-gray-500 mb-6">
    <a href="{{ route('dashboard') }}" class="hover:text-bri-blue transition-colors">My Files</a>
    @foreach ($breadcrumbs as $breadcrumb)
        <span class="mx-2 text-gray-400">/</span>
        <a href="{{ $breadcrumb['route'] }}" class="hover:text-bri-blue transition-colors">{{ $breadcrumb['name'] }}</a>
    @endforeach
</nav>

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
            <form method="GET" action="{{ route('dashboard.folder', $folder) }}">
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

@include('partials.file-list', ['items' => $items])

</div>
