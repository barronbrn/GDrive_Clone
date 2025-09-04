<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // The main 'index' method is now handled by FileController.
    // This controller will handle the other dashboard-related views.

    public function recent(Request $request)
    {
        $recentItems = collect();
        if (Auth::check()) {
            $query = File::where('created_by', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(50);

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
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

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

            $recentItems = $query->get();
        }

    public function uploadFile(Request $request)
    {
        $request->validate(['file_upload' => 'required|file|max:1048576']);
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

    public function trash(Request $request)
    {
        $trashedItems = collect();
        if (Auth::check()) {
            $query = File::where('created_by', Auth::id())
                ->onlyTrashed()
                ->orderBy('deleted_at', 'desc');

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where('name', 'like', '%'.$search.'%');
            }

            $trashedItems = $query->get();
        }

        return view('trash', ['trashedItems' => $trashedItems]);
    }
}
