<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;


class FeedbackController extends Controller
{
  public function showAboutUs()
{
    $user = Auth::user(); // Ambil user yang sedang login
    return view('users.aboutUs', compact('user'));
}
    // Proses simpan feedback
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'description' => 'required|string',
            'comments' => 'nullable|string',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),  // null kalau belum login
            'rating' => $validated['rating'],
            'description' => $validated['description'],
            'comments' => $validated['comments'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas feedback Anda!');
    }
}
