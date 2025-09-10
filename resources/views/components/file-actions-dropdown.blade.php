<x-dropdown align="right" width="48" x-cloak class="z-[9999]">
    <x-slot name="trigger">
        <button x-ref="trigger" class="text-black hover:text-gray-700 p-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bri-blue transition-colors opacity-100"
                @click.stop="open = ! open; $dispatch('close-other-dropdowns', { id: id })">
            <span class="material-symbols">more_vert</span>
        </button>
    </x-slot>

    <x-slot name="content">
        <template x-teleport="body">
            <div x-show="open"
                 x-cloak
                 x-ref="content"
                 class="origin-top-right absolute mt-2 w-56 rounded-md shadow-lg !bg-white ring-1 ring-black ring-opacity-5 z-[9999] focus:outline-none pointer-events-auto"
                 :style="`top: ${$refs.trigger.getBoundingClientRect().bottom + window.scrollY}px; left: ${$refs.trigger.getBoundingClientRect().left + window.scrollX - 224}px; background-color: white !important; opacity: 1 !important; will-change: transform, opacity; transform: translateZ(0);`"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform scale-95"
                 x-transition:enter-end="transform scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="transform scale-100"
                 x-transition:leave-end="transform scale-95">
                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                    <a href="{{ $item->is_folder ? route('folder.download', $item) : route('file.download', $item) }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 bg-white hover:bg-gray-100 dropdown-menu-item" role="menuitem">
                        <span class="material-symbols-outlined mr-3">download</span>
                        <span>Download</span>
                    </a>
                    <a href="#"
                       @click.prevent="$dispatch('open-edit-modal', { id: {{ $item->id }}, name: '{{ $item->name }}', action: '{{ route('file.update', $item) }}' }); open = false"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 bg-white hover:bg-gray-100 dropdown-menu-item" role="menuitem">
                        <span class="material-symbols-outlined mr-3">drive_file_rename_outline</span>
                        <span>Rename</span>
                    </a>
                    <form action="{{ route('file.destroy', $item) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this {{ $item->is_folder ? 'folder' : 'file' }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 bg-white hover:bg-gray-100 dropdown-menu-item" role="menuitem">
                            <span class="material-symbols-outlined mr-3">delete</span>
                            <span>Delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </x-slot>
</x-dropdown>

<style>
    /* Pastikan item menu dropdown solid dan dapat diklik saat di-hover */
    .dropdown-menu-item:hover {
        background-color: #f3f4f6 !important; /* Tailwind's gray-100 */
        opacity: 1 !important;
        visibility: visible !important;
        display: block !important; /* Ensure it's not hidden by display:none */
    }
</style>