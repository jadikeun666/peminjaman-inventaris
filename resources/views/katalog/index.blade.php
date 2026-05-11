@extends('layouts.app')

@section('title', 'Katalog Inventaris')

@section('content')

<!-- Header halaman -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-semibold">Katalog Inventaris</h4>
        <small class="text-muted">
            Selamat datang, {{ Auth::user()->name }} 👋
        </small>
    </div>
    <span class="badge bg-primary">
        {{ $barang->count() }} barang tersedia
    </span>
</div>

<!-- Notifikasi sukses -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Search bar -->
<form method="GET" action="{{ route('katalog') }}" class="mb-4">
    <div class="input-group">
        <span class="input-group-text bg-white">
            <i class="bi bi-search"></i>
        </span>
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Cari nama barang..."
            value="{{ request('search') }}"
        >
        @if(request('search'))
        <a href="{{ route('katalog') }}" class="btn btn-outline-secondary">
            Hapus filter
        </a>
        @endif
        <button class="btn btn-primary" type="submit">Cari</button>
    </div>
</form>

<!-- Grid kartu barang -->
@if($barang->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1"></i>
        <p class="mt-2">Tidak ada barang ditemukan.</p>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($barang as $item)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">

                <!-- Foto barang -->
                @if($item->foto)
                    <img
                        src="{{ str_starts_with($item->foto, 'http') ? $item->foto : asset('storage/' . $item->foto) }}"
                        class="card-img-top"
                        style="height: 200px; object-fit: cover;"
                        alt="{{ $item->nama_barang }}"
                        onerror="this.src='https://placehold.co/400x300/e2e8f0/94a3b8?text=No+Image'"
                    >
                @else
                    <!-- Placeholder jika belum ada foto -->
                    <div class="bg-light d-flex align-items-center justify-content-center"
                         style="height: 200px;">
                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                @endif

                <!-- Isi kartu -->
                <div class="card-body">

                    <!-- Kode barang -->
                    <span class="badge bg-secondary mb-2">
                        {{ $item->kode_barang }}
                    </span>

                    <!-- Nama -->
                    <h5 class="card-title fw-semibold">
                        {{ $item->nama_barang }}
                    </h5>

                    <!-- Harga sewa -->
                    <p class="text-primary fw-semibold mb-1">
                        Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}
                        <small class="text-muted fw-normal">/ hari</small>
                    </p>

                    <!-- Status stok -->
                    @if($item->jumlah_barang > 0)
                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                            Stok tersedia: {{ $item->jumlah_barang }} unit
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                            Stok habis
                        </span>
                    @endif

                </div>

                <!-- Tombol aksi -->
                <div class="card-footer bg-white border-0 pb-3">
                    @if($item->jumlah_barang > 0)
                        <a href="{{ route('peminjaman.create', ['barang' => $item->id_barang]) }}"
                           class="btn btn-primary w-100">
                            <i class="bi bi-bag-plus me-1"></i> Pinjam Sekarang
                        </a>
                    @else
                        <button class="btn btn-secondary w-100" disabled>
                            Stok Habis
                        </button>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection