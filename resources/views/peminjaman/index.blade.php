@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')

<h3 class="mb-4">Data Peminjaman</h3>

{{-- Notifikasi sukses --}}
@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif

@foreach($peminjaman as $item)

@php

    $today =
        \Carbon\Carbon::today();

    $jatuhTempo =
        \Carbon\Carbon::parse(
            $item->tanggal_pengembalian
        );

    $terlambat =
        $today->gt($jatuhTempo);

    $hariLambat =
        $terlambat
        ? $jatuhTempo->diffInDays($today)
        : 0;

    $dendaEstimasi =
        $hariLambat * 5000;

@endphp

<div class="card shadow-sm mb-4">

    <div class="card-body">

        <h5 class="card-title mb-3">
            Peminjaman #{{ $item->id_peminjaman }}
        </h5>

        <p class="mb-1">
            <strong>Tanggal Pinjam:</strong>
            {{ $item->tanggal_peminjaman }}
        </p>

        <p class="mb-1">
            <strong>Jatuh Tempo:</strong>
            {{ $item->tanggal_pengembalian }}
        </p>

        <p class="mb-2">

            <strong>Status:</strong>

            @if($item->status_peminjaman == 'menunggu_pembayaran')

                <span class="badge bg-warning text-dark">
                    Menunggu Pembayaran
                </span>

            @elseif($item->status_peminjaman == 'disewa')

                <span class="badge bg-primary">
                    Disewa
                </span>

            @elseif($item->status_peminjaman == 'dikembalikan')

                <span class="badge bg-secondary">
                    Dikembalikan
                </span>

            @endif

        </p>

        {{-- Denda --}}
        @if($item->status_peminjaman === 'dikembalikan')

            <p class="mb-2">

                <strong>Denda Final:</strong>

                Rp {{ number_format(
                    $item->denda,
                    0,
                    ',',
                    '.'
                ) }}

            </p>

        @elseif($terlambat)

            <div class="alert alert-warning py-2 mb-2">

                ⚠️ Terlambat
                <strong>{{ $hariLambat }} hari</strong>

                — Estimasi denda:

                <strong>
                    Rp {{ number_format(
                        $dendaEstimasi,
                        0,
                        ',',
                        '.'
                    ) }}
                </strong>

            </div>

            <p class="text-muted mb-2">
                Denda dihitung saat barang dikembalikan
            </p>

        @else

            <p class="text-success mb-2">
                ✅ Belum ada denda
            </p>

        @endif

        {{-- Detail Barang --}}
        <div class="mb-3">

            <strong>Barang Dipinjam:</strong>

            <ul class="mb-0 mt-1">

                @foreach($item->details as $detail)

                    <li>

                        {{ $detail->barang->nama_barang }}

                        —
                        {{ $detail->jumlah_pinjam }} unit

                    </li>

                @endforeach

            </ul>

        </div>

        <div class="d-flex gap-2 flex-wrap">

            {{-- Tombol Bayar --}}
            @if($item->status_peminjaman == 'menunggu_pembayaran')

                <a href="{{ route(
                    'peminjaman.bayar',
                    $item->id_peminjaman
                ) }}"
                   class="btn btn-success btn-sm">

                    Bayar

                </a>

            @endif

            {{-- Tombol Kembalikan --}}
            @if($item->status_peminjaman == 'disewa')

                <form action="{{ route(
                    'peminjaman.kembalikan',
                    $item->id_peminjaman
                ) }}"
                      method="POST">

                    @csrf

                    @if($terlambat)

                        <button type="submit"
                                class="btn btn-danger btn-sm"

                                onclick="return confirm(
                                    'Denda Rp {{ number_format(
                                        $dendaEstimasi,
                                        0,
                                        ',',
                                        '.'
                                    ) }} akan dikenakan. Lanjutkan?'
                                )">

                            Kembalikan

                        </button>

                    @else

                        <button type="submit"
                                class="btn btn-danger btn-sm">

                            Kembalikan

                        </button>

                    @endif

                </form>

            @endif

        </div>

    </div>

</div>

@endforeach

@endsection