<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $items = Item::all();
        $reportCount = $items->count();
        $user = Auth::user();

        if (Auth::check()) {
            $user = Auth::user();
            ($user->role);  // Melihat role pengguna
        }

        return view('dashboard', compact("items", "reportCount", "user")); // Halaman dashboard user
    }

    // Tambahkan method ini untuk halaman About Us
    public function aboutUs()
    {
        $user = Auth::user();
        return view('users.aboutUs', compact('user'));
    }
}
