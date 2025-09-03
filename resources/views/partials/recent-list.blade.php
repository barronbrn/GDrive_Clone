<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
    <h2 class="text-xl font-semibold text-gray-700 mb-4 sm:mb-0">Terakhir dibuat</h2>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-500 px-6 py-4 border-b">
        <div class="col-span-6">Name</div>
        <div class="col-span-3">Type</div>
        <div class="col-span-3">Date Created</div>
    </div>
    @forelse ($items as $item)
        <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-gray-50 border-b last:border-b-0">
            <div class="col-span-12 md:col-span-6 flex items-center space-x-3">
                @if($item->is_folder)
                    <a href="{{ route('dashboard.folder', $item) }}" class="flex items-center space-x-3">
                        <x-file-icon :item="$item" />
                        <span class="font-medium truncate">{{ $item->name }}</span>
                    </a>
                @else
                    <a href="{{ route('file.preview', $item) }}" target="_blank" class="flex items-center space-x-3">
                        <x-file-icon :item="$item" />
                        <span class="font-medium truncate">{{ $item->name }}</span>
                    </a>
                @endif
            </div>
            <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $item->is_folder ? 'Folder' : 'File' }}</div>
            <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $item->created_at->format('d M, Y H:i') }}</div>
        </div>
    @empty
        <div class="p-6 text-center text-gray-500">
            <p>Tidak ada item yang baru dibuat.</p>
        </div>
    @endforelse
</div>