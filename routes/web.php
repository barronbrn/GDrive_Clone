<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('my-drive');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my-drive/{folder?}', [FileController::class, 'index'])->name('my-drive');
    Route::post('/folder/create', [FileController::class, 'createFolder'])->name('folder.create');
    Route::post('/file/upload', [FileController::class, 'uploadFile'])->name('file.upload');
    Route::get('/file/download/{file}', [FileController::class, 'downloadFile'])->name('file.download');
    Route::delete('/file/delete/{file}', [FileController::class, 'deleteFile'])->name('file.delete');
});

require __DIR__.'/auth.php';
