<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'cluster',
        'competitor_name',
        'paket',
        'kecepatan',
        'kuota',
        'harga',
        'fitur_tambahan',
        'keterangan',
    ];
}
