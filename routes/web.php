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
    // Displays PHP information (for testing)
    return phpinfo();
});

// Routes requiring authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Main file and folder browsing
    // Displays the main dashboard with files and folders
    Route::get('/', [FileController::class, 'index'])->name('file.index');
    // Displays content of a specific folder
    Route::get('/folders/{folder}', [FileController::class, 'index'])->name('file.folder');

    // Profile routes
    // Displays the user profile editing form
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Updates the user profile information
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Deletes the user account
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard view routes
    // Displays recently accessed items
    Route::get('/recent', [DashboardController::class, 'recent'])->name('recent');
    // Displays trashed items
    Route::get('/trash', [DashboardController::class, 'trash'])->name('trash');

    // File and folder actions
    // Handles creation of new folders
    Route::post('/folder/create', [FileController::class, 'createFolder'])->name('folder.create');
    // Handles file uploads
    Route::post('/file/upload', [FileController::class, 'uploadFile'])->name('file.upload');
    // Handles updating file/folder properties
    Route::patch('/file/update/{file}', [FileController::class, 'update'])->name('file.update');
    // Handles deleting a file or folder
    Route::delete('/file/delete/{file}', [FileController::class, 'destroy'])->name('file.destroy');

    // File and folder downloads/previews
    // Handles downloading a file
    Route::get('/file/download/{file}', [FileController::class, 'download'])->name('file.download');
    // Handles previewing a file
    Route::get('/file/preview/{file}', [FileController::class, 'preview'])->name('file.preview');
    // Handles downloading a folder
    Route::get('/folder/download/{folder}', [FileController::class, 'downloadFolder'])->name('folder.download');

    // Trash actions
    // Handles restoring an item from trash
    Route::post('/trash/restore/{id}', [FileController::class, 'restore'])->name('trash.restore');
    // Menangani penghapusan permanen item dari sampah
    Route::delete('/trash/force-delete/{id}', [FileController::class, 'forceDelete'])->name('trash.forceDelete');
});

require __DIR__.'/auth.php';
