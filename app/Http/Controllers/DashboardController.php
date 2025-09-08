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
    // Note: The original index method was moved to FileController.
    // This controller now handles other dashboard-related views like recent and trash.

    public function recent(Request $request)
    {
        $recentItems = collect();
        if (Auth::check()) {
            $query = File::where('created_by', Auth::id())
                ->orderBy('last_accessed_at', 'desc')
                ->limit(50);

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            }

            $recentItems = $query->get();
        }
        return view('recent', ['recentItems' => $recentItems]);
    }

    public function trash(Request $request)
    {
        $trashedItems = collect();
        if (Auth::check()) {
            $query = File::where('created_by', Auth::id())
                ->onlyTrashed()
                ->orderBy('deleted_at', 'desc');

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            }

            $trashedItems = $query->get();
        }
        return view('trash', ['trashedItems' => $trashedItems]);
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
        return back()->with('success', 'File uploaded successfully.');
    }

    public function updateName(Request $request, File $file)
    {
        if ($file->created_by !== Auth::id()) {
            abort(403);
        }
        $request->validate(['file_name' => 'required|string|max:255']);
        $file->update(['name' => $request->file_name]);
        return back()->with('success', 'Name updated successfully.');
    }

    public function delete(File $file)
    {
        if ($file->created_by !== Auth::id()) {
            abort(403);
        }
        $file->delete();
        return back()->with('success', 'Item moved to Trash.');
    }

    public function downloadFolder(File $folder)
    {
        if ($folder->created_by !== Auth::id() || !$folder->is_folder) {
            abort(403);
        }

        $zip = new ZipArchive();
        $zipFileName = $folder->name . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!Storage::exists('temp')) {
            Storage::makeDirectory('temp');
        }

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return back()->withErrors('Cannot create zip file.');
        }

        $zip->addEmptyDir($folder->name);
        $this->addFilesToZip($zip, $folder, $folder->name);
        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function addFilesToZip(ZipArchive $zip, File $folder, $parentPath = '')
    {
        $items = File::where('parent_id', $folder->id)
            ->where('created_by', Auth::id())
            ->get();

        foreach ($items as $item) {
            $localPath = ltrim($parentPath . '/' . $item->name, '/');

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

    public function restore($id)
    {
        $file = File::onlyTrashed()->where('created_by', Auth::id())->findOrFail($id);
        $file->restore();
        return back()->with('success', 'Item restored successfully.');
    }

    public function forceDelete($id)
    {
        $file = File::onlyTrashed()->where('created_by', Auth::id())->findOrFail($id);
        if (!$file->is_folder) {
            Storage::disk('private')->delete($file->path);
        }
        $file->forceDelete();
        return back()->with('success', 'Item permanently deleted.');
    }
}