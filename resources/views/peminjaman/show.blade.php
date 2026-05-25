@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')

<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Detail Peminjaman
            </h3>

            <p class="text-muted mb-0">
                Informasi lengkap transaksi peminjaman barang
            </p>
        </div>

        <a href="{{ route('peminjaman.index') }}"
           class="btn btn-outline-secondary rounded-3">

            <i class="bi bi-arrow-left me-1"></i>
            Kembali

        </a>

    </div>

    {{-- Alert --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif

    {{-- Card utama --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        {{-- Header card --}}
        <div class="card-header bg-primary text-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0 fw-semibold">
                        Peminjaman #{{ $peminjaman->id_peminjaman }}
                    </h5>
                </div>

                <div>

                    @if($peminjaman->status_peminjaman == 'menunggu_pembayaran')

                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                            Menunggu Pembayaran
                        </span>

                    @elseif($peminjaman->status_peminjaman == 'disewa')

                        <span class="badge bg-success px-3 py-2 rounded-pill">
                            Sedang Disewa
                        </span>

                    @elseif($peminjaman->status_peminjaman == 'dikembalikan')

                        <span class="badge bg-secondary px-3 py-2 rounded-pill">
                            Dikembalikan
                        </span>

                    @endif

                </div>

            </div>

        </div>

        {{-- Body --}}
        <div class="card-body p-4">

            {{-- Detail informasi --}}
            <div class="row g-4 mb-4">

                <div class="col-md-6">

                    <div class="border rounded-4 p-3 h-100 bg-light">

                        <small class="text-muted d-block mb-1">
                            Peminjam
                        </small>

                        <h6 class="fw-semibold mb-0">
                            {{ $peminjaman->user->name }}
                        </h6>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="border rounded-4 p-3 h-100 bg-light">

                        <small class="text-muted d-block mb-1">
                            Tanggal Pinjam
                        </small>

                        <h6 class="fw-semibold mb-0">
                            {{ $peminjaman->tanggal_peminjaman }}
                        </h6>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="border rounded-4 p-3 h-100 bg-light">

                        <small class="text-muted d-block mb-1">
                            Jatuh Tempo
                        </small>

                        <h6 class="fw-semibold mb-0">
                            {{ $peminjaman->tanggal_pengembalian }}
                        </h6>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="border rounded-4 p-3 h-100 bg-light">

                        <small class="text-muted d-block mb-1">
                            Denda
                        </small>

                        <h5 class="fw-bold text-danger mb-0">

                            Rp {{ number_format(
                                $peminjaman->denda,
                                0,
                                ',',
                                '.'
                            ) }}

                        </h5>

                    </div>

                </div>

            </div>

            {{-- Divider --}}
            <hr class="my-4">

            {{-- Tabel barang --}}
            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-semibold mb-0">
                    Barang Dipinjam
                </h5>

                <span class="badge bg-primary-subtle text-primary px-3 py-2">

                    {{ $peminjaman->details->count() }}
                    item

                </span>

            </div>

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>Nama Barang</th>
                            <th width="120">Jumlah</th>
                            <th width="200">Biaya Sewa</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($peminjaman->details as $detail)

                            <tr>

                                <td>

                                    <div class="fw-semibold">
                                        {{ $detail->barang->nama_barang }}
                                    </div>

                                </td>

                                <td>

                                    <span class="badge bg-secondary rounded-pill px-3 py-2">

                                        {{ $detail->jumlah_pinjam }}

                                    </span>

                                </td>

                                <td class="fw-semibold text-primary">

                                    Rp {{ number_format(
                                        $detail->biaya_sewa,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection