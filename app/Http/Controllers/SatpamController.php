<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;

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

        return view('satpam.satpam_dashboard', compact(
            'userCount',
            'unapprovedCount',
            'totalReports',
            'lostItemsCount',
            'foundItemsCount',
            'items'
        ));
    }

    // Method untuk approval, lost items, found items dll bisa ditambahkan di sini
}
