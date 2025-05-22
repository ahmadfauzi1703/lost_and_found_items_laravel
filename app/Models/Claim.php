<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'type',
        'claimer_name',
        'claimer_nim',
        'claimer_email',
        'claimer_phone',
        'ownership_proof',
        'proof_document',
        'claim_date',
        'notes',
        'status',
        'where_found',   // Tambahkan field untuk kasus return
        'item_photo'     // Tambahkan field untuk kasus return
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
