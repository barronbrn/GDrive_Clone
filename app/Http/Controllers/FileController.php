<?php

namespace App\Http\Controllers;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class FileController extends Controller
{
    public function getFileSystem(Request $request, ?File $folder = null)
    {
        if ($folder) {
            $this->authorize('view', $folder);
            $folder->touch('last_accessed_at');
        }

        $query = File::where('created_by', Auth::id());

        if ($request->filled('search')) {
            $this->applyFilters($query, $request);
        } else {
            $query->where('parent_id', $folder?->id);
            if ($request->filled('modified')) {
                $this->applyFilters($query, $request);
            }
        }

        $sortDirection = $request->input('sort_direction', 'asc');
        $items = $query->orderBy('is_folder', 'desc')
            ->orderBy('name', $sortDirection)
            ->get();

        $recentItems = collect();
        if (! $folder && !$request->filled('search')) {
            $recentItems = $this->getRecentItems(8);
        }

        return [
            'items' => $items,
            'recentItems' => $recentItems,
            'folder' => $request->filled('search') ? null : $folder,
            'breadcrumbs' => $request->filled('search') ? collect() : $this->getBreadcrumbs($folder),
        ];
    }

    public function getRecentItems($limit = 50)
    {
        return File::where('created_by', Auth::id())
            ->whereNotNull('last_accessed_at')
            ->orderBy('last_accessed_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTrashedItems()
    {
        return File::where('created_by', Auth::id())
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get();
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:files,id,created_by,'.Auth::id(),
        ]);

        File::create([
            'name' => $request->folder_name,
            'is_folder' => true,
            'created_by' => Auth::id(),
            'parent_id' => $request->parent_id,
            'last_accessed_at' => now(),
        ]);

        return response()->json(['success' => 'Folder berhasil dibuat.']);
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|file|max:512000', // 500MB limit per file
            'parent_id' => 'nullable|exists:files,id,created_by,'.Auth::id(),
        ], [
            'file_upload.max' => 'The file must not be greater than 500MB.',
        ]);

        if ($request->hasFile('file_upload')) {
            $uploadedFile = $request->file('file_upload');
            $path = $uploadedFile->store('files/'.Auth::id(), 'private');
            File::create([
                'name' => $uploadedFile->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $uploadedFile->getMimeType(),
                'size' => $uploadedFile->getSize(),
                'is_folder' => false,
                'created_by' => Auth::id(),
                'parent_id' => $request->parent_id,
                'last_accessed_at' => now(),
            ]);
        }

        return response()->json(['success' => 'File berhasil diupload.']);
    }

    public function update(Request $request, File $file)
    {
        $this->authorize('update', $file);

        $request->validate([
            'file_name' => 'required|string|max:255',
        ]);

        $file->update([
            'name' => $request->file_name,
        ]);

        return back()->with('success', 'Nama berhasil diperbarui.');
    }

    public function destroy(File $file)
    {
        $this->authorize('delete', $file);
        $file->delete(); // Soft delete

        return back()->with('success', 'Item berhasil dipindahkan ke Trash.');
    }

    public function download(File $file)
    {
        $this->authorize('download', $file);
        $file->touch('last_accessed_at');

        return Storage::disk('private')->download($file->path, $file->name);
    }

    public function downloadFolder(File $folder)
    {
        $this->authorize('download', $folder);

        $zip = new ZipArchive;
        $zipFileName = $folder->name.'.zip';
        $tempZipPath = storage_path('app/temp/'.$zipFileName);

        if ($zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->withErrors('Tidak dapat membuat file zip.');
        }

        $this->addFilesToZip($zip, $folder);
        $zip->close();

        return response()->download($tempZipPath)->deleteFileAfterSend(true);
    }

    public function duplicate(File $file)
    {
        $this->authorize('duplicate', $file);

        if ($file->is_folder) {
            $this->duplicateFolder($file, $file->parent_id);
        } else {
            $this->duplicateFile($file, $file->parent_id);
        }

        return back()->with('success', 'Item berhasil diduplikasi.');
    }

    public function preview(File $file)
    {
        $this->authorize('view', $file);
        $file->touch('last_accessed_at');
        $path = Storage::disk('private')->path($file->path);

        if (in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain'])) {
            return Response::file($path);
        }

        return $this->download($file);
    }

    public function restore($id)
    {
        $file = File::onlyTrashed()->where('created_by', Auth::id())->findOrFail($id);
        $this->authorize('restore', $file);
        $this->restoreWithChildren($file);

        return back()->with('success', 'Item berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        $file = File::onlyTrashed()->where('created_by', Auth::id())->findOrFail($id);
        $this->authorize('forceDelete', $file);
        $this->deleteFolderContents($file);
        $file->forceDelete();

        return back()->with('success', 'Item berhasil dihapus permanen.');
    }

    private function duplicateFile(File $originalFile, $newParentId)
    {
        $newPath = null;
        if ($originalFile->path) {
            $extension = pathinfo($originalFile->path, PATHINFO_EXTENSION);
            $newFileName = pathinfo($originalFile->name, PATHINFO_FILENAME).' (Copy)'.($extension ? '.'.$extension : '');
            $newPath = 'files/'.Auth::id().'/'.uniqid().'.'.$extension;
            Storage::disk('private')->copy($originalFile->path, $newPath);
        }

        File::create([
            'name' => $originalFile->name.' (Copy)',
            'path' => $newPath,
            'mime_type' => $originalFile->mime_type,
            'size' => $originalFile->size,
            'is_folder' => false,
            'created_by' => Auth::id(),
            'parent_id' => $newParentId,
        ]);
    }

    private function duplicateFolder(File $originalFolder, $newParentId)
    {
        $newFolder = File::create([
            'name' => $originalFolder->name.' (Copy)',
            'is_folder' => true,
            'created_by' => Auth::id(),
            'parent_id' => $newParentId,
        ]);

        $children = File::where('parent_id', $originalFolder->id)->get();
        foreach ($children as $child) {
            if ($child->is_folder) {
                $this->duplicateFolder($child, $newFolder->id);
            } else {
                $this->duplicateFile($child, $newFolder->id);
            }
        }
    }

    // Private Helper Methods

    private function deleteFolderContents(File $folder)
    {
        if (! $folder->is_folder) {
            Storage::disk('private')->delete($folder->path);

            return;
        }

        $children = File::where('parent_id', $folder->id)->withTrashed()->get();
        foreach ($children as $child) {
            $this->deleteFolderContents($child); // Recursive call
            $child->forceDelete();
        }
    }

    private function restoreWithChildren(File $file)
    {
        if ($file->is_folder) {
            $children = File::where('parent_id', $file->id)->onlyTrashed()->get();
            foreach ($children as $child) {
                $this->restoreWithChildren($child);
            }
        }
        $file->restore();
    }

    private function addFilesToZip(ZipArchive $zip, File $folder, $parentPath = '')
    {
        $items = File::where('parent_id', $folder->id)
            ->where('created_by', Auth::id())
            ->get();

        foreach ($items as $item) {
            $localPath = ltrim($parentPath.'/'.$item->name, '/');
            if ($item->is_folder) {
                $zip->addEmptyDir($localPath);
                $this->addFilesToZip($zip, $item, $localPath);
            } else {
                if (Storage::disk('private')->exists($item->path)) {
                    $zip->addFile(Storage::disk('private')->path($item->path), $localPath);
                }
            }
        }
    }

    private function getBreadcrumbs(?File $folder)
    {
        if (! $folder) {
            return collect();
        }

        $breadcrumbs = collect();
        $current = $folder;
        while ($current) {
            $breadcrumbs->prepend($current);
            $current = $current->parent; // Uses the new relationship
        }

        return $breadcrumbs->map(function (File $file) {
            return [
                'name' => $file->name,
                'route' => route('file.folder', ['folder' => $file]),
            ];
        });
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('modified')) {
            switch ($request->input('modified')) {
                case 'today':
                    $query->whereDate('updated_at', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('updated_at', Carbon::now()->month);
                    break;
            }
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%'.$search.'%');
        }
    }

}
