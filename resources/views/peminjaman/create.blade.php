@extends('layouts.app')

@section('title', 'Form Peminjaman')

@section('content')

<div class="row justify-content-center">
<div class="col-md-7">

    <h4 class="mb-1 fw-semibold">Form Peminjaman Barang</h4>
    <p class="text-muted mb-4">Isi data peminjaman dengan lengkap</p>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $e)
                <div>{{ $e }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('peminjaman.store') }}">
        @csrf

        {{-- Pilih Barang --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">
                Barang yang Dipinjam
            </label>

            <select name="barang[0][id]"
                    class="form-select"
                    id="selectBarang"
                    required
                    onchange="hitungEstimasi()">

                <option value="">-- Pilih Barang --</option>

                @foreach($barang as $item)

                    <option value="{{ $item->id_barang }}"
                            data-harga="{{ $item->harga_sewa }}"
                            data-stok="{{ $item->jumlah_barang }}"
                            {{ request('barang') == $item->id_barang ? 'selected' : '' }}>

                        {{ $item->nama_barang }}
                        (Stok: {{ $item->jumlah_barang }})
                        — Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}/hari

                    </option>

                @endforeach

            </select>
        </div>

        {{-- Jumlah --}}
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Jumlah Pinjam
            </label>

            <input type="number"
                   name="barang[0][jumlah]"
                   id="jumlah"
                   class="form-control"
                   value="1"
                   min="1"
                   max="1"
                   onchange="hitungEstimasi()"
                   required>

            <small class="text-muted" id="infoStok"></small>

        </div>

        {{-- Tanggal Peminjaman --}}
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Tanggal Peminjaman
            </label>

            <input type="date"
                   name="tanggal_peminjaman"
                   id="tglPinjam"
                   class="form-control"
                   value="{{ date('Y-m-d') }}"
                   min="{{ date('Y-m-d') }}"
                   onchange="hitungEstimasi()"
                   required>

        </div>

        {{-- Tanggal Pengembalian --}}
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Tanggal Pengembalian
            </label>

            <input type="date"
                   name="tanggal_pengembalian"
                   id="tglKembali"
                   class="form-control"
                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                   onchange="hitungEstimasi()"
                   required>

        </div>

        {{-- Estimasi Biaya --}}
        <div class="alert alert-info py-2 mb-4"
             id="estimasiBox"
             style="display:none;">

            <div class="d-flex justify-content-between">

                <span>Estimasi biaya sewa:</span>

                <strong id="estimasiHarga">
                    Rp 0
                </strong>

            </div>

            <small class="text-muted"
                   id="estimasiDetail">
            </small>

        </div>

        <div class="d-flex gap-2">

            <button type="submit"
                    class="btn btn-primary flex-fill">

                Ajukan Peminjaman

            </button>

            <a href="{{ route('home') }}"
               class="btn btn-outline-secondary flex-fill">

                Batal

            </a>

        </div>

    </form>

</div>
</div>

<script>

function hitungEstimasi() {

    const select = document.getElementById('selectBarang');

    const jumlah =
        parseInt(document.getElementById('jumlah').value) || 0;

    const tglPinjam =
        document.getElementById('tglPinjam').value;

    const tglKembali =
        document.getElementById('tglKembali').value;

    const opt =
        select.options[select.selectedIndex];

    const harga =
        parseInt(opt?.dataset?.harga) || 0;

    const stok =
        parseInt(opt?.dataset?.stok) || 0;

    const inputJumlah =
        document.getElementById('jumlah');

    // Batasi jumlah sesuai stok
    inputJumlah.max = stok;

    // Jika input lebih besar dari stok
    if (parseInt(inputJumlah.value) > stok) {

        inputJumlah.value = stok;
    }

    // Info stok
    document.getElementById('infoStok').textContent =
        stok > 0
        ? `Maksimal ${stok} unit`
        : '';

    // Jika data belum lengkap
    if (!tglPinjam || !tglKembali || !harga) {

        document.getElementById('estimasiBox')
                .style.display = 'none';

        return;
    }

    // Hitung jumlah hari
    const hari = Math.round(
        (
            new Date(tglKembali) -
            new Date(tglPinjam)
        ) / (1000 * 60 * 60 * 24)
    );

    if (hari <= 0) return;

    // Hitung total
    const total =
        harga * jumlah * hari;

    const fmt =
        new Intl.NumberFormat('id-ID')
        .format(total);

    document.getElementById('estimasiHarga')
        .textContent = `Rp ${fmt}`;

    document.getElementById('estimasiDetail')
        .textContent =
        `${jumlah} unit × Rp ${
            new Intl.NumberFormat('id-ID')
            .format(harga)
        } × ${hari} hari`;

    document.getElementById('estimasiBox')
        .style.display = 'block';
}

// Auto select barang dari URL
window.addEventListener('DOMContentLoaded', () => {

    const params =
        new URLSearchParams(window.location.search);

    const id = params.get('barang');

    if (id) {

        const sel =
            document.getElementById('selectBarang');

        for (let opt of sel.options) {

            if (opt.value == id) {

                opt.selected = true;
                break;
            }
        }
    }

    // Jalankan estimasi otomatis
    hitungEstimasi();
});

</script>

@endsection