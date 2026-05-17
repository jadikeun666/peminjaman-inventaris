<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'id_user',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'status_peminjaman',
        'denda',
        'bukti_bayar',
        'metode_pembayaran',
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id'
        );
    }

    /**
     * Relasi detail peminjaman
     */
    public function details()
    {
        return $this->hasMany(
            DetailPeminjaman::class,
            'id_peminjaman',
            'id_peminjaman'
        );
    }
}