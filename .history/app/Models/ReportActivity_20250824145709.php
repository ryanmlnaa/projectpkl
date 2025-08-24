<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales',
        'aktivitas',
        'tanggal',
        'lokasi',
        'evidence',
        'hasil_kendala',
        'status',
    ];
}
