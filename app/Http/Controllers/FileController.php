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
    // Menangani tampilan file dan folder di dasbor
    public function index(Request $request, ?File $folder = null)
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
        if (! $folder && ! $request->filled('search')) {
           $recentItems = File::where('created_by', Auth::id())
                ->whereNotNull('last_accessed_at')
                ->orderBy('last_accessed_at', 'desc')
                ->limit(8)
                ->get();
        }

        return view('dashboard', [
            'items' => $items,
            'recentItems' => $recentItems,
            'folder' => $request->filled('search') ? null : $folder,
            'breadcrumbs' => $request->filled('search') ? collect() : $this->getBreadcrumbs($folder),
        ]);
    }

    // Menangani pembuatan folder baru
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
        ]);

        return response()->json(['success' => 'Folder berhasil dibuat.']);
    }

    // Menangani unggahan file baru
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
            ]);
        }

        return response()->json(['success' => 'File berhasil diupload.']);
    }

    // Menangani pembaruan properti file/folder (misalnya, penggantian nama)
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

    // Menangani penghapusan sementara file atau folder (memindahkan ke sampah)
    public function destroy(File $file)
    {
        $this->authorize('delete', $file);
        $file->delete(); // Soft delete

        return back()->with('success', 'Item berhasil dipindahkan ke Trash.');
    }

    // Menangani pengunduhan file
    public function download(File $file)
    {
        $this->authorize('download', $file);
        $file->touch('last_accessed_at');

        return Storage::disk('private')->download($file->path, $file->name);
    }

    // Menangani pengunduhan folder sebagai file zip
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

    // Menangani pratinjau file
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

    // Menangani pemulihan file atau folder yang dihapus sementara dari sampah
    public function restore($id)
    {
        $file = File::onlyTrashed()->where('created_by', Auth::id())->findOrFail($id);
        $this->authorize('restore', $file);
        $this->restoreWithChildren($file);

        return back()->with('success', 'Item berhasil dikembalikan.');
    }

    // Menangani penghapusan permanen file atau folder dari sampah
    public function forceDelete($id)
    {
        $file = File::onlyTrashed()->where('created_by', Auth::id())->findOrFail($id);
        $this->authorize('forceDelete', $file);
        $this->deleteFolderContents($file);
        $file->forceDelete();

        return back()->with('success', 'Item berhasil dihapus permanen.');
    }

    // Metode Pembantu Pribadi

    // Secara rekursif menghapus isi folder (pembantu pribadi)
    private function deleteFolderContents(File $folder)
    {
        if (! $folder->is_folder) {
            Storage::disk('private')->delete($folder->path);

            return;
        }

        $children = File::where('parent_id', $folder->id)->withTrashed()->get();
        foreach ($children as $child) {
            $this->deleteFolderContents($child); // Panggilan rekursif
            $child->forceDelete();
        }
    }

    // Secara rekursif mengembalikan folder dan turunannya (pembantu pribadi)
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

    // Secara rekursif menambahkan file dalam folder ke ZipArchive (pembantu pribadi)
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

    // Menghasilkan breadcrumb untuk navigasi folder (pembantu pribadi)
    private function getBreadcrumbs(?File $folder)
    {
        if (! $folder) {
            return collect();
        }

        $breadcrumbs = collect();
        $current = $folder;
        while ($current) {
            $breadcrumbs->prepend($current);
            $current = $current->parent; // Menggunakan relasi baru
        }

        return $breadcrumbs->map(function (File $file) {
            return [
                'name' => $file->name,
                'route' => route('file.folder', ['folder' => $file]),
            ];
        });
    }

    // Menerapkan filter ke kueri file berdasarkan parameter permintaan (pembantu pribadi)
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
