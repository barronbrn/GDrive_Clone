<h2 class="text-2xl font-bold mb-4">Recent Items</h2>
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
                    <div class="p-2 bg-blue-100 rounded-lg"><svg class="w-5 h-5 text-bri-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg></div>
                    <a href="{{ route('dashboard.folder', $item) }}" class="font-medium truncate">{{ $item->name }}</a>
                @else
                    <x-file-icon :item="$item" />
                    <a href="{{ route('file.preview', $item) }}" target="_blank" class="font-medium truncate">{{ $item->name }}</a>
                @endif
            </div>
            <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $item->is_folder ? 'Folder' : 'File' }}</div>
            <div class="col-span-6 md:col-span-3 text-sm text-gray-500">{{ $item->created_at->format('d M, Y H:i') }}</div>
        </div>
    @empty
        <p class="p-6 text-center text-gray-500">No recent items found.</p>
    @endforelse
</div>