@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')

<h3>Data Peminjaman</h3>

@foreach($peminjaman as $item)

<div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">

    <p>ID: {{ $item->id_peminjaman }}</p>
    <p>Tanggal Pinjam: {{ $item->tanggal_peminjaman }}</p>
    <p>Jatuh Tempo: {{ $item->tanggal_pengembalian }}</p>
    <p>Status: {{ $item->status_peminjaman }}</p>

    <!-- 🔴 INI BAGIAN DENDA -->
    <p>Denda: Rp {{ number_format($item->denda) }}</p>

    <!-- Tombol Bayar -->
    @if($item->status_peminjaman == 'menunggu_pembayaran')
        <a href="{{ route('peminjaman.bayar', $item->id_peminjaman) }}"
           class="btn btn-success btn-sm">
            Bayar
        </a>
    @endif

    <!-- Tombol Kembalikan -->
    @if($item->status_peminjaman == 'disewa')
        <form action="{{ route('peminjaman.kembali', $item->id_peminjaman) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                Kembalikan
            </button>
        </form>
    @endif

</div>

@endforeach

@endsection