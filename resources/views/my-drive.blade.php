<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Drive') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Header dengan Tombol Aksi dan Breadcrumbs -->
                    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                        <div>
                            <nav class="flex items-center text-sm font-medium">
                                <a href="{{ route('my-drive') }}" class="text-blue-500 hover:underline">My Drive</a>
                                @if($folder)
                                    {{-- Nanti bisa ditambahkan loop untuk menampilkan semua parent folder --}}
                                    <span class="mx-2 text-gray-500">/</span>
                                    <span>{{ $folder->name }}</span>
                                @endif
                            </nav>
                             <h1 class="text-2xl font-bold text-white mt-2">
                                {{ $folder ? $folder->name : 'All files' }}
                             </h1>
                        </div>
                        <div class="flex space-x-2 mt-4 sm:mt-0">
                            <button type="button" onclick="showUploadModal()" class="flex items-center space-x-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <span>Upload</span>
                            </button>
                            <button type="button" onclick="showCreateFolderModal()" class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                <span>Create folder</span>
                            </button>
                        </div>
                    </header>

                    <!-- Notifikasi Sukses -->
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Area Tampilan File -->
                    @if ($files->isEmpty())
                        <!-- Tampilan saat folder kosong -->
                        <div class="bg-gray-900 rounded-lg p-6 min-h-[50vh] flex items-center justify-center">
                            <div class="text-center w-full max-w-lg">
                                <div class="border-2 border-dashed border-gray-700 rounded-lg p-12 flex flex-col items-center justify-center space-y-4">
                                    <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-gray-400">Folder ini kosong. Upload file pertama Anda.</p>
                                    <button class="bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md text-sm transition-colors" onclick="showUploadModal()">
                                        Upload File
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Tabel untuk menampilkan file dan folder -->
                        <div class="overflow-x-auto rounded-lg border border-gray-700">
                            <table class="min-w-full divide-y divide-gray-700">
                                <thead class="bg-gray-900">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nama</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Ukuran</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Terakhir Diubah</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-gray-700">
                                    @foreach ($files as $file)
                                        <tr class="hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                                <a href="{{ $file->is_folder ? route('my-drive', ['folder' => $file->id]) : route('file.download', $file) }}" class="flex items-center space-x-3 group">
                                                    @if ($file->is_folder)
                                                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                                        <span class="group-hover:underline">{{ $file->name }}</span>
                                                    @else
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0011.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                        <span class="group-hover:underline">{{ $file->name }}</span>
                                                    @endif
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                                {{ !$file->is_folder ? \Illuminate\Support\Number::fileSize($file->size, precision: 2) : '—' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $file->updated_at->format('d M Y, H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('file.delete', $file) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat diurungkan.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create Folder -->
    <div id="createFolderModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden items-center justify-center transition-opacity duration-300">
        <div class="bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-medium leading-6 text-white mb-4">Buat Folder Baru</h3>
            <form action="{{ route('folder.create') }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $folder?->id }}">
                <div>
                    <label for="name" class="sr-only">Nama Folder</label>
                    <input type="text" name="name" id="name" placeholder="Nama Folder" class="w-full bg-gray-700 text-white border-gray-600 rounded-md focus:ring-blue-500 focus:border-blue-500" required autofocus>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="hideCreateFolderModal()" class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md text-sm font-medium text-white">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-sm font-medium text-white">Buat Folder</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Upload File -->
    <div id="uploadFileModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden items-center justify-center transition-opacity duration-300">
        <div class="bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-medium leading-6 text-white mb-4">Upload File</h3>
            <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $folder?->id }}">
                <div class="mt-2">
                    <label for="files" class="sr-only">Pilih file</label>
                    <input type="file" name="files[]" id="files" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer" multiple required>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="hideUploadModal()" class="px-4 py-2 bg-gray-600 hover:bg-gray-500 rounded-md text-sm font-medium text-white">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md text-sm font-medium text-white">Upload</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const createFolderModal = document.getElementById('createFolderModal');
        const uploadFileModal = document.getElementById('uploadFileModal');

        function showCreateFolderModal() {
            createFolderModal.classList.remove('hidden');
            createFolderModal.classList.add('flex');
        }
        function hideCreateFolderModal() {
            createFolderModal.classList.add('hidden');
            createFolderModal.classList.remove('flex');
        }
        function showUploadModal() {
            uploadFileModal.classList.remove('hidden');
            uploadFileModal.classList.add('flex');
        }
        function hideUploadModal() {
            uploadFileModal.classList.add('hidden');
            uploadFileModal.classList.remove('flex');
        }

        // Menutup modal jika menekan tombol Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === "Escape") {
                hideCreateFolderModal();
                hideUploadModal();
            }
        });
    </script>
    @endpush

</x-app-layout>