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


    public function found(Request $request)
    {
        // Mulai dengan query dasar
        $query = Item::where('type', 'ditemukan');

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('item_name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm)
                    ->orWhere('location', 'like', $searchTerm);
            });
        }

        // Jalankan query dan ambil hasilnya
        $foundItems = $query->orderBy('created_at', 'desc')->get();

        return view('admin.admin_dashboard_found', compact('foundItems'));
    }

    public function lost(Request $request)
    {
        // Base query untuk item hilang
        $query = Item::where('type', 'hilang');

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('item_name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm)
                    ->orWhere('location', 'like', $searchTerm);
            });
        }

        // Ambil data setelah filter diterapkan
        $lostItems = $query->orderBy('created_at', 'desc')->get();

        return view('admin.admin_dashboard_lost', compact('lostItems'));
    }

    public function user(Request $request)
    {
        // Mulai dengan query dasar
        $query = User::query();

        // Filter berdasarkan nama (first_name, last_name, atau keduanya)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', $searchTerm)
                    ->orWhere('last_name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Ambil data setelah filter diterapkan dan urutkan berdasarkan nama
        $users = $query->orderBy('first_name')->get();

        return view('admin.admin_dashboard_user', compact('users'));
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
