<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Dapatkan path yang harus dialihkan pengguna ketika mereka tidak diautentikasi.
     */
    // Mendapatkan jalur ke mana pengguna harus dialihkan saat tidak diautentikasi
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
