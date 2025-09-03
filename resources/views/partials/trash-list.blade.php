<h2 class="text-2xl font-bold mb-4">Trash</h2>
<p class="text-sm text-gray-500 mb-4">Items in the trash will be permanently deleted after 30 days.</p>
<div class="bg-white rounded-lg shadow-sm">
    <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-500 px-6 py-4 border-b">
        <div class="col-span-5">Name</div>
        <div class="col-span-3">Time Remaining</div>
        <div class="col-span-4"></div>
    </div>
    @forelse ($items as $item)
        @php
            $deletionDate = $item->deleted_at->addDays(30);
            $daysRemaining = now()->diffInDays($deletionDate, false);
        @endphp
        <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-gray-50 border-b last:border-b-0 group">
            <div class="col-span-12 md:col-span-5 flex items-center space-x-3">
                <x-file-icon :item="$item" />
                <span class="font-medium truncate text-gray-500">{{ $item->name }}</span>
            </div>
            <div class="col-span-6 md:col-span-3 text-sm text-gray-500">
                @if($daysRemaining > 0)
                    Deletes in {{ $daysRemaining }} days
                @else
                    Deletes soon
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