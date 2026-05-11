@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Pembayaran Peminjaman</h5>
        </div>

        <div class="card-body">

            <!-- DETAIL PEMINJAMAN -->
            <h6 class="fw-bold mb-3">Detail Peminjaman</h6>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <tr>
                        <th width="30%">Tanggal Pinjam</th>
                        <td>{{ $peminjaman->tanggal_peminjaman }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Kembali</th>
                        <td>{{ $peminjaman->tanggal_pengembalian }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-info">
                                {{ $peminjaman->status_peminjaman }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- DAFTAR BARANG -->
            <h6 class="fw-bold mt-4 mb-3">Daftar Barang</h6>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Biaya</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $total = 0; @endphp

                        @foreach($peminjaman->details as $detail)
                            @php $total += $detail->biaya_sewa; @endphp

                            <tr>
                                <td>{{ $detail->barang->nama_barang }}</td>
                                <td>{{ $detail->jumlah_pinjam }}</td>
                                <td>Rp {{ number_format($detail->biaya_sewa) }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="table-light fw-bold">
                            <td colspan="2" class="text-end">Total</td>
                            <td>Rp {{ number_format($total) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <hr>

            <!-- PEMBAYARAN -->
            <h6 class="fw-bold mb-3">Pembayaran</h6>

            <div class="mb-3">
                <div><strong>Merchant:</strong></div>
                <div class="text-muted">{{ $namaMerchant }}</div>
            </div>

            <div class="mb-3">
                <div><strong>Info Rekening:</strong></div>
                <div class="text-muted">{{ $infoRekening }}</div>
            </div>

            @if($qrisImage)
                <div class="mb-4 text-center">
                    <img src="{{ asset('storage/' . $qrisImage) }}"
                         alt="QRIS"
                         class="img-fluid rounded border"
                         style="max-width: 300px;">
                </div>
            @endif

            <!-- FORM -->
            <form action="{{ route('peminjaman.upload_bukti', $peminjaman->id_peminjaman) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Metode Pembayaran
                    </label>

                    <select name="metode_pembayaran"
                            class="form-select"
                            required>

                        <option value="">-- Pilih --</option>
                        <option value="qris">QRIS</option>
                        <option value="tunai">Tunai</option>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Upload Bukti Pembayaran
                    </label>

                    <input type="file"
                           name="bukti_bayar"
                           class="form-control"
                           required>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary px-4">
                        Kirim Bukti
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection