<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    /**
     * Nama tabel basis data.
     *
     * @var string
     */
    protected $table = 'klaim';

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
        'where_found',   
        'item_photo'    
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
