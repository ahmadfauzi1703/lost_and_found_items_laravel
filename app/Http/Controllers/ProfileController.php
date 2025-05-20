<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nim' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255', // Pastikan ini ada
        ]);

        $user->fill($validated);
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update the user's profile picture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|max:2048',
        ]);

        $user = User::find(Auth::id());

        if ($request->hasFile('profile_picture')) {
            // Hapus foto lama jika ada
            if ($user->profile_picture && file_exists(storage_path('app/public/' . $user->profile_picture))) {
                unlink(storage_path('app/public/' . $user->profile_picture));
            }

            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $user->profile_picture = $path;
            $user->save();
        }

        return redirect()->route('profile')->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Delete the user's profile picture.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePicture()
    {
        $user = User::find(Auth::id());

        if ($user->profile_picture && file_exists(storage_path('app/public/' . $user->profile_picture))) {
            unlink(storage_path('app/public/' . $user->profile_picture));
        }

        $user->profile_picture = null;
        $user->save();

        return redirect()->route('profile')->with('success', 'Foto profil berhasil dihapus!');
    }

    /**
     * Change user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function changePassword(Request $request)
    // {
    //     $request->validate([
    //         'current_password' => 'required|current_password',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     $user = User::find(Auth::id());
    //     $user->password = bcrypt($request->password);
    //     $user->save();

    //     return redirect()->route('profile')->with('success', 'Password berhasil diperbarui!');
    // }
}
