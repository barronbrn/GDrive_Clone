<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request, File $folder = null)
    {
        $items = collect();
        $breadcrumbs = collect();

        if (Auth::check()) {
            $user = Auth::user();

            $query = File::where('created_by', $user->id);

            if ($folder) {
                // Kalau buka folder → hanya isi folder tsb
                $query->where('parent_id', $folder->id);
                $breadcrumbs = $this->getBreadcrumbs($folder);
            } else {
                if ($request->filled('search')) {
                    // Kalau di root + search → cari semua level
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                } else {
                    // Kalau di root tanpa search → hanya anak root
                    $query->whereNull('parent_id');
                }
            }

            // Filter tambahan (modified)
            $this->applyFilters($query, $request);

            // Sorting
            $sortDirection = $request->input('sort_direction', 'asc');
            $items = $query
                ->orderBy('is_folder', 'desc')
                ->orderBy('name', $sortDirection)
                ->get();
        }

        return view('dashboard', [
            'items' => $items,
            'folder' => $folder,
            'breadcrumbs' => $breadcrumbs,
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
