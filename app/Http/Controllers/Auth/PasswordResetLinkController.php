<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    // Menampilkan tampilan permintaan tautan atur ulang kata sandi
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // Menangani permintaan tautan reset kata sandi yang masuk
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Kami akan mengirim tautan reset kata sandi ke pengguna ini. Setelah kami mencoba
        // untuk mengirim tautan, kami akan memeriksa responsnya lalu melihat pesan yang
        // perlu kami tunjukkan kepada pengguna. Akhirnya, kami akan mengirimkan respons yang tepat.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
