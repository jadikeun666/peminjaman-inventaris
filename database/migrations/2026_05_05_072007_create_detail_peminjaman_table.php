<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
                $table->id('id_detail');
                $table->foreignId('id_peminjaman')
                    ->constrained('peminjaman','id_peminjaman');
                $table->foreignId('id_barang')
                    ->constrained('barang','id_barang');
                $table->integer('jumlah_pinjam');
                $table->integer('biaya_sewa');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
