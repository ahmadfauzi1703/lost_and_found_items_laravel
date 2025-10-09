<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RememberToken extends Model
{

    /**
     * Nama tabel yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'token_pengingat';

    // Menentukan kolom yang bisa diisi
    protected $fillable = [
        'user_id',
        'token',
        'expires_at'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
