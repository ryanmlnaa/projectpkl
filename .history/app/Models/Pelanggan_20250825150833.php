<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';

    protected $fillable = [
    'id_pelanggan',
    'nama_pelanggan',
    'bandwidth',
    'alamat',
    'latitude',
    'longitude',
    'nomor_telepon',
    'cluster',
    'kode_fat',
];


    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];
}
