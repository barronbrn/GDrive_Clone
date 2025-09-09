<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute API
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan rute API untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditugaskan ke grup middleware "api". Buat sesuatu yang hebat!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // Returns the authenticated user's data
    return $request->user();
});
