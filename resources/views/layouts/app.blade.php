<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'Dashboard' }} - DataBOX</title>
    
    <!-- Memuat gaya Filled (Material+Symbols) dan Outlined (Material+Symbols+Outlined) -->
    {{-- <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" /> --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols&family=Material+Symbols+Outlined" rel="stylesheet" />
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root { --bri-blue: #00529B; --bri-blue-dark: #003a70; }
        body { background-color: #f4f7fc; }
        .bg-bri-blue { background-color: var(--bri-blue); }
        .hover\:bg-bri-blue-dark:hover { background-color: var(--bri-blue-dark); }
        .text-bri-blue { color: var(--bri-blue); }
        .ring-bri-blue:focus { --tw-ring-color: var(--bri-blue); }
        [x-cloak] { display: none !important; }

        /* Gaya untuk ikon Outlined (FILL: 0) */
        .material-symbols-outlined {
          font-variation-settings:
          'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }

        /* Gaya untuk ikon Filled (FILL: 1) */
        .material-symbols {
          font-variation-settings:
          'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
    </style>
</head>
<body class="font-sans text-gray-800 antialiased" 
      x-data="{ 
          showCreateFolderModal: false, 
          showUploadFileModal: false,
          showEditModal: false,
          editItem: {},
          currentFolderId: null
      }">

    <div class="flex h-screen bg-gray-100">
        @include('partials.sidebar')

        <main class="flex-1 p-8 overflow-y-auto">
            @include('partials.header')
            <div class="mt-8">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                 @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Modals -->
    <div x-show="showCreateFolderModal" x-cloak @keydown.escape.window="showCreateFolderModal = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.outside="showCreateFolderModal = false" 
             class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md"
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
                    data.append('parent_id', currentFolderId);

                    axios.post('{{ route('folder.create') }}', data, { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
                        .then(response => {
                            location.reload();
                        })
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
            <h3 class="text-xl font-semibold mb-4">Create New Folder</h3>
            <form @submit.prevent="createFolder(currentFolderId)">
                <input type="hidden" name="parent_id" :value="currentFolderId">
                <input type="text" name="folder_name" x-model="folderName" placeholder="Enter folder name" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 ring-bri-blue" required :disabled="creating">
                <div x-show="error" class="mt-2 text-sm text-red-600" x-text="error"></div>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" @click="showCreateFolderModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300" :disabled="creating">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark" :disabled="creating">
                        <span x-show="!creating">Create</span>
                        <span x-show="creating">Creating...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div x-show="showUploadFileModal" x-cloak @keydown.escape.window="showUploadFileModal = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.outside="showUploadFileModal = false" 
             class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" 
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
                    data.append('parent_id', currentFolderId);

                    this.uploading = true;
                    this.progress = 0;
                    this.error = '';

                    const config = {
                        onUploadProgress: (progressEvent) => {
                            this.progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        },
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    };

                    axios.post('{{ route('file.upload') }}', data, config)
                        .then(response => {
                            location.reload();
                        })
                        .catch(error => {
                            this.uploading = false;
                            this.progress = 0;
                            if (error.response && error.response.status === 422) {
                                this.error = error.response.data.errors.file_upload[0];
                            } else {
                                this.error = 'Upload failed. The file may be too large or a server error occurred.';
                            }
                        });
                }
             }">
            <h3 class="text-xl font-semibold mb-4">Upload New File</h3>
            <form @submit.prevent="uploadFile(currentFolderId)">
                <input type="file" name="file_upload" x-ref="fileInput" class="w-full border border-gray-300 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-bri-blue hover:file:bg-blue-100" required :disabled="uploading">
                
                <div x-show="error" class="mt-2 text-sm text-red-600" x-text="error"></div>

                <!-- Progress Bar -->
                <div x-show="uploading" class="mt-4 w-full bg-gray-200 rounded-full">
                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" :style="`width: ${progress}%`" x-text="progress > 0 ? `${progress}%` : ''"></div>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" @click="showUploadFileModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300" :disabled="uploading">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark" :disabled="uploading">
                        <span x-show="!uploading">Upload</span>
                        <span x-show="uploading">Uploading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div x-show="showEditModal" x-cloak @keydown.escape.window="showEditModal = false" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.outside="showEditModal = false" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4" x-text="`Rename '${editItem.name}'`"></h3>
            <form :action="editItem.action" method="POST">
                @csrf
                @method('PATCH')
                <input type="text" name="file_name" x-model="editItem.name" class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 ring-bri-blue" required>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-bri-blue text-white rounded-lg hover:bg-bri-blue-dark">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>