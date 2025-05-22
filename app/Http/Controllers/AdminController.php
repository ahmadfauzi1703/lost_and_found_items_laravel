<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
        }

        // Hitung jumlah user (non-admin)
        $userCount = User::where('role', '!=', 'admin')->count();

        // Hitung laporan yang belum disetujui (status pending)
        $unapprovedCount = Item::where('status', 'pending')->count();

        // Hitung total semua laporan
        $totalReports = Item::count();

        // Hitung laporan barang hilang
        $lostItemsCount = Item::where('type', 'hilang')->count();

        // Hitung laporan barang ditemukan
        $foundItemsCount = Item::where('type', 'ditemukan')->count();

        // Mengambil item terbaru untuk ditampilkan di dashboard
        $items = Item::orderBy('created_at', 'desc')->limit(3)->get();

        return view('admin.admin_dashboard', compact(
            'userCount',
            'unapprovedCount',
            'totalReports',
            'lostItemsCount',
            'foundItemsCount',
            'items'
        ));
    }


    public function found()
    {
        // Ambil item dengan kategori "kehilangan" (bisa disesuaikan nama kategorinya)
        $foundItems = Item::where('type', 'ditemukan')->get();

        return view('admin.admin_dashboard_found', compact('foundItems'));
    }

    public function lost()
    {
        // Ambil item dengan kategori "kehilangan" (bisa disesuaikan nama kategorinya)
        $lostItems = Item::where('type', 'hilang')->get();

        return view('admin.admin_dashboard_lost', compact('lostItems'));
    }

    public function user()
    {
        $users = User::all(); // ambil semua data user dari DB

        return view('admin.admin_dashboard_user', compact('users',));
    }

    public function approval()
    {
        $pendingItems = Item::where('status', 'pending')->orderBy('created_at', 'desc')->get();
        return view('admin.admin_dashboard_approval', compact('pendingItems'));
    }

    public function approveItem($id)
    {
        $item = Item::findOrFail($id);
        $item->status = 'approved';
        $item->save();

        return redirect()->back()->with('success', 'Item has been approved');
    }

    public function rejectItem($id)
    {
        $item = Item::findOrFail($id);
        $item->status = 'rejected';
        $item->save();

        return redirect()->back()->with('success', 'Item has been rejected');
    }


    // CRUD

    public function edit(User $user)
    {
        return response()->json($user);  // Mengembalikan data user dalam format JSON
    }

    public function update(Request $request, User $user)
    {
        // Validasi role yang valid
        $request->validate([
            'role' => 'required|in:user,admin,satpam',
        ]);

        // Update role user
        $user->role = $request->role;
        $user->save();  // Simpan perubahan

        return redirect()->route('admin_dashboard_user')->with('success', 'User role updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();  // Menghapus user

        return redirect()->route('admin_dashboard_user')->with('success', 'User deleted successfully');
    }
}
