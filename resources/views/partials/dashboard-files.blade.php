<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

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
        </div>

        <div x-data="{ layoutView: 'list' }"> {{-- Default to list --}}
            @include('partials.file-list', ['items' => $items])
        </div>
    </section>
</div>