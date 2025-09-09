<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $fileController;

    public function __construct(FileController $fileController)
    {
        $this->fileController = $fileController;
    }

    /**
     * Menampilkan halaman utama (dashboard) dengan file dan folder.
     */
    public function showHomePage(Request $request, ?File $folder = null)
    {
        $data = $this->fileController->getFileSystem($request, $folder);
        return view('dashboard', $data);
    }

    /**
     * Menampilkan halaman item yang baru saja diakses.
     */
    public function showRecentPage()
    {
        $items = $this->fileController->getRecentItems();
        return view('recent', ['items' => $items]);
    }

    /**
     * Menampilkan halaman trash (item yang sudah dihapus).
     */
    public function showTrashPage()
    {
        $items = $this->fileController->getTrashedItems();
        return view('trash', ['items' => $items]);
    }
}
