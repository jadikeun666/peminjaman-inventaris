<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            'id_user'              => Auth::id(),
            'tanggal_peminjaman'   => $request->tanggal_peminjaman,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'status_peminjaman'    => 'menunggu_pembayaran',
            'denda'                => 0,
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

        return redirect()->route('peminjaman.bayar', $peminjaman->id_peminjaman)
                         ->with('info', 'Form berhasil! Selesaikan pembayaran.');
    }

    /**
     * Halaman pembayaran
     */
    public function bayar(string $id)
    {
        $peminjaman = Peminjaman::with('details.barang')
                        ->findOrFail($id);

        $qrisImage    = Setting::get('qris_image');
        $namaMerchant = Setting::get('nama_merchant');
        $infoRekening = Setting::get('info_rekening');

        return view('peminjaman.bayar', compact(
            'peminjaman',
            'qrisImage',
            'namaMerchant',
            'infoRekening'
        ));
    }

    /**
     * Upload bukti pembayaran
     */
public function uploadBukti(Request $request, string $id)
{
    $request->validate([
        'bukti_bayar'       => 'required|image|mimes:jpg,jpeg,png|max:3048',
        'metode_pembayaran' => 'required|in:qris,tunai',
    ]);

    $peminjaman = Peminjaman::findOrFail($id);

    $path = $request->file('bukti_bayar')
                    ->store('bukti_bayar', 'public');

    $peminjaman->update([
        'bukti_bayar'       => $path,
        'metode_pembayaran' => $request->metode_pembayaran,

        // 🔥 INI YANG KAMU BUTUHKAN
        'status_peminjaman' => 'disewa',
    ]);

    return redirect()->route('peminjaman.index')
                     ->with('success', 'Pembayaran berhasil! Status menjadi disewa.');
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

        DetailPeminjaman::where('id_peminjaman', $peminjaman->id_peminjaman)
                        ->delete();

        $peminjaman->delete();

        return redirect()->route('peminjaman.index');
    }

    /**
     * Hitung denda saat pengembalian
     */
    public function hitungDenda(Peminjaman $peminjaman)
    {
        $today        = Carbon::today();
        $jatuhTempo   = Carbon::parse($peminjaman->tanggal_pengembalian);
        $dendaPerHari = 5000;

        if ($today->gt($jatuhTempo)) {

            $hariTerlambat = $today->diffInDays($jatuhTempo);
            $denda = $hariTerlambat * $dendaPerHari;

            $peminjaman->update([
                'denda'             => $denda,
                'status_peminjaman' => 'dikembalikan',
            ]);

        } else {

            $peminjaman->update([
                'status_peminjaman' => 'dikembalikan',
            ]);
        }

        return back()->with('success', 'Pengembalian berhasil');
    }
}