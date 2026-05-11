<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $primaryKey = 'id_peminjaman';
protected $fillable = [
    'id_user', 'tanggal_peminjaman',
    'tanggal_pengembalian', 'status_peminjaman', 'denda'
];

public function user() {
    return $this->belongsTo(User::class, 'id_user');
}
public function details() {
    return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman');
}
}
