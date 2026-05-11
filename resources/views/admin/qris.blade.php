@extends('layouts.app')
@section('title', 'Kelola QRIS')
@section('content')

<div class="row justify-content-center">
<div class="col-md-5">

    <h4 class="fw-semibold mb-4">Upload QR Code Pembayaran</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Preview QR saat ini --}}
    @if($qrisImage)
        <div class="text-center mb-4">
            <p class="text-muted small">QR Code aktif saat ini:</p>
            <img src="{{ asset('storage/' . $qrisImage) }}"
                 style="max-width:200px; border-radius:8px;">
        </div>
    @endif

    <form method="POST" action="{{ route('admin.qris.update') }}"
          enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Upload QR Code Baru
                @if($qrisImage) <span class="text-muted fw-normal">(opsional, untuk ganti)</span> @endif
            </label>
            <input type="file" name="qris_image" class="form-control"
                   accept="image/*" {{ $qrisImage ? '' : 'required' }}>
            <small class="text-muted">Screenshot QR dari aplikasi bank/DANA/GoPay</small>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Merchant</label>
            <input type="text" name="nama_merchant" class="form-control"
                   value="{{ $namaMerchant }}" required>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Info Rekening / Dompet</label>
            <input type="text" name="info_rekening" class="form-control"
                   value="{{ $infoRekening }}"
                   placeholder="BCA - 1234567890 a.n Nama" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Simpan</button>
    </form>

    <div class="mt-3 text-center">
        <a href="{{ route('home') }}" class="text-muted small">← Kembali ke katalog</a>
    </div>

</div>
</div>
@endsection