<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    // Menentukan kolom yang bisa diisi
    protected $fillable = [
        'user_id',
        'message',
        'is_read'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
