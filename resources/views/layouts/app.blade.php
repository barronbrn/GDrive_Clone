<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Dashboard' }} - DataBOX</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --bri-blue: #00529B;
            --bri-blue-dark: #003a70;
            --bg-color: #f8fafc;
            --card-bg-color: #ffffff;
            --text-color: #1f2937;
            --text-muted-color: #6b7280;
        }
        body { background-color: var(--bg-color); font-family: 'Inter', sans-serif; }
        .bg-bri-blue { background-color: var(--bri-blue); }
        .hover\:bg-bri-blue-dark:hover { background-color: var(--bri-blue-dark); }
        .text-bri-blue { color: var(--bri-blue); }
        .ring-bri-blue:focus { --tw-ring-color: var(--bri-blue); }
        .focus\:border-bri-blue:focus { border-color: var(--bri-blue); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-gray-800 antialiased" 
      x-data="{ 
          showCreateFolderModal: false, 
          showUploadFileModal: false,
          showEditModal: false,
          editItem: {},
          currentFolderId: null
      }">

    <div class="flex h-screen bg-gray-50">
        @include('partials.sidebar')

        <main class="flex-1 p-6 md:p-10 overflow-y-auto">
            @include('partials.header')
            <div class="mt-8">
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                        <div class="flex">
                            <div class="py-1"><span class="material-symbols-outlined mr-3">check_circle</span></div>
                            <div>
                                <p class="font-bold">Success</p>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                 @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                        <div class="flex">
                            <div class="py-1"><span class="material-symbols-outlined mr-3">error</span></div>
                            <div>
                                <p class="font-bold">Error</p>
                                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Modals -->
    <div x-show="showCreateFolderModal" 
         x-cloak 
         @keydown.escape.window="showCreateFolderModal = false" 
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300"
         x-transition:enter="ease-out"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="showCreateFolderModal = false" 
             class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md transform transition-all duration-300"
             x-show="showCreateFolderModal"
             x-transition:enter="ease-out"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Folder Baru</h3>
            <form action="{{ route('folder.create') }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" :value="currentFolderId">
                <div>
                    <label for="folder_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Folder</label>
                    <input type="text" id="folder_name" name="folder_name" placeholder="Masukkan nama folder" class="w-full border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-bri-blue focus:border-bri-blue transition" required>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="showCreateFolderModal = false" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark transition-colors">Buat</button>
                </div>
            </form>
        </div>
    </div>
    
    <div x-show="showUploadFileModal" 
         x-cloak 
         @keydown.escape.window="showUploadFileModal = false" 
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300"
         x-transition:enter="ease-out"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="showUploadFileModal = false" 
             class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md transform transition-all duration-300"
             x-show="showUploadFileModal"
             x-transition:enter="ease-out"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Upload File Baru</h3>
            <form action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="parent_id" :value="currentFolderId">
                <div>
                    <label for="file_upload" class="block text-sm font-medium text-gray-700 mb-2">Pilih File</label>
                    <input type="file" id="file_upload" name="file_upload" class="w-full border border-gray-300 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-bri-blue hover:file:bg-blue-100 transition" required>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="showUploadFileModal = false" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark transition-colors">Upload</button>
                </div>
            </form>
        </div>
    </div>
    
    <div x-show="showEditModal" 
         x-cloak 
         @keydown.escape.window="showEditModal = false" 
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300"
         x-transition:enter="ease-out"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.outside="showEditModal = false" 
             class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md transform transition-all duration-300"
             x-show="showEditModal"
             x-transition:enter="ease-out"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <h3 class="text-2xl font-bold mb-6 text-gray-800" x-text="`Ubah nama '${editItem.name}'`"></h3>
            <form :action="editItem.action" method="POST">
                @csrf
                @method('PATCH')
                <div>
                    <label for="file_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Baru</label>
                    <input type="text" id="file_name" name="file_name" x-model="editItem.name" class="w-full border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-bri-blue focus:border-bri-blue transition" required>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>