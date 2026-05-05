<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $primaryKey = 'id_barang';
protected $fillable = [
    'nama_barang', 'kode_barang',
    'jumlah_barang', 'harga_sewa'
];

public function detailPeminjaman() {
    return $this->hasMany(DetailPeminjaman::class, 'id_barang');
}
}
