@extends('layouts.app')
@section('title', 'Akses Ditolak')
@section('content')
<div class="text-center py-5">
    <div style="font-size:4rem">🚫</div>
    <h4 class="mt-3 fw-semibold">Akses Ditolak</h4>
    <p class="text-muted">Kamu tidak punya izin untuk membuka halaman ini.</p>
    <a href="{{ route('home') }}" class="btn btn-primary mt-2">
        Kembali ke Katalog
    </a>
</div>
@endsection