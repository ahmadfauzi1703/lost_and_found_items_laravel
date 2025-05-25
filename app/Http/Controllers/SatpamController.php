<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Claim;
use Illuminate\Support\Facades\Hash;

class SatpamController extends Controller
{
    public function index()
    {
        // Hitung jumlah user
        $userCount = User::where('role', '!=', 'admin')
            ->where('role', '!=', 'satpam')
            ->count();

        // Hitung laporan yang belum disetujui (status pending)
        $unapprovedCount = Item::where('status', 'pending')->count();

        // Hitung total semua laporan
        $totalReports = Item::count();

        // Hitung laporan barang hilang
        $lostItemsCount = Item::where('type', 'hilang')->count();

        // Hitung laporan barang ditemukan
        $foundItemsCount = Item::where('type', 'ditemukan')->count();

        // Mengambil item terbaru untuk ditampilkan di dashboard
        $items = Item::orderBy('created_at', 'desc')->limit(5)->get();

        $claimedItemsCount = \App\Models\Claim::whereIn('status', ['approved', 'Claimed'])->count();

        // Data untuk chart laporan bulanan
        $monthlyReports = DB::table('items')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y')) // Filter untuk tahun ini
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Cari bulan dengan laporan tertinggi
        $highestMonth = null;
        $highestCount = 0;

        foreach ($monthlyReports as $report) {
            if ($report->count > $highestCount) {
                $highestCount = $report->count;
                $highestMonth = date('F', mktime(0, 0, 0, $report->month, 1));
            }
        }

        // Hitung rata-rata laporan per bulan
        $totalReportsThisYear = $monthlyReports->sum('count');
        $monthsWithReports = $monthlyReports->count();
        $averageReports = $monthsWithReports > 0 ? round($totalReportsThisYear / $monthsWithReports, 1) : 0;

        return view('satpam.satpam_dashboard', compact(
            'userCount',
            'unapprovedCount',
            'totalReports',
            'lostItemsCount',
            'foundItemsCount',
            'claimedItemsCount',
            'items',
            'monthlyReports',
            'highestMonth',
            'averageReports'
        ));
    }


    public function create()
    {
        return view('satpam.satpam_dashboard_create');
    }

    public function store(Request $request)
    {
        // Validasi input berdasarkan struktur database
        $validated = $request->validate([
            'type' => 'required|in:hilang,ditemukan',
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'date_of_event' => 'required|date',
            'description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:15',
            'location' => 'nullable|string|max:100',
            'report_by' => 'nullable|string|max:255', // Tambahkan validasi untuk report_by
            'photo' => 'nullable|image|max:2048', // max 2MB
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('items', 'public');
            $validated['photo_path'] = $photoPath;
        }

        // Hapus 'photo' karena tidak ada di database
        if (isset($validated['photo'])) {
            unset($validated['photo']);
        }

        // Tambahkan data satpam yang menginput
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'approved'; // Langsung approve karena diinput oleh satpam

        // Set report_by jika tidak diisi
        if (empty($validated['report_by'])) {
            // Default: Gunakan nama satpam yang login
            $validated['report_by'] = 'Satpam' . Auth::user()->name;
        }

        // Simpan item
        Item::create($validated);

        return redirect()->route('satpam_dashboard')
            ->with('success', 'Item added successfully!');
    }

    public function viewItems()
    {
        // Ambil item yang dilaporkan oleh satpam saja
        // Ini menggunakan where untuk mencari report_by yang dimulai dengan "Satpam:"
        $items = Item::all();

        return view('satpam.satpam_dashboard_view', compact('items'));
    }

    public function updateItem(Request $request, Item $item)
    {
        // Validasi request
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'type' => 'required|in:hilang,ditemukan',
            'date_of_event' => 'required|date',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // max 2MB
        ]);

        // Hapus photo dari array validated karena kita tangani secara manual
        if (isset($validated['photo'])) {
            unset($validated['photo']);
        }

        // Upload foto baru jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($item->photo_path && Storage::disk('public')->exists($item->photo_path)) {
                Storage::disk('public')->delete($item->photo_path);
            }

            // Upload dan simpan path foto baru
            $photoPath = $request->file('photo')->store('items', 'public');
            $validated['photo_path'] = $photoPath;
        }

        // Update item
        $item->update($validated);

        return redirect()->route('satpam.dashboard.view')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    public function createClaim()
    {
        // Ambil item yang belum diklaim
        $items = Item::select('id', 'item_name', 'category', 'status')
            ->where(function ($query) {
                $query->where('status', '!=', 'Claimed')
                    ->orWhereNull('status');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('satpam.satpam_dashboard_createClaim', compact('items'));
    }

    public function storeClaim(Request $request)
    {
        // Validasi request
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'claimer_name' => 'required|string|max:255',
            'claimer_nim' => 'nullable|string|max:20',
            'claimer_email' => 'required|email|max:255',
            'claimer_phone' => 'required|string|max:15',
            'ownership_proof' => 'required|string',
            'proof_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'claim_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Set status langsung ke "Claimed" (tidak perlu approval)
        $validated['status'] = 'Claimed';

        // Upload bukti dokumen jika ada
        if ($request->hasFile('proof_document')) {
            $filePath = $request->file('proof_document')->store('claim_proofs', 'public');
            $validated['proof_document'] = $filePath;
        }

        // Buat record claim
        $claim = Claim::create($validated);

        // Update status item menjadi 'Claimed'
        $item = Item::find($validated['item_id']);
        if ($item) {
            $item->status = 'Claimed';
            $item->save();
        }

        // Redirect dengan pesan sukses
        return redirect()->route('satpam.dashboard.createClaim')
            ->with('success', 'Klaim barang berhasil dibuat!');
    }

    public function viewHistory()
    {
        // Ambil semua data klaim dengan relasi ke item
        $claims = Claim::with('item')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('satpam.satpam_dashboard_viewHistory', compact('claims'));
    }

    public function profile()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        return view('satpam.satpam_dashboard_profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        // Validasi input
        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update data user
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Upload foto profile jika ada
        if ($request->hasFile('profile_picture')) {
            // Hapus foto lama jika ada
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Upload foto baru
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return redirect()->route('satpam.dashboard.profile')
            ->with('success', 'Profile successfully updated!');
    }
}
