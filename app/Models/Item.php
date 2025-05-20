<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    // Menentukan kolom yang bisa diisi
    protected $fillable = [
        'type',
        'item_name',
        'category',
        'date_of_event',
        'description',
        'email',
        'phone_number',
        'location',
        'photo_path',
        'user_id',
        'status'
    ];

    protected $casts = [
        'date_of_event' => 'datetime',
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
