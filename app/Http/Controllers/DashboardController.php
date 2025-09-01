<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    //
    public function index()
    {
        $folders = [];
        $files = [];

        // Hanya ambil dan kirim data jika pengguna sudah login.
        // Untuk tamu, variabel $folders dan $files akan menjadi array kosong.
        if (Auth::check()) {
            // NANTINYA: Ganti ini dengan query database ke tabel 'files'
            // $user = Auth::user();
            // $folders = $user->files()->where('is_folder', true)->whereNull('parent_id')->get();
            // $files = $user->files()->where('is_folder', false)->whereNull('parent_id')->get();

            // Untuk sekarang, kita tetap gunakan data dummy untuk pengguna yang sudah login
            $folders = [
                ['name' => 'Laporan Keuangan', 'date' => '10 Dec, 2020', 'file_count' => 8],
                ['name' => 'Dokumen HRD', 'date' => '09 Dec, 2020', 'file_count' => 12],
                ['name' => 'Materi Marketing', 'date' => '08 Dec, 2020', 'file_count' => 5],
                ['name' => 'Proyek Klien A', 'date' => '06 Dec, 2020', 'file_count' => 24],
            ];

            $files = [
                ['name' => 'rekap-bulanan.xlsx', 'members' => 'Me', 'last_edit' => 'Jan 21, 2020 me', 'size' => '2 MB'],
                ['name' => 'kontrak-karyawan.pdf', 'members' => 'HR Team', 'last_edit' => 'Jan 25, 2020 HR Team', 'size' => '1 MB'],
                ['name' => 'presentasi-q3.pptx', 'members' => 'Me', 'last_edit' => 'Mar 30, 2020 Marketing', 'size' => '30 MB'],
            ];
        }

        return view('dashboard', compact('folders', 'files'));
    }

    public function recent()
    {
        // NANTINYA: Tulis query untuk mengambil file yang baru diakses
        $folders = []; // Data dummy untuk recent folders
        $files = [
            ['name' => 'presentasi-q3.pptx', 'members' => 'Me', 'last_edit' => 'Sep 01, 2025 Me', 'size' => '30 MB'],
        ]; // Data dummy untuk recent files

        // Kita gunakan view yang sama untuk konsistensi
        return view('dashboard', compact('folders', 'files'));
    }

    public function trash()
    {
        // NANTINYA: Tulis query untuk mengambil file yang sudah di-soft delete
        $folders = []; // Data dummy untuk trash
        $files = [];   // Data dummy untuk trash

        // Kita gunakan view yang sama
        return view('dashboard', compact('folders', 'files'));
    }
}
