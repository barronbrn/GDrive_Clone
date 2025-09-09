<?php

namespace App\Providers;

use App\Models\File;
use App\Policies\FilePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        File::class => FilePolicy::class,
    ];

    /**
     * Daftarkan semua layanan otentikasi / otorisasi.
     */
    // Mendaftarkan layanan otentikasi / otorisasi apa pun
    public function boot(): void
    {
        //
    }
}
