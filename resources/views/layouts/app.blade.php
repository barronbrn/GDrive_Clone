<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Dashboard' }} - DataBOX</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols&family=Material+Symbols+Outlined" rel="stylesheet" />
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

        .material-symbols-outlined {
          font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
        .material-symbols {
          font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
    </style>
</head>
<body class="text-gray-800 antialiased" 
      x-data="{
          showCreateFolderModal: false,
          showUploadFileModal: false,
          showEditModal: false,
          editItem: {},
          currentFolderId: null,
          isSidebarOpen: localStorage.getItem('isSidebarOpen') === 'false' ? false : true
      }">

    <div class="flex h-screen bg-gray-50">
        <div x-show="isSidebarOpen" 
             class="flex-shrink-0 w-72 h-full"
             x-transition:enter="transition ease-in-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform -translate-x-full">
            @include('partials.sidebar')
        </div>

        <main class="flex-1 p-6 md:p-10 overflow-y-auto transition-all duration-400 ease-in-out">
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

    <!-- Create Folder Modal -->
    <div x-show="showCreateFolderModal" 
         x-cloak 
         @keydown.escape.window="showCreateFolderModal = false" 
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300">
        <div @click.outside="showCreateFolderModal = false" 
             class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md transform transition-all duration-300"
             x-data="{
                folderName: '',
                error: '',
                creating: false,
                createFolder(currentFolderId) {
                    if (!this.folderName.trim()) {
                        this.error = 'Folder name is required.';
                        return;
                    }
                    this.creating = true;
                    this.error = '';

                    const data = new FormData();
                    data.append('folder_name', this.folderName);
                    if (currentFolderId) {
                        data.append('parent_id', currentFolderId);
                    }

                    axios.post('{{ route('folder.create') }}', data, { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
                        .then(() => location.reload())
                        .catch(error => {
                            this.creating = false;
                            if (error.response && error.response.status === 422) {
                                this.error = error.response.data.errors.folder_name[0];
                            } else {
                                this.error = 'Could not create folder. Please try again.';
                            }
                        });
                }
             }">
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Create New Folder</h3>
            <form @submit.prevent="createFolder(currentFolderId)">
                <input type="text" name="folder_name" x-model="folderName" placeholder="Enter folder name" class="w-full border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-bri-blue focus:border-bri-blue transition" required :disabled="creating">
                <div x-show="error" class="mt-2 text-sm text-red-600" x-text="error"></div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="showCreateFolderModal = false" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300" :disabled="creating">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark" :disabled="creating">
                        <span x-show="!creating">Create</span>
                        <span x-show="creating">Creating...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Upload File Modal -->
    <div x-show="showUploadFileModal" 
         x-cloak 
         @keydown.escape.window="showUploadFileModal = false" 
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300">
        <div @click.outside="showUploadFileModal = false" 
             class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md transform transition-all duration-300"
             x-data="{
                progress: 0,
                error: '',
                uploading: false,
                uploadFile(currentFolderId) {
                    const fileInput = this.$refs.fileInput;
                    if (fileInput.files.length === 0) {
                        this.error = 'Please select a file to upload.';
                        return;
                    }
                    const data = new FormData();
                    data.append('file_upload', fileInput.files[0]);
                    if (currentFolderId) {
                        data.append('parent_id', currentFolderId);
                    }

                    this.uploading = true;
                    this.progress = 0;
                    this.error = '';

                    const config = {
                        onUploadProgress: (e) => {
                            this.progress = Math.round((e.loaded * 100) / e.total);
                        },
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    };

                    axios.post('{{ route('file.upload') }}', data, config)
                        .then(() => location.reload())
                        .catch(error => {
                            this.uploading = false;
                            this.progress = 0;
                            if (error.response && error.response.status === 422) {
                                this.error = error.response.data.errors.file_upload[0];
                            } else {
                                this.error = 'Upload failed. Please try again.';
                            }
                        });
                }
             }">
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Upload New File</h3>
            <form @submit.prevent="uploadFile(currentFolderId)">
                <input type="file" name="file_upload" x-ref="fileInput" class="w-full border border-gray-300 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-bri-blue hover:file:bg-blue-100 transition" required :disabled="uploading">
                <div x-show="error" class="mt-2 text-sm text-red-600" x-text="error"></div>

                <!-- Progress Bar -->
                <div x-show="uploading" class="mt-4 w-full bg-gray-200 rounded-full">
                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" :style="`width: ${progress}%`" x-text="progress > 0 ? `${progress}%` : ''"></div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="showUploadFileModal = false" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300" :disabled="uploading">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark" :disabled="uploading">
                        <span x-show="!uploading">Upload</span>
                        <span x-show="uploading">Uploading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showEditModal" 
         x-cloak 
         @keydown.escape.window="showEditModal = false" 
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300">
        <div @click.outside="showEditModal = false" 
             class="bg-white rounded-xl shadow-xl p-8 w-full max-w-md transform transition-all duration-300"
             x-show="showEditModal">
            <h3 class="text-2xl font-bold mb-6 text-gray-800" x-text="`Rename '${editItem.name}'`"></h3>
            <form :action="editItem.action" method="POST">
                @csrf
                @method('PATCH')
                <div>
                    <label for="file_name" class="block text-sm font-medium text-gray-700 mb-2">New Name</label>
                    <input type="text" id="file_name" name="file_name" x-model="editItem.name" class="w-full border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-bri-blue focus:border-bri-blue transition" required>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark">Save</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
