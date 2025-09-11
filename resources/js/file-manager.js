
import axios from 'axios';

export default function fileManager(config) {
    return {
        // Sidebar state
        isSidebarOpen: localStorage.getItem('isSidebarOpen') === 'false' ? false : true,

        // Modal states
        showCreateFolderModal: false,
        showUploadFileModal: false,
        showEditModal: false,
        editItem: {},

        // Current folder context
        currentFolderId: config.currentFolderId || null,

        // --- Create Folder state & logic ---
        folderName: '',
        creating: false,
        createFolderError: '',
        createFolder() {
            if (!this.folderName.trim()) {
                this.createFolderError = 'Folder name is required.';
                return;
            }
            this.creating = true;
            this.createFolderError = '';

            const data = new FormData();
            data.append('folder_name', this.folderName);
            if (this.currentFolderId) {
                data.append('parent_id', this.currentFolderId);
            }

            axios.post(config.routes.createFolder, data, { headers: { 'X-CSRF-TOKEN': config.csrfToken }})
                .then(() => location.reload())
                .catch(error => {
                    this.creating = false;
                    if (error.response && error.response.status === 422) {
                        this.createFolderError = error.response.data.errors.folder_name[0];
                    } else {
                        this.createFolderError = 'Could not create folder. Please try again.';
                    }
                });
        },

        // --- Upload state & logic ---
        progress: 0,
        uploadError: '',
        uploading: false,
        totalChunks: 0,
        currentChunk: 0,
        uploadId: null,
        chunkSize: 2 * 1024 * 1024, // 2MB
        async uploadFile() {
            const fileInput = this.$refs.fileInput;
            if (fileInput.files.length === 0) {
                this.uploadError = 'Please select a file to upload.';
                return;
            }

            const file = fileInput.files[0];
            this.uploading = true;
            this.progress = 0;
            this.uploadError = '';
            this.currentChunk = 0;

            // Step 1: Initiate Upload
            try {
                const initiateResponse = await axios.post(config.routes.initiateUpload, {
                    filename: file.name,
                    total_size: file.size,
                    total_chunks: Math.ceil(file.size / this.chunkSize),
                    parent_id: this.currentFolderId
                }, {
                    headers: { 'X-CSRF-TOKEN': config.csrfToken }
                });
                this.uploadId = initiateResponse.data.upload_id;
                this.totalChunks = Math.ceil(file.size / this.chunkSize);
            } catch (initiateError) {
                this.uploading = false;
                this.uploadError = 'Failed to initiate upload: ' + (initiateError.response?.data?.message || initiateError.message);
                return;
            }

            // Step 2: Upload Chunks
            for (let i = 0; i < this.totalChunks; i++) {
                this.currentChunk = i + 1;
                const start = i * this.chunkSize;
                const end = Math.min(start + this.chunkSize, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('upload_id', this.uploadId);
                formData.append('chunk_index', i);
                formData.append('file_chunk', chunk, file.name + '.part' + i);

                try {
                    await axios.post(config.routes.uploadChunk, formData, {
                        headers: {
                            'X-CSRF-TOKEN': config.csrfToken,
                            'Content-Type': 'multipart/form-data'
                        },
                        onUploadProgress: (e) => {
                            this.progress = ((i * this.chunkSize) + e.loaded) / file.size * 100;
                        }
                    });
                } catch (chunkError) {
                    this.uploading = false;
                    this.uploadError = `Failed to upload chunk ${this.currentChunk} of ${this.totalChunks}: ` + (chunkError.response?.data?.message || chunkError.message);
                    return;
                }
            }

            // Step 3: Finalize Upload
            try {
                await axios.post(config.routes.finalizeUpload, {
                    upload_id: this.uploadId,
                    total_chunks: this.totalChunks
                }, {
                    headers: { 'X-CSRF-TOKEN': config.csrfToken }
                });
                location.reload();
            } catch (finalizeError) {
                this.uploading = false;
                this.uploadError = 'Failed to finalize upload: ' + (finalizeError.response?.data?.message || finalizeError.message);
            }
        }
    };
}
