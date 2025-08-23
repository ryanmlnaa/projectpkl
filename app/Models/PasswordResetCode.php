<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    /**
     * Generate kode 6 digit random
     */
    public static function generateCode()
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Cari kode yang masih aktif untuk email tertentu
     */
    public static function getActiveCode($email)
    {
        return self::where('email', $email)
                   ->where('expires_at', '>', Carbon::now())
                   ->orderBy('created_at', 'desc')
                   ->first();
    }

    /**
     * Cek apakah kode masih valid
     */
    public function isValid()
    {
        return $this->expires_at > Carbon::now();
    }
}