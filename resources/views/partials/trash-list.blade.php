<h2 class="text-2xl font-bold mb-4">Trash</h2>
<p class="text-sm text-gray-500 mb-4">Items in the trash will be permanently deleted after 30 days.</p>
<div class="bg-white rounded-lg shadow-sm">
    @forelse ($items as $item)
        @php
            $deletionDate = $item->deleted_at->addDays(30);
            $daysRemaining = now()->diffInDays($deletionDate, false);
        @endphp
        <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-gray-50 border-b last:border-b-0 group">
            <div class="col-span-12 md:col-span-5 flex items-center space-x-3">
                @if($item->is_folder)
                    <div class="p-2 bg-gray-200 rounded-lg"><svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg></div>
                @else
                    <div class="p-2 bg-gray-200 rounded-lg"><svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0011.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div>
                @endif
                <span class="font-medium truncate">{{ $item->name }}</span>
            </div>
            <div class="col-span-6 md:col-span-3 text-sm text-gray-500">
                @if($daysRemaining > 0)
                    Deletes in {{ $daysRemaining }} days
                @else
                    Scheduled for deletion
                @endif
            </div>
            <div class="col-span-6 md:col-span-4 text-right flex items-center justify-end space-x-4">
                <form action="{{ route('trash.restore', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-green-600 hover:underline">Restore</button>
                </form>
                <form action="{{ route('trash.forceDelete', $item->id) }}" method="POST" onsubmit="return confirm('This action is irreversible. Are you sure you want to permanently delete this item?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:underline">Delete Forever</button>
                </form>
            </div>
        </div>
    @empty
        <p class="p-6 text-center text-gray-500">The trash is empty.</p>
    @endforelse
</div>