<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
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
}
