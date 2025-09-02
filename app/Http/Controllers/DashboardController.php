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

            $folderQuery = File::where('created_by', $user->id)
                ->where('is_folder', true)
                ->where('parent_id', $currentFolderId)
                ->orderBy($sortField, $sortDirection);

            $fileQuery = File::where('created_by', $user->id)
                ->where('is_folder', false)
                ->where('parent_id', $currentFolderId)
                ->orderBy($sortField, $sortDirection);

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

    // ... sisa method (createFolder, uploadFile, delete, dll) tidak berubah ...
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
        return view('dashboard', ['folders' => collect(), 'files' => collect(), 'folder' => null, 'breadcrumbs' => collect()]);
    }
    public function trash()
    {
        return view('dashboard', ['folders' => collect(), 'files' => collect(), 'folder' => null, 'breadcrumbs' => collect()]);
    }
}
