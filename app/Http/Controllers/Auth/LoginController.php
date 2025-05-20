<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Melakukan login menggunakan email dan password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Cek role pengguna dan redirect ke halaman yang sesuai
            $user = Auth::user();

            if ($user->role == 'admin') {
                return redirect()->route('admin_dashboard');  // Redirect ke dashboard admin
            } elseif ($user->role == 'satpam') {
                return redirect()->route('satpam_dashboard');  // Redirect ke dashboard satpam
            } elseif ($user->role == 'user') {
                return redirect()->route('dashboard');  // Redirect ke dashboard user
            }
        }

        // Jika login gagal, kembali ke halaman login dengan pesan error
        return redirect()->route('login')->withErrors(['email' => 'Email atau password salah.']);
    }
}
