<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang'; // 🔥 WAJIB

    protected $primaryKey = 'id_barang'; // kalau pakai id_barang

    public $timestamps = false; // kalau tidak pakai created_at

    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'jumlah_barang',
        'harga_sewa',
        'foto' // ✅ ditambahkan
    ];

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_barang', 'id_barang');
    }
}