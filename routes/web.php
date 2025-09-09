<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

// Publicly accessible test route
Route::get('/cek-php', function () {
    return phpinfo();
});

// Routes requiring authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Main file and folder browsing
    Route::get('/', [FileController::class, 'index'])->name('file.index');
    Route::get('/folders/{folder}', [FileController::class, 'index'])->name('file.folder');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard view routes
    Route::get('/recent', [DashboardController::class, 'recent'])->name('recent');
    Route::get('/trash', [DashboardController::class, 'trash'])->name('trash');

    // File and folder actions
    Route::post('/folder/create', [FileController::class, 'createFolder'])->name('folder.create');
    Route::post('/file/upload', [FileController::class, 'uploadFile'])->name('file.upload');
    Route::patch('/file/update/{file}', [FileController::class, 'update'])->name('file.update');
    Route::delete('/file/delete/{file}', [FileController::class, 'destroy'])->name('file.destroy');
    Route::post('/file/duplicate/{file}', [FileController::class, 'duplicate'])->name('file.duplicate');

    // File and folder downloads/previews
    Route::get('/file/download/{file}', [FileController::class, 'download'])->name('file.download');
    Route::get('/file/preview/{file}', [FileController::class, 'preview'])->name('file.preview');
    Route::get('/folder/download/{folder}', [FileController::class, 'downloadFolder'])->name('folder.download');

    // Trash actions
    Route::post('/trash/restore/{id}', [FileController::class, 'restore'])->name('trash.restore');
    Route::delete('/trash/force-delete/{id}', [FileController::class, 'forceDelete'])->name('trash.forceDelete');
});

require __DIR__.'/auth.php';
