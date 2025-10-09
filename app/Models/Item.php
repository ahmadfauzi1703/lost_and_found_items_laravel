<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    /**
     * Nama tabel yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'barang';

    
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
        'status',
        'report_by',
    ];

    protected $casts = [
        'date_of_event' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
