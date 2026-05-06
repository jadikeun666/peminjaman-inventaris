@extends('layouts.app')

@section('title', 'Katalog Barang')

@section('content')

<h2 class="mb-3">Katalog Barang</h2>
<p class="text-muted">Daftar barang yang tersedia untuk dipinjam</p>

<!-- Search -->
<form method="GET" action="{{ route('home') }}" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control"
               placeholder="Cari barang..."
               value="{{ request('search') }}">
        <button class="btn btn-primary">Cari</button>
    </div>
</form>

@if($barang->isEmpty())
    <div class="alert alert-info">Tidak ada barang</div>
@else
<div class="row">
    @foreach($barang as $item)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">

            <div class="card-body">
                <h5 class="card-title">{{ $item->nama_barang }}</h5>

                <p class="text-muted mb-1">
                    Kode: {{ $item->kode_barang }}
                </p>

                <p>
                    Stok:
                    @if($item->jumlah_barang > 0)
                        <span class="badge bg-success">
                            {{ $item->jumlah_barang }}
                        </span>
                    @else
                        <span class="badge bg-danger">
                            Habis
                        </span>
                    @endif
                </p>

                <p>
                    Harga:
                    <strong>
                        Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}
                    </strong>
                </p>
            </div>

            <div class="card-footer bg-white">
                @auth
                    <a href="{{ route('peminjaman.create') }}?barang={{ $item->id_barang }}"
                       class="btn btn-primary w-100">
                       Pinjam Sekarang
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="btn btn-outline-primary w-100">
                       Login untuk Pinjam
                    </a>
                @endauth
            </div>

        </div>
    </div>
    @endforeach
</div>
@endif

@endsection