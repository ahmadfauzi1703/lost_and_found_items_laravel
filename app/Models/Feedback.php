<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Feedback extends Model
{
    use HasFactory;
  protected $table = 'feedbacks';   // ← tambahkan baris ini

    protected $fillable = [
        'user_id',
        'rating',
        'description',
        'comments',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
