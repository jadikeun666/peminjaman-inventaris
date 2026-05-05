<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $peminjaman = Peminjaman::with('details.barang', 'user')->get();
        return view('peminjaman.index', compact('peminjaman'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::all();
        return view('peminjaman.create', compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_peminjaman'   => 'required|date|after_or_equal:today',
            'tanggal_pengembalian' => 'required|date|after:tanggal_peminjaman',
            'barang'               => 'required|array',
        ]);

        $peminjaman = Peminjaman::create([
            'id_user'               => Auth::id(),
            'tanggal_peminjaman'    => $request->tanggal_peminjaman,
            'tanggal_pengembalian'  => $request->tanggal_pengembalian,
            'status_peminjaman'     => 'dipinjam',
            'denda'                 => 0,
        ]);

        foreach ($request->barang as $item) {

            $barang = Barang::find($item['id']);

            DetailPeminjaman::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_barang'     => $item['id'],
                'jumlah_pinjam' => $item['jumlah'],
                'biaya_sewa'    => $item['jumlah'] * $barang->harga_sewa,
            ]);
        }

        return redirect()->route('peminjaman.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $peminjaman = Peminjaman::with('details.barang', 'user')
                        ->findOrFail($id);

        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $barang = Barang::all();

        return view('peminjaman.edit', compact('peminjaman', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $request->validate([
            'tanggal_peminjaman'   => 'required|date',
            'tanggal_pengembalian' => 'required|date|after:tanggal_peminjaman',
            'status_peminjaman'    => 'required',
        ]);

        $peminjaman->update([
            'tanggal_peminjaman'   => $request->tanggal_peminjaman,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'status_peminjaman'    => $request->status_peminjaman,
        ]);

        return redirect()->route('peminjaman.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // hapus detail dulu
        DetailPeminjaman::where('id_peminjaman', $peminjaman->id_peminjaman)->delete();

        $peminjaman->delete();

        return redirect()->route('peminjaman.index');
    }
}