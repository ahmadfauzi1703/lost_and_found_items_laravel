<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Item;
use App\Models\Claim;

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

        // Hitung total item dengan status claimed
        $claimedItemsCount = Item::where('status', 'Claimed')->count();

        // Ambil klaim terbaru beserta relasi item
        $recentClaims = Claim::with('item')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();


        return view('admin.admin_dashboard', compact(
            'userCount',
            'unapprovedCount',
            'totalReports',
            'lostItemsCount',
            'foundItemsCount',
            'items',
            'claimedItemsCount',
            'recentClaims'
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

    public function claims(Request $request)
    {
        $statusFilter = $request->get('status');
        $searchTerm = $request->get('search');

        $query = Claim::with('item')->orderBy('created_at', 'desc');

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($searchTerm) {
            $like = '%' . $searchTerm . '%';
            $query->where(function ($q) use ($like) {
                $q->where('claimer_name', 'like', $like)
                    ->orWhere('claimer_email', 'like', $like)
                    ->orWhere('ownership_proof', 'like', $like)
                    ->orWhereHas('item', function ($itemQuery) use ($like) {
                        $itemQuery->where('item_name', 'like', $like)
                            ->orWhere('category', 'like', $like);
                    });
            });
        }

        $claims = $query->paginate(10)->withQueryString();

        $statusSummary = [
            'pending' => Claim::where('status', 'pending')->count(),
            'approved' => Claim::where('status', 'approved')->count(),
            'rejected' => Claim::where('status', 'rejected')->count(),
        ];

        return view('admin.admin_dashboard_claims', compact('claims', 'statusSummary', 'statusFilter', 'searchTerm'));
    }

    public function approveItem($id)
    {
        $item = Item::findOrFail($id);
        $item->status = 'approved';
        $item->save();

        return redirect()->back()->with('success', 'Barang berhasil disetujui');
    }

    public function rejectItem($id)
    {
        $item = Item::findOrFail($id);
        $item->status = 'rejected';
        $item->save();

        return redirect()->back()->with('success', 'Barang ditolak');
    }


    // CRUD
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:pengguna,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'nim' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in(['user', 'admin', 'satpam'])],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'nim' => $validated['nim'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin_dashboard_user')->with('success', 'Pengguna berhasil ditambahkan.');
    }

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

        return redirect()->route('admin_dashboard_user')->with('success', 'Peran pengguna berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $user->delete();  // Menghapus user

        return redirect()->route('admin_dashboard_user')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function destroyItems(Item $item)
    {
        try {
            // Hapus item
            $item->delete();

            return redirect()->back()->with('success', 'Item berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }

    public function updateClaimStatus(Request $request, Claim $claim)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'notes' => 'nullable|string|max:500',
        ]);

        $claim->status = $validated['status'];
        if (array_key_exists('notes', $validated)) {
            $claim->notes = $validated['notes'];
        }
        $claim->save();

        if ($claim->item) {
            if ($validated['status'] === 'approved') {
                $claim->item->status = 'Claimed';
                $claim->item->save();
            } elseif (in_array($validated['status'], ['rejected', 'pending'], true) && $claim->item->status === 'Claimed') {
                // Kembalikan ke status approved agar item tetap dapat dilihat jika klaim belum sah
                $claim->item->status = 'approved';
                $claim->item->save();
            }
        }

        $messageStatus = match ($validated['status']) {
            'approved' => 'diapprove',
            'rejected' => 'ditolak',
            default => 'diperbarui',
        };

        return redirect()->back()->with('success', "Status klaim berhasil {$messageStatus}.");
    }
}
