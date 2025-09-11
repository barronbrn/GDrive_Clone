<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use ZipArchive;

class DashboardController extends Controller
{
    // Catatan: Metode indeks asli dipindahkan ke FileController.
    // Pengontrol ini sekarang menangani tampilan terkait dasbor lainnya seperti terbaru dan sampah.

    // Menampilkan item yang baru diakses
    public function recent(Request $request, ?File $folder = null)
    {
        $query = File::where('created_by', Auth::id());
        $breadcrumbs = [['name' => 'Recent', 'route' => route('recent')]];

        if ($folder) {
            $this->authorize('view', $folder); // Ensure user can view this folder
            $folder->touch('last_accessed_at'); // Update last accessed time

            $query->where('parent_id', $folder->id);
            $items = $query->get();

            // Generate breadcrumbs for the folder path
            $current = $folder;
            while ($current) {
                array_unshift($breadcrumbs, ['name' => $current->name, 'route' => route('recent', $current)]);
                $current = $current->parent;
            }
        } else {
            $query->orderBy('last_accessed_at', 'desc')->limit(50);
            $items = $query->get();
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        return view('recent', compact('items', 'breadcrumbs', 'folder'));
    }

    // Menampilkan item yang dibuang
    public function trash(Request $request)
    {
        $trashedItems = collect();
        if (Auth::check()) {
            $query = File::where('created_by', Auth::id())
                ->onlyTrashed()
                ->orderBy('deleted_at', 'desc');

            if ($request->filled('search')) {
                $query->where('name', 'like', '%'.$request->input('search').'%');
            }

            $trashedItems = $query->get();
        }

        $breadcrumbs = [['name' => 'Trash']];

        return view('trash', compact('trashedItems', 'breadcrumbs'));
    }
}