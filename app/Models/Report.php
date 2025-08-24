<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // PERBAIKAN: Pastikan semua field bisa diisi
    protected $fillable = [
        'sales',
        'aktivitas', 
        'tanggal',
        'lokasi',
        'evidence',
        'hasil_kendala',
        'status'
    ];

    // PERBAIKAN: Cast tanggal ke format yang benar
    protected $casts = [
        'tanggal' => 'date'
    ];

    // PERBAIKAN: Tambahkan accessor untuk URL gambar
    public function getEvidenceUrlAttribute()
    {
        if ($this->evidence) {
            return asset('storage/' . $this->evidence);
        }
        return null;
    }
}