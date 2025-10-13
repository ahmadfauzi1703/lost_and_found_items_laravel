<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramNotifier;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query dasar
        $query = Item::where('status', 'approved');

        // Filter berdasarkan kategori jika ada
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan nama item jika ada
        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        // Ambil hasil setelah semua filter
        $items = $query->latest()->get();

        // Informasi filter untuk ditampilkan
        $filterInfo = [];
        if ($request->filled('category')) {
            $filterInfo[] = "Kategori: " . $request->category;
        }
        if ($request->filled('search')) {
            $filterInfo[] = "Pencarian: " . $request->search;
        }

        return view('landing.index_items', compact('items', 'filterInfo'));
    }

    public function dashboard(Request $request)
    {
        // Mulai query dasar untuk item yang disetujui
        $query = Item::where('status', 'approved');

        // Filter berdasarkan kategori jika ada
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan nama item jika ada
        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        // Ambil hasil setelah semua filter
        $items = $query->latest()->get();

        // Dapatkan user yang sedang login
        $user = Auth::user();

        // Menampilkan notifikasi laporan pending milik user saat ini
        $pendingItemsCount = Item::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->count();

        return view('dashboard', compact('items', 'user', 'pendingItemsCount'));
    }

    public function showRecentItems()
    {
        // Mengambil 5 item pertama dari tabel 'items'
        $items = Item::limit(5)->get();

        // Mengirimkan data ke view dengan variabel yang sama (items)
        return view('admin.admin_dashboard', compact('items'));  // Sesuaikan nama variabel
    }


    public function showLostItems()
    {
        $lostItems = Item::where('type', 'hilang')->get();
        return view('admim_dashboard_lost', compact('lostItems'));
    }

    public function showFoundItems()
    {
        $foundItems = Item::where('type', 'ditemukan')->get();
        return view('admim_dashboard_found', compact('foundItems'));
    }


    public function filterItems(Request $request)
    {
        // Mulai query dari model Item
        $query = Item::query();

        // Filter berdasarkan kategori jika ada
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan nama item jika ada
        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan tahun jika ada
        if ($request->filled('year')) {
            $query->whereYear('date_of_event', $request->year);
        }

        // Filter berdasarkan bulan jika ada
        if ($request->filled('month')) {
            $query->whereMonth('date_of_event', $request->month);
        }

        // Filter berdasarkan tanggal jika ada
        if ($request->filled('date')) {
            // Mengambil bagian tahun, bulan, dan hari dari tanggal
            $date = $request->input('date'); // Format: Y-m-d
            $query->whereDate('date_of_event', $date);
        }

        // Ambil hasil setelah semua filter
        $lostItems = $query->get();

        // Kirim data lostItems ke view
        return view('admin.admin_dashboard_lost', compact('lostItems'));
    }

    // Menyimpan item baru
    public function storeBasic(Request $request)
    {
        // Validasi input gambar
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Jika ada gambar yang diunggah
        if ($request->hasFile('photo')) {
            // Simpan gambar di folder public/images/
            $path = $request->file('photo')->store('images', 'public');

            // Simpan data item ke database
            Item::create([
                'item_name' => $request->item_name,
                'photo_path' => $path,  // Simpan path relatif
                'date_of_event' => now(), // Atau gunakan tanggal yang sesuai
            ]);
        }

        return back()->with('success', 'Item berhasil disimpan.');
    }

    public function create()
    {
        $user = Auth::user();
        return view('users.formReport', compact('user'));
    }

    public function activity(Request $request)
    {
        $user = Auth::user();

        // Mulai query dasar untuk barang user
        $userItemsQuery = Item::where('user_id', $user->id);

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $userItemsQuery->where('category', $request->category);
        }

        // Filter berdasarkan jenis laporan
        if ($request->filled('type')) {
            $userItemsQuery->where('type', $request->type);
        }

        // Execute query dan urutkan
        $userItems = $userItemsQuery->orderBy('created_at', 'desc')->get();

        // Ambil ID barang yang dilaporkan user
        $myItemIds = $userItems->pluck('id')->toArray();

        // Ambil semua klaim terhadap barang user
        $claimsOnMyItems = \App\Models\Claim::whereIn('item_id', $myItemIds)
            ->with(['item']) // Load relasi item
            ->latest()
            ->get();

        // Ambil klaim yang dibuat oleh user
        $myClaimsQuery = \App\Models\Claim::where('claimer_email', $user->email);

        // Filter klaim berdasarkan kategori barang jika ada
        if ($request->filled('category')) {
            $myClaimsQuery->whereHas('item', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        $myClaims = $myClaimsQuery->with(['item'])->latest()->get();

        // Query baru untuk returns yang dibuat user
        $myReturnsQuery = \App\Models\ItemReturn::where('returner_id', $user->id);

        // Filter berdasarkan kategori barang jika ada
        if ($request->filled('category')) {
            $myReturnsQuery->whereHas('item', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        $myReturns = $myReturnsQuery->with(['item'])->latest()->get();

        // Ambil returns untuk barang yang dimiliki user
        $returnsOnMyItems = \App\Models\ItemReturn::whereIn('item_id', $myItemIds)
            ->with(['item'])
            ->latest()
            ->get();

        // SATU return statement dengan semua data
        return view('users.activity', [
            'user' => $user,
            'userItems' => $userItems,
            'claimsOnMyItems' => $claimsOnMyItems,
            'myClaimsAndReturns' => $myClaims,
            'myReturns' => $myReturns,
            'returnsOnMyItems' => $returnsOnMyItems,
            'filterCategory' => $request->category,
            'filterType' => $request->type
        ]);
    }

    /**
     * Store a newly created item report.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:hilang,ditemukan',
                'item_name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'date_of_event' => 'required|date',
                'description' => 'required|string',
                'email' => 'required|email',
                'phone_number' => 'nullable|string|max:15',
                'location' => 'required|string',
                // max is in kilobytes; 15MB = 15360 KB
                'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:15360',
            ]);

            $photoPath = null;
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                try {
                    $photo = $request->file('photo');
                    Log::info('Processing photo upload', [
                        'original_name' => $photo->getClientOriginalName(),
                        'mime_type' => $photo->getMimeType(),
                        'size' => $photo->getSize()
                    ]);
                    
                    $photoPath = $photo->store('items', 'public');
                    
                    if (!$photoPath) {
                        throw new \Exception('Failed to store the photo');
                    }
                    
                    Log::info('Photo successfully stored', ['path' => $photoPath]);
                } catch (\Exception $e) {
                    Log::error('Error uploading photo: ' . $e->getMessage());
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Failed to upload photo. Please try again.');
                }
            }

            // Dapatkan user yang sedang login
            $user = Auth::user();

            // Buat nama pelapor berdasarkan informasi user
            $reporter = '';
            if (!empty($user->first_name) || !empty($user->last_name)) {
                // Jika ada first_name atau last_name, gunakan keduanya
                $reporter = trim($user->first_name . ' ' . $user->last_name);
            } else {
                // Jika tidak ada, gunakan name saja
                $reporter = $user->name;
            }

            // Simpan data item ke database
            $item = Item::create([
                'type' => $request->type,
                'item_name' => $request->item_name,
                'category' => $request->category,
                'date_of_event' => $request->date_of_event,
                'description' => $request->description,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'location' => $request->location,
                'photo_path' => $photoPath,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'report_by' => $reporter // Tambahkan field report_by dengan nilai nama user
            ]);

            Notification::create([
                'user_id' => Auth::id(),
                'message' => 'Laporan barang ' . ($request->type == 'hilang' ? 'hilang' : 'ditemukan') .
                    ' (' . $request->item_name . ') telah berhasil dibuat dan sedang menunggu approval admin.',
                'created_at' => now(),
                'is_read' => 0
            ]);

            // TelegramNotifier::notifyNewReport(
            //     $item->type,
            //     $item->item_name,
            //     $item->category,
            //     $reporter,
            //     route('dashboard')
            // );

            return redirect()->route('dashboard')
                ->with('success', 'Laporan telah berhasil dikirimkan, dan sedang menunggu approval admin');

        } catch (\Exception $e) {
            Log::error('Error creating item report: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create report. Please try again.');
        }

        Notification::create([
            'user_id' => Auth::id(),
            'message' => 'Laporan barang ' . ($request->type == 'hilang' ? 'hilang' : 'ditemukan') .
                ' (' . $request->item_name . ') telah berhasil dibuat dan sedang menunggu approval admin.',
            'created_at' => now(),
            'is_read' => 0
        ]);

        return redirect()->route('dashboard')->with('success', 'Laporan telah berhasil dikirimkan, dan sedang menunggu approval admin');
    }

    public function update(Request $request, Item $item)
    {
        // Verifikasi kepemilikan (kode yang sudah ada)
        if ($item->user_id !== Auth::id()) {
            return redirect()->route('activity')->with('error', 'Anda tidak memiliki izin untuk mengedit laporan ini.');
        }

        // Menyimpan status sebelumnya untuk pengecekan
        $previousStatus = $item->status;

        try {
            // Validasi data (kode yang sudah ada)
            $validated = $request->validate([
                // Validasi yang sudah ada
            ]);

            // Update data item (kode yang sudah ada)
            $item->item_name = $request->item_name;
            $item->type = $request->type;
            $item->category = $request->category;
            $item->date_of_event = $request->date_of_event;
            $item->description = $request->description;
            $item->email = $request->email;
            $item->phone_number = $request->phone_number;

            // Jika sebelumnya approved, ubah menjadi pending
            if ($previousStatus == 'approved') {
                $item->status = 'pending';
            }

            // Handle photo update (kode yang sudah ada)
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                // Kode untuk update photo yang sudah ada
            }

            // Jika report_by kosong, tambahkan informasi pelapor
            if (empty($item->report_by)) {
                $user = Auth::user();
                if (!empty($user->first_name) || !empty($user->last_name)) {
                    $item->report_by = trim($user->first_name . ' ' . $user->last_name);
                } else {
                    $item->report_by = $user->name;
                }
            }

            // Simpan perubahan ke database
            $item->save();

            // Kode return yang sudah ada
        } catch (\Exception $e) {
            // Kode error yang sudah ada
        }

        return redirect()->route('activity')->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy(Item $item)
    {
        // Verify ownership
        if ($item->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus laporan ini.');
        }

        // Delete photo if exists
        if ($item->photo_path && Storage::disk('public')->exists($item->photo_path)) {
            Storage::disk('public')->delete($item->photo_path);
        }

        // Delete item
        $item->delete();

        return redirect()->route('activity')->with('success', 'Laporan berhasil dihapus!');
    }

    // Menampilkan form claim barang
    public function showClaimForm(Request $request)
    {
        $item_id = $request->query('item_id');
        $item = Item::findOrFail($item_id);

        // Verifikasi bahwa item berjenis "ditemukan"
        if ($item->type !== 'ditemukan') {
            return redirect()->back()->with('error', 'Anda hanya dapat mengklaim barang yang ditemukan.');
        }

        // Ubah dari return view('claim_form', compact('item'));
        return view('users.claim_form', compact('item'));
    }

    // Proses claim barang
    public function processClaim(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:barang,id',
            'ownership_proof' => 'required|string',
            'claimer_phone' => 'required|string',
            'notes' => 'nullable|string',
            'proof_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Tambahkan data user yang login
        $user = Auth::user();
        $claim = new \App\Models\Claim();
        $claim->item_id = $validated['item_id'];
        $claim->claimer_name = $user->name;
        $claim->claimer_email = $user->email;
        $claim->claimer_phone = $validated['claimer_phone'];
        $claim->ownership_proof = $validated['ownership_proof'];
        $claim->notes = $validated['notes'] ?? null;
        $claim->claim_date = now();
        $claim->status = 'pending'; // Admin/satpam akan memverifikasi klaim

        // Upload bukti dokumen jika ada
        if ($request->hasFile('proof_document')) {
            $filePath = $request->file('proof_document')->store('claim_proofs', 'public');
            $claim->proof_document = $filePath;
        }

        $claim->save();

        return redirect()->route('dashboard')->with('success', 'Klaim barang berhasil diajukan. Mohon tunggu verifikasi dari petugas.');
    }

    // Menampilkan form return barang
    public function showReturnForm(Request $request)
    {
        $item_id = $request->query('item_id');
        $item = Item::findOrFail($item_id);
        $user = Auth::user(); // Ambil data user yang sedang login

        // Verifikasi bahwa item berjenis "hilang"
        if ($item->type !== 'hilang') {
            return redirect()->back()->with('error', 'Anda hanya dapat mengembalikan barang yang hilang.');
        }

        // Ubah dari return view('users.return_form', compact('item'));
        return view('users.return_form', compact('item', 'user'));
    }

    // Proses return barang
    public function processReturn(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:barang,id',
            'where_found' => 'required|string',
            'returner_phone' => 'required|string',
            'notes' => 'nullable|string',
            'item_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Menggunakan data user yang login
        $user = Auth::user();

        // Buat nama returner berdasarkan informasi user
        $returner_name = '';
        if (!empty($user->first_name) || !empty($user->last_name)) {
            $returner_name = trim($user->first_name . ' ' . $user->last_name);
        } else if (!empty($user->name)) {
            $returner_name = $user->name;
        } else {
            $returner_name = 'User_' . $user->id;
        }

        // Buat objek ItemReturn baru (bukan Claim)
        $return = new \App\Models\ItemReturn();
        $return->item_id = $validated['item_id'];
        $return->returner_id = $user->id;
        $return->returner_name = $returner_name;
        $return->returner_email = $user->email;
        $return->returner_phone = $validated['returner_phone'];
        $return->where_found = $validated['where_found'];
        $return->notes = $validated['notes'] ?? null;
        $return->return_date = now();
        $return->status = 'pending';

        // Upload foto item jika ada
        if ($request->hasFile('item_photo')) {
            $filePath = $request->file('item_photo')->store('return_photos', 'public');
            $return->item_photo = $filePath;
        }

        $return->save();

        // Notifikasi untuk pemilik barang
        if ($item = Item::find($validated['item_id'])) {
            Notification::create([
                'user_id' => $item->user_id,
                'message' => "Barang Anda ({$item->item_name}) telah dikembalikan oleh seseorang. Mohon cek detailnya.",
                'is_read' => 0
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Laporan pengembalian barang berhasil diajukan. Mohon tunggu konfirmasi dari pemilik barang.');
    }

    public function updateReturnStatus(Request $request)
    {
        $validated = $request->validate([
            'return_id' => 'required|exists:pengembalian,id',
            'status' => 'required|in:approved,rejected,completed',
        ]);

        $return = \App\Models\ItemReturn::findOrFail($validated['return_id']);

        // Periksa kepemilikan item
        $item = Item::findOrFail($return->item_id);
        if ($item->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah status pengembalian ini');
        }

        // Update status pengembalian
        $return->status = $validated['status'];
        $return->save();

        // Jika disetujui, update status item menjadi 'Claimed' (yang sudah ada dalam ENUM)
        // bukan 'returned' yang tidak ada dalam ENUM
        if ($validated['status'] == 'approved' || $validated['status'] == 'completed') {
            $item->status = 'Claimed'; // Menggunakan nilai yang valid dalam ENUM
            $item->save();
        }

        return redirect()->back()->with('success', 'Status pengembalian berhasil diperbarui');
    }


    public function updateClaimStatus(Request $request)
    {
        $validated = $request->validate([
            'claim_id' => 'required|exists:klaim,id',
            'status' => 'required|in:approved,rejected',
        ]);

        $claim = \App\Models\Claim::findOrFail($validated['claim_id']);

        // Periksa apakah user adalah pemilik barang
        $item = Item::findOrFail($claim->item_id);
        if ($item->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak berhak mengubah status klaim ini');
        }

        // Update status klaim
        $claim->status = $validated['status'];
        $claim->save();

        // Jika status approved (baik klaim maupun pengembalian), update status barang menjadi claimed
        if ($validated['status'] == 'approved') {
            // Menggunakan status 'claimed' untuk semua jenis klaim yang diterima
            $item->status = 'Claimed';
            $item->save();
        }

        $actionType = $claim->type == 'return' ? 'pengembalian' : 'klaim';
        $statusText = $validated['status'] == 'approved' ? 'diterima' : 'ditolak';

        return redirect()->back()->with('success', "Permintaan $actionType berhasil $statusText");
    }

    public function storeClaim(Request $request, $item)
    {
        $validated = $request->validate([
            'claimer_name' => 'required|string|max:255',
            'claimer_email' => 'required|email',
            'claimer_phone' => 'required|string|max:15',
            'ownership_proof' => 'required|string',
            'notes' => 'nullable|string',
            'terms' => 'required',
        ]);

        // Buat objek klaim baru
        $claim = new \App\Models\Claim();
        $claim->item_id = $item;
        $claim->claimer_name = $request->claimer_name;
        $claim->claimer_email = $request->claimer_email;
        $claim->claimer_phone = $request->claimer_phone;
        $claim->ownership_proof = $request->ownership_proof;
        $claim->notes = $request->notes ?? null;
        $claim->claim_date = now();
        $claim->status = 'pending';

        // Tambahkan tipe claim
        $claim->type = 'claim'; // Pastikan nilai default sesuai dengan definisi tabel

        // HAPUS ATAU KOMENTARI BAGIAN INI
        // if (Auth::check()) {
        //     $claim->user_id = Auth::id();
        // }

        // Upload dokumen bukti jika ada
        if ($request->hasFile('proof_document')) {
            $filePath = $request->file('proof_document')->store('claim_proofs', 'public');
            $claim->proof_document = $filePath;
        }

        $claim->save();

        return redirect()->route('dashboard')->with('success', 'Klaim barang berhasil diajukan. Mohon tunggu verifikasi dari petugas.');
    }
}
