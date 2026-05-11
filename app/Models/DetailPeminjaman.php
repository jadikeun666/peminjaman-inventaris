<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';

    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_peminjaman',
        'id_barang',
        'jumlah_pinjam',
        'biaya_sewa',
    ];

    /**
     * Relasi ke tabel barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    /**
     * Relasi ke peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman');
    }
}