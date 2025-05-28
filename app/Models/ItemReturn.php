<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemReturn extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'returns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id',
        'returner_id',
        'returner_name',
        'returner_nim',
        'returner_email',
        'returner_phone',
        'where_found',
        'item_photo',
        'return_date',
        'notes',
        'status',
    ];

    /**
     * Get the item associated with the return.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the returner (user) associated with the return.
     */
    public function returner()
    {
        return $this->belongsTo(User::class, 'returner_id');
    }
}
