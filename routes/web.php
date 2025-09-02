<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman utama (root)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
// Rute untuk menampilkan isi folder
Route::get('/folders/{folder}', [DashboardController::class, 'index'])->name('dashboard.folder');

// Grup rute yang memerlukan login
Route::middleware(['auth', 'verified'])->group(function () {
    // Rute Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Menu Navigasi
    Route::get('/recent', [DashboardController::class, 'recent'])->name('recent');
    Route::get('/trash', [DashboardController::class, 'trash'])->name('trash');

    // Rute Aksi CRUD
    Route::post('/folder/create', [DashboardController::class, 'createFolder'])->name('folder.create');
    Route::post('/file/upload', [DashboardController::class, 'uploadFile'])->name('file.upload');
    Route::delete('/file/delete/{file}', [DashboardController::class, 'delete'])->name('file.delete');

    // Rute Aksi File
    Route::get('/file/download/{file}', [DashboardController::class, 'download'])->name('file.download');
    Route::get('/file/preview/{file}', [DashboardController::class, 'preview'])->name('file.preview');
});

require __DIR__ . '/auth.php';
