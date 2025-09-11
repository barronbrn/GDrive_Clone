<div x-data="{ open: false }" @click.outside="open = false" class="relative inline-block text-left">
    <button @click="open = !open" class="text-black p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-bri-blue">
        <span class="material-symbols">more_vert</span>
    </button>
    <div x-show="open"
         x-cloak
         class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
            <a href="{{ $item->is_folder ? route('folder.download', $item) : route('file.download', $item) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <span class="material-symbols-outlined mr-3">download</span>
                <span>Download</span>
            </a>
            <a href="#" @click.prevent="$dispatch('open-edit-modal', { id: {{ $item->id }}, name: '{{ $item->name }}', action: '{{ route('file.update', $item) }}' }); open = false" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <span class="material-symbols-outlined mr-3">drive_file_rename_outline</span>
                <span>Rename</span>
            </a>
            <form action="{{ route('file.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this {{ $item->is_folder ? 'folder' : 'file' }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                    <span class="material-symbols-outlined mr-3">delete</span>
                    <span>Delete</span>
                </button>
            </form>
        </div>
    </div>
</div>
