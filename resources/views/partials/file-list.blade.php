<div>






    <!-- File & Folder List -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-semibold text-gray-600 px-6 py-4 border-b border-gray-200">
            <div class="col-span-5 pl-4">Nama</div>
            <div class="col-span-3">Terakhir diubah</div>
            <div class="col-span-2">Ukuran</div>
            <div class="col-span-2"></div>
        </div>

        @forelse ($items as $item)
            <div class="group relative border-b border-gray-200 last:border-b-0">
                <a href="{{ $item->is_folder ? route('file.folder', $item) : route('file.preview', $item) }}" 
                   target="{{ $item->is_folder ? '_self' : '_blank' }}"
                   class="grid grid-cols-12 gap-4 items-center px-6 py-3 hover:bg-gray-200 z-10">
                    
                    <div class="col-span-5 flex items-center space-x-4">
                        <x-file-icon :item="$item" class="w-8 h-8" />
                        <span class="font-medium text-gray-800 truncate">{{ $item->name }}</span>
                    </div>

                    <div class="col-span-3 text-sm text-gray-500 hidden md:block">{{ $item->updated_at->format('d M, Y') }}</div>
                    <div class="col-span-2 text-sm text-gray-500 hidden md:block">{{ $item->is_folder ? '—' : \Illuminate\Support\Number::fileSize($item->size) }}</div>
                </a>
                
                <!-- Menu aksi (Unduh, Ganti Nama, Hapus) -->
                <div class="absolute top-1/2 right-4 -translate-y-1/2 flex items-center justify-end space-x-2 z-[9999]">
                    <x-file-actions-dropdown :item="$item" />
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">
                <span class="material-symbols-outlined text-6xl text-gray-300">folder_open</span>
                <p class="mt-4 text-lg">{{ isset($folder) && $folder ? 'Folder ini kosong.' : 'Tidak ada file atau folder.' }}</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    /* Pastikan item menu dropdown solid dan dapat diklik saat di-hover */
    .dropdown-menu-item:hover {
        background-color: #f3f4f6 !important; /* Tailwind's gray-100 */
        opacity: 1 !important;
        visibility: visible !important;
        display: block !important; /* Ensure it's not hidden by display:none */
    }
</style>

<style>
    /* Pastikan item menu dropdown solid dan dapat diklik saat di-hover */
    .dropdown-menu-item:hover {
        background-color: #f3f4f6 !important; /* Tailwind's gray-100 */
        opacity: 1 !important;
        visibility: visible !important;
        display: block !important; /* Ensure it's not hidden by display:none */
    }
</style>
