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
    public function show(Notification $notification)
    {
        // Pastikan user hanya bisa melihat notifikasinya sendiri
        if ($notification->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($notification);
    }

    /**
     * Menandai notifikasi sebagai dibaca
     */
    public function markAsRead(Notification $notification)
    {
        // Pastikan user hanya bisa mengubah notifikasinya sendiri
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->is_read = 1;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
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
    public function destroy(Notification $notification)
    {
        // Pastikan user hanya bisa menghapus notifikasinya sendiri
        if ($notification->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();
        return response()->json(['message' => 'Notification deleted']);
    }
}
