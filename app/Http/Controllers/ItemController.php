<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ItemController extends Controller
{
    public function index()
    {

        $items = Item::all();

        return view('landing.index_items', compact('items'));
    }

    public function dashboard()
    {
        // Hanya ambil item dengan status 'approved'
        $items = Item::where('status', 'approved')->get();

        // Dapatkan user yang sedang login
        $user = Auth::user();

        // Menampilkan notifikasi laporan pending milik user saat ini (opsional)
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
    $query = Item::where('user_id', Auth::id());

    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    $userItems = $query->orderBy('created_at', 'desc')->get();
    $user = Auth::user();

    return view('users.activity', compact('userItems', 'user'));
}

    /**
     * Store a newly created item report.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'type' => 'required|in:hilang,ditemukan',
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'date_of_event' => 'required|date',
            'description' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:15',
            'location' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:3048',
        ]);

        // Sisa kode tetap sama
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('items', 'public');
        }

        // Simpan data item ke database
        Item::create([
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
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard')->with('success', 'Laporan telah berhasil dikirimkan, dan sedang menunggu approval admin');
    }

    public function update(Request $request, Item $item)
    {
        // Verifikasi kepemilikan
        if ($item->user_id !== Auth::id()) {
            return redirect()->route('activity')->with('error', 'Anda tidak memiliki izin untuk mengedit laporan ini.');
        }

        // Menyimpan status sebelumnya untuk pengecekan
        $previousStatus = $item->status;

        try {
            // Validasi data
            $validated = $request->validate([
                'item_name' => 'required|string|max:255',
                'type' => 'required|in:hilang,ditemukan',
                'category' => 'required|string|max:255',
                'date_of_event' => 'required|date',
                'description' => 'required|string',
                'email' => 'required|email',
                'phone_number' => 'required|string|max:15',
            ]);

            // Update data item
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

            // Handle photo update jika ada
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                // Hapus foto lama jika ada
                if ($item->photo_path && Storage::disk('public')->exists($item->photo_path)) {
                    Storage::disk('public')->delete($item->photo_path);
                }

                // Simpan foto baru
                $item->photo_path = $request->file('photo')->store('items', 'public');

                // Jika foto diupdate, harus menjadi pending (terlepas dari status sebelumnya)
                $item->status = 'pending';
            }

            // Simpan perubahan ke database
            $item->save();

            // Pesan sukses berbeda berdasarkan perubahan status
            if ($previousStatus == 'approved' && $item->status == 'pending') {
                return redirect()->route('activity')->with('success', 'Laporan berhasil diperbarui! Status laporan kembali menjadi pending untuk ditinjau admin.');
            } else {
                return redirect()->route('activity')->with('success', 'Laporan berhasil diperbarui!');
            }
        } catch (\Exception $e) {
            return redirect()->route('activity')->with('error', 'Gagal memperbarui laporan: ' . $e->getMessage());
        }
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
}
