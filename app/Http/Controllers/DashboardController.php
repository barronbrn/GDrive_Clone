<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class DashboardController extends Controller
{
    /**
     * Menampilkan file dan folder berdasarkan folder saat ini.
     */
    public function index(Request $request, File $folder = null)
    {
        $folders = collect();
        $files = collect();
        $breadcrumbs = $this->getBreadcrumbs($folder);
        if (Auth::check()) {
            $user = Auth::user();
            $this->authorizeFolderAccess($folder);
            $searchQuery = $request->input('search');
            $sortBy = $request->input('sort_by', 'name_asc');
            [$sortField, $sortDirection] = $this->parseSortBy($sortBy);
            $currentFolderId = $folder ? $folder->id : null;
            $folderQuery = File::where('created_by', $user->id)->where('is_folder', true)->where('parent_id', $currentFolderId)->orderBy($sortField, $sortDirection);
            $fileQuery = File::where('created_by', $user->id)->where('is_folder', false)->where('parent_id', $currentFolderId)->orderBy($sortField, $sortDirection);
            if ($searchQuery) {
                $folderQuery->where('name', 'like', '%' . $searchQuery . '%');
                $fileQuery->where('name', 'like', '%' . $searchQuery . '%');
            }
            $folders = $folderQuery->get();
            $files = $fileQuery->get();
        }
        return view('dashboard', compact('folders', 'files', 'folder', 'breadcrumbs'));
    }

    /**
     * Menangani download file.
     */
    public function download(File $file)
    {
        $this->authorizeFileAccess($file);
        return Storage::disk('private')->download($file->path, $file->name);
    }

    /**
     * Menampilkan pratinjau file.
     */
    public function preview(File $file)
    {
        $this->authorizeFileAccess($file);

        $path = Storage::disk('private')->path($file->path);

        if (in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain'])) {
            return Response::file($path);
        }

        return $this->download($file);
    }

    public function updateName(Request $request, File $file)
    {
        // 1. Keamanan: Pastikan user adalah pemilik item
        if ($file->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Validasi: Pastikan nama baru valid
        $request->validate([
            'file_name' => 'required|string|max:255',
        ]);

        // 3. Update: Simpan nama baru ke database
        $file->update([
            'name' => $request->file_name,
        ]);

        // 4. Feedback: Kirim pesan sukses
        return back()->with('success', 'Nama berhasil diperbarui.');
    }

    public function createFolder(Request $request)
    {
        $request->validate(['folder_name' => 'required|string|max:255']);
        File::create([
            'name' => $request->folder_name,
            'is_folder' => true,
            'created_by' => Auth::id(),
            'parent_id' => $request->parent_id,
        ]);
        return back()->with('success', 'Folder berhasil dibuat.');
    }
    public function uploadFile(Request $request)
    {
        $request->validate(['file_upload' => 'required|file|max:20480']);
        $uploadedFile = $request->file('file_upload');
        $path = $uploadedFile->store('files/' . Auth::id(), 'private');
        File::create([
            'name' => $uploadedFile->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'is_folder' => false,
            'created_by' => Auth::id(),
            'parent_id' => $request->parent_id,
        ]);
        return back()->with('success', 'File berhasil diupload.');
    }
    public function delete(File $file)
    {
        if ($file->created_by !== Auth::id()) abort(403);
        $file->delete();
        return back()->with('success', 'Item berhasil dipindahkan ke Trash.');
    }
    private function getBreadcrumbs($folder)
    {
        if (!$folder) return collect();
        $breadcrumbs = collect();
        $current = $folder;
        while ($current) {
            $breadcrumbs->prepend(['name' => $current->name, 'route' => route('dashboard.folder', $current)]);
            $current = File::find($current->parent_id);
        }
        return $breadcrumbs;
    }
    private function authorizeFolderAccess($folder)
    {
        if ($folder && $folder->created_by !== Auth::id()) {
            abort(403, 'Unauthorized Access');
        }
    }
    private function authorizeFileAccess(File $file)
    {
        if ($file->is_folder || $file->created_by !== Auth::id()) {
            abort(403);
        }
    }
    private function parseSortBy($sortBy)
    {
        switch ($sortBy) {
            case 'name_desc':
                return ['name', 'desc'];
            case 'date_desc':
                return ['created_at', 'desc'];
            case 'date_asc':
                return ['created_at', 'asc'];
            case 'name_asc':
            default:
                return ['name', 'asc'];
        }
    }


    public function recent()
    {
        $recentItems = collect();
        if (Auth::check()) {
            // Ambil semua file & folder milik user, urutkan berdasarkan yang terbaru dibuat
            $recentItems = File::where('created_by', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(50) // Batasi hanya 50 item terbaru untuk performa
                ->get();
        }
        // Pastikan variabel folder dan breadcrumbs selalu ada
        return view('dashboard', [
            'recentItems' => $recentItems,
            'folder' => null,
            'breadcrumbs' => collect()
        ]);
    }

    public function trash()
    {
        $trashedItems = collect();
        if (Auth::check()) {
            // Ambil semua file & folder milik user yang sudah di-soft delete
            $trashedItems = File::where('created_by', Auth::id())
                ->onlyTrashed() // <-- Kunci utamanya ada di sini
                ->orderBy('deleted_at', 'desc')
                ->get();
        }
        // Pastikan variabel folder dan breadcrumbs selalu ada
        return view('dashboard', [
            'trashedItems' => $trashedItems,
            'folder' => null,
            'breadcrumbs' => collect()
        ]);
    }

    public function restore($id)
    {
        // Cari file HANYA di dalam trash
        $file = File::onlyTrashed()->where('created_by', Auth::id())->findOrFail($id);
        $file->restore();
        return back()->with('success', 'Item berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        // Cari file HANYA di dalam trash
        $file = File::onlyTrashed()->where('created_by', Auth::id())->findOrFail($id);

        if (!$file->is_folder) {
            Storage::disk('private')->delete($file->path);
        }

        $file->forceDelete();
        return back()->with('success', 'Item berhasil dihapus permanen.');
    }
}
