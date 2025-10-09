<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model.
     *
     * @var string
     */
    protected $table = 'notifikasi';

    // Disable timestamps karena tabel hanya punya created_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'message',
        'created_at',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
