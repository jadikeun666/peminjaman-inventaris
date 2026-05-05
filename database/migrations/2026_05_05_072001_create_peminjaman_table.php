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
        Schema::create('peminjaman', function (Blueprint $table) {
                $table->id('id_peminjaman');
                $table->foreignId('id_user')->constrained('users');
                $table->date('tanggal_peminjaman');
                $table->date('tanggal_pengembalian');
                $table->boolean('status_peminjaman')->default(false);
                $table->integer('denda')->default(0);
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
