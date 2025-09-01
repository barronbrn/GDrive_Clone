<?php

namespace App\Http\Controllers;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(File $folder = null)
    {
        $parentId = $folder ? $folder->id : null;

        // Keamanan sederhana: Pastikan user hanya bisa mengakses foldernya sendiri
        if ($folder && $folder->created_by !== Auth::id()) {
            abort(403, 'Unauthorized Access');
        }

        $files = File::where('created_by', Auth::id())
            ->where('parent_id', $parentId)
            ->orderBy('is_folder', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        return view('my-drive', compact('files', 'folder'));
    }

    public function createFolder(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        File::create([
            'name' => $request->name,
            'is_folder' => true,
            'created_by' => Auth::id(),
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Folder berhasil dibuat.');
    }

    public function uploadFile(Request $request)
    {
        $request->validate(['files.*' => 'required|file|max:102400']); // Batas 100MB per file

        foreach ($request->file('files') as $uploadedFile) {
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
        }

        return back()->with('success', 'File berhasil diupload.');
    }

    public function downloadFile(File $file)
    {
        if ($file->created_by !== Auth::id() || $file->is_folder) {
            abort(403);
        }
        return Storage::disk('private')->download($file->path, $file->name);
    }

    public function deleteFile(File $file)
    {
        if ($file->created_by !== Auth::id()) {
            abort(403);
        }

        if (!$file->is_folder) {
            Storage::disk('private')->delete($file->path);
        }

        $file->delete();
        return back()->with('success', 'Item berhasil dihapus.');
    }
}
