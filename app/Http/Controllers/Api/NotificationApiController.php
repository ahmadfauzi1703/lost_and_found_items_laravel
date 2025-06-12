<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{
    /**
     * Menampilkan daftar semua notifikasi (admin only)
     */
    public function index()
    {
        // Hanya admin yang boleh melihat semua notifikasi
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notifications = Notification::latest()->get();

        return response()->json([
            'data' => $notifications
        ]);
    }

    /**
     * Menampilkan notifikasi milik user yang login
     */
    public function myNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'data' => $notifications
        ]);
    }

    /**
     * Menampilkan detail notifikasi
     */
    public function show($id)
    {
        try {
            // Cari notifikasi berdasarkan ID
            $notification = Notification::findOrFail($id);

            // Pastikan user hanya bisa melihat notifikasinya sendiri
            if ($notification->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return response()->json($notification);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Notification not found'], 404);
        }
    }

    /**
     * Menandai notifikasi sebagai dibaca
     */
    public function markAsRead(Notification $notification)
    {

        // Cek apakah notifikasi ditemukan
        if (!$notification || !$notification->exists) {
            return response()->json(['error' => 'Notification not found'], 404);
        }

        // Proses normal jika user_id cocok
        if ($notification->user_id == Auth::id()) {
            $notification->is_read = 1;
            $notification->save();
            return response()->json(['message' => 'Notification marked as read']);
        }

        // Unauthorized jika user_id tidak cocok
        return response()->json([
            'error' => 'Unauthorized',
            'auth_id' => Auth::id(),
            'notification_user_id' => $notification->user_id
        ], 403);
    }

    /**
     * Menandai semua notifikasi user sebagai dibaca
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    /**
     * Menghapus notifikasi
     */

    public function destroy($id)
    {
        try {
            // Cari notifikasi berdasarkan ID
            $notification = Notification::findOrFail($id);


            // Untuk notifikasi sistem (user_id = null)
            if ($notification->user_id === null) {
                // Gunakan delete dan simpan hasil
                $deleted = $notification->delete();

                // Verifikasi penghapusan
                $exists = Notification::find($id);

                return response()->json([
                    'message' => 'System notification deleted',
                    'success' => $deleted,
                    'still_exists' => $exists ? true : false
                ]);
            }

            // Kode untuk notifikasi user normal
            if ($notification->user_id != Auth::id() && Auth::user()->role != 'admin') {
                return response()->json([
                    'error' => 'Unauthorized',
                    'auth_id' => Auth::id(),
                    'notification_user_id' => $notification->user_id
                ], 403);
            }

            // Hapus notifikasi user dengan delete biasa
            $deleted = $notification->delete();
            $exists = Notification::find($id);

            return response()->json([
                'message' => 'Notification deleted',
                'success' => $deleted,
                'still_exists' => $exists ? true : false
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Khusus untuk notifikasi tidak ditemukan
            return response()->json(['error' => 'Notification not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
