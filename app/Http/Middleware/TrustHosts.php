<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Dapatkan pola host yang harus dipercaya.
     *
     * @return array<int, string|null>
     */
    // Mendapatkan pola host yang harus dipercaya
    public function hosts(): array
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
