<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use ZipArchive;

class DashboardController extends Controller
{
    public function index(Request $request, File $folder = null)
    {
        if (!Auth::check()) {
            return view('dashboard', [
                'items' => collect(),
                'recentItems' => collect(),
                'folder' => null,
                'breadcrumbs' => collect()
            ]);
        }

        $user = Auth::user();
        $this->authorizeFolderAccess($folder);

        if ($folder) {
            $folder->touch('last_accessed_at');
        }

        // --- Logika Utama untuk mengambil daftar file/folder ---
        $baseQuery = File::where('created_by', $user->id)
            ->where('parent_id', $folder?->id);

        // Terapkan filter pencarian & modifikasi
        $this->applyFilters($baseQuery, $request);

        // Terapkan pengurutan
        $sortDirection = $request->input('sort_direction', 'asc');
        $items = $baseQuery->orderBy('is_folder', 'desc')
            ->orderBy('name', $sortDirection)
            ->get();

        // --- Logika untuk mengambil "Terakhir Dibuka" (HANYA untuk halaman utama) ---
        $recentItems = collect();
        if (!$folder) {
            $recentItems = File::where('created_by', $user->id)
                ->whereNotNull('last_accessed_at')
                ->orderBy('last_accessed_at', 'desc')
                ->limit(8)
                ->get();
        }

        return view('dashboard', [
            'items' => $items,
            'recentItems' => $recentItems,
            'folder' => $folder,
            'breadcrumbs' => $this->getBreadcrumbs($folder),
        ]);
    }

    public function recent()
    {
        $recentItems = collect();
        if (Auth::check()) {
            $recentItems = File::where('created_by', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();
        }
        return view('recent', ['recentItems' => $recentItems]);
    }

    public function trash()
    {
        $trashedItems = collect();
        if (Auth::check()) {
            $trashedItems = File::where('created_by', Auth::id())
                ->onlyTrashed()
                ->orderBy('deleted_at', 'desc')
                ->get();
        }
        return view('trash', ['trashedItems' => $trashedItems]);
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
    }

    public function restore($id)
    {
        $file = File::onlyTrashed()
            ->where('created_by', Auth::id())
            ->findOrFail($id);

        $file->restore();
        return back()->with('success', 'Item berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        $file = File::onlyTrashed()
            ->where('created_by', Auth::id())
            ->findOrFail($id);

        if (!$file->is_folder) {
            Storage::disk('private')->delete($file->path);
        }
        $file->forceDelete();
        return back()->with('success', 'Item berhasil dihapus permanen.');
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

    public function updateName(Request $request, File $file)
    {
        if ($file->created_by !== Auth::id()) {
            abort(403);
        }
        $request->validate([
            'file_name' => 'required|string|max:255',
        ]);
        $file->update([
            'name' => $request->file_name,
        ]);
        return back()->with('success', 'Nama berhasil diperbarui.');
    }

    public function download(File $file)
    {
        $this->authorizeFileAccess($file);
        $file->touch('last_accessed_at');
        return Storage::disk('private')->download($file->path, $file->name);
    }

    public function downloadFolder(File $folder)
    {
        // Keamanan: Pastikan user adalah pemilik folder dan item ini adalah folder
        if ($folder->created_by !== Auth::id() || !$folder->is_folder) {
            abort(403);
        }

        $zip = new ZipArchive();
        $zipFileName = $folder->name . '.zip';

        // Buat file zip sementara di storage
        $zipPath = storage_path('app/temp/' . $zipFileName);
        if (!Storage::exists('temp')) {
            Storage::makeDirectory('temp');
        }

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return back()->withErrors('Cannot create zip file.');
        }

        // Tambahkan folder utama ke dalam zip
        $zip->addEmptyDir($folder->name);

        // Panggil fungsi rekursif untuk menambahkan semua file dan subfolder
        $this->addFilesToZip($zip, $folder, $folder->name);

        $zip->close();

        // Kirim file zip ke user dan hapus file sementara setelahnya
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Fungsi rekursif untuk menambahkan file dan folder ke dalam arsip zip.
     */
    private function addFilesToZip(ZipArchive $zip, File $folder, $parentPath = '')
    {
        // PERBAIKAN: Ambil semua item (file & folder) yang parent_id-nya adalah ID folder saat ini
        $items = File::where('parent_id', $folder->id)
            ->where('created_by', Auth::id())
            ->get();

        foreach ($items as $item) {
            // Buat path relatif di dalam zip
            $localPath = ltrim($parentPath . '/' . $item->name, '/');

            if ($item->is_folder) {
                // Jika item adalah folder, buat direktori di dalam zip dan panggil fungsi ini lagi untuk folder tersebut
                $zip->addEmptyDir($localPath);
                $this->addFilesToZip($zip, $item, $localPath);
            } else {
                // Jika item adalah file, tambahkan ke dalam zip dari storage privat
                if (Storage::disk('private')->exists($item->path)) {
                    $zip->addFile(Storage::disk('private')->path($item->path), $localPath);
                }
            }
        }
    }

    public function preview(File $file)
    {
        $this->authorizeFileAccess($file);
        $file->touch('last_accessed_at');
        $path = Storage::disk('private')->path($file->path);
        if (in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain'])) {
            return Response::file($path);
        }
        return $this->download($file);
    }

    private function getBreadcrumbs($folder)
    {
        if (!$folder) return collect();
        $breadcrumbs = collect();
        $current = $folder;
        while ($current) {
            $breadcrumbs->prepend([
                'name' => $current->name,
                'route' => route('dashboard.folder', $current)
            ]);
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
}
