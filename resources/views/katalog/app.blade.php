@extends('layouts.app')

@section('title', 'Katalog Inventaris')

@section('content')
<div class="row">

    <input type="text" id="search"
           placeholder="Cari barang..."
           class="form-control mb-3">

    @foreach($barang as $item)
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5>{{ $item->nama_barang }}</h5>
                <p>Stok: {{ $item->jumlah_barang }}</p>
                <p>Harga: Rp {{ number_format($item->harga_sewa) }}/hari</p>
            </div>
        </div>
    </div>
    @endforeach

</div>
@endsection