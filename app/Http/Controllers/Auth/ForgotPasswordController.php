<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form permintaan reset password.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot_password');
    }

    /**
     * Kirim tautan reset password ke email pengguna.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email' => $validated['email'],
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Tautan reset password sudah dikirim. Cek email Anda.');
        }

        $message = $status === Password::RESET_THROTTLED
            ? 'Terlalu banyak percobaan reset. Silakan coba lagi beberapa menit lagi.'
            : 'Email tidak ditemukan atau gagal mengirim tautan reset.';

        return back()->withErrors(['email' => $message]);
    }

    /**
     * Tampilkan form reset password menggunakan token.
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset_password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Simpan password baru pengguna.
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $validated,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil diperbarui. Silakan login dengan password baru.');
        }

        $message = match ($status) {
            Password::INVALID_TOKEN => 'Token reset password tidak valid atau sudah digunakan.',
            Password::INVALID_USER => 'Email tidak ditemukan.',
            Password::RESET_THROTTLED => 'Terlalu banyak percobaan reset. Silakan coba lagi beberapa menit lagi.',
            default => 'Gagal mereset password. Silakan coba lagi.',
        };

        return back()->withErrors(['email' => $message]);
    }
}
