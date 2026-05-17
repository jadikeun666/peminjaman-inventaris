<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';

    protected $primaryKey = 'id_detail_peminjaman';

    protected $fillable = [
        'id_peminjaman',
        'id_barang',
        'jumlah_pinjam',
        'biaya_sewa',
    ];

    /**
     * Relasi ke peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(
            Peminjaman::class,
            'id_peminjaman',
            'id_peminjaman'
        );
    }

    /**
     * Relasi ke barang
     */
    public function barang()
    {
        return $this->belongsTo(
            Barang::class,
            'id_barang',
            'id_barang'
        );
    }
}