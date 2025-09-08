<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="space-y-12">
    @if(Auth::check() && isset($recentItems) && $recentItems->isNotEmpty())
    <section>
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Terakhir dibuka</h2>
        <div class="relative">
            <div class="overflow-x-auto pb-4 -mx-4 px-4 no-scrollbar">
                <div class="flex space-x-6">
                    @foreach ($recentItems as $item)
                        <a href="{{ $item->is_folder ? route('file.folder', $item) : route('file.preview', $item) }}" 
                           target="{{ $item->is_folder ? '_self' : '_blank' }}"
                           class="flex-shrink-0 w-72 bg-white p-5 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 ease-in-out border border-gray-200 group">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <x-file-icon :item="$item" class="w-12 h-12" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate text-lg">{{ $item->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $item->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <section>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Semua File</h2>
            <div x-data="{ sortDirection: '{{ request('sort_direction', 'asc') }}' }" class="flex items-center space-x-3 text-sm">
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

        @include('partials.file-list', ['items' => $items])
    </section>
</div>
