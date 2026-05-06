@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard</h1>

    <div class="card mb-3">
        <div class="card-body">
            <p>Total Barang: {{ $totalBarang }}</p>
            <p>Total Peminjaman: {{ $totalPeminjaman }}</p>
            <p>Sedang Dipinjam: {{ $dipinjam }}</p>
            <p>Sudah Dikembalikan: {{ $dikembalikan }}</p>
        </div>
    </div>
</div>
@endsection