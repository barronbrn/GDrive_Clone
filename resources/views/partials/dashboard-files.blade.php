<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@include('partials.breadcrumbs')

<div class="space-y-12">
    @if(Auth::check() && isset($recentItems) && $recentItems->isNotEmpty())
    <section>
        <div class="relative">
            <div class="overflow-x-auto pb-4 -mx-4 px-4 no-scrollbar">
                <div class="flex space-x-6">
                    @foreach ($recentItems as $item)
                        <a href="{{ $item->is_folder ? route('file.folder', $item) : route('file.preview', $item) }}"
                           target="{{ $item->is_folder ? '_self' : '_blank' }}"
                           class="flex-shrink-0 w-80 bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 ease-in-out border border-gray-200 group">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <x-file-icon :item="$item" class="w-14 h-14" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 truncate text-xl">{{ $item->name }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $item->updated_at->diffForHumans() }}</p>
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
            <h2 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">All Files</h2>

            <div x-data="{ sortDirection: '{{ request('sort_direction', 'asc') }}' }" class="flex items-center space-x-3 text-sm">
                <a :href="'{{ url()->current() }}?sort_direction=' + (sortDirection === 'asc' ? 'desc' : 'asc') + '&modified={{ request('modified') }}&search={{ request('search') }}'" 
                   class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors shadow-md hover:shadow-lg">
                    <span>Nama</span>
                    <span x-show="sortDirection === 'asc'" class="material-symbols-outlined ml-1 text-base">arrow_upward</span>
                    <span x-show="sortDirection === 'desc'" class="material-symbols-outlined ml-1 text-base">arrow_downward</span>
                </a>
                <form method="GET" action="{{ route('file.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'asc') }}">
                    <select name="modified" onchange="this.form.submit()" class="border-gray-300 rounded-lg focus:ring-2 focus:ring-bri-blue focus:border-bri-blue text-sm transition shadow-md hover:bg-gray-100 hover:shadow-lg">
                        <option value="">Dimodifikasi</option>
                        <option value="today" @selected(request('modified') == 'today')>Hari ini</option>
                        <option value="week" @selected(request('modified') == 'week')>7 hari terakhir</option>
                        <option value="month" @selected(request('modified') == 'month')>Bulan ini</option>
                    </select>
                </form>
            </div>

        </div>

        <div x-data="{ layoutView: 'list' }"> {{-- Default to list --}}
            @include('partials.file-list', ['items' => $items])
        </div>
    </section>
</div>