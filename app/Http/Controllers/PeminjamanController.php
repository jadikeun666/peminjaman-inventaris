<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ✅ Admin lihat semua, user biasa hanya miliknya
        $peminjaman = Peminjaman::with(
                'details.barang',
                'user'
            )
            ->when(
                !Auth::user()->isAdmin(),
                function ($query) {

                    $query->where(
                        'id_user',
                        Auth::id()
                    );
                }
            )
            ->orderBy(
                'created_at',
                'desc'
            )
            ->get();

        return view(
            'peminjaman.index',
            compact('peminjaman')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya barang dengan stok tersedia
        $barang = Barang::where(
                        'jumlah_barang',
                        '>',
                        0
                    )
                    ->orderBy('nama_barang')
                    ->get();

        return view(
            'peminjaman.create',
            compact('barang')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'tanggal_peminjaman' =>
                'required|date|after_or_equal:today',

            'tanggal_pengembalian' =>
                'required|date|after:tanggal_peminjaman',

            'barang' =>
                'required|array',

            'barang.*.id' =>
                'required|exists:barang,id_barang',

            'barang.*.jumlah' =>
                'required|integer|min:1',
        ]);

        // Validasi stok
        foreach ($request->barang as $item) {

            $barang =
                Barang::findOrFail($item['id']);

            // Jika stok habis
            if ($barang->jumlah_barang <= 0) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'barang' =>
                            "Stok {$barang->nama_barang} sudah habis."
                    ]);
            }

            // Jika jumlah melebihi stok
            if (
                $item['jumlah']
                > $barang->jumlah_barang
            ) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'barang' =>
                            "Stok {$barang->nama_barang} hanya tersisa {$barang->jumlah_barang} unit."
                    ]);
            }
        }

        DB::transaction(function () use (
            $request,
            &$peminjaman
        ) {

            // Simpan peminjaman
            $peminjaman = Peminjaman::create([

                'id_user' =>
                    Auth::id(),

                'tanggal_peminjaman' =>
                    $request->tanggal_peminjaman,

                'tanggal_pengembalian' =>
                    $request->tanggal_pengembalian,

                'status_peminjaman' =>
                    'menunggu_pembayaran',

                'denda' =>
                    0,
            ]);

            // Simpan detail
            foreach ($request->barang as $item) {

                $barang =
                    Barang::findOrFail($item['id']);

                DetailPeminjaman::create([

                    'id_peminjaman' =>
                        $peminjaman->id_peminjaman,

                    'id_barang' =>
                        $item['id'],

                    'jumlah_pinjam' =>
                        $item['jumlah'],

                    'biaya_sewa' =>
                        $item['jumlah']
                        * $barang->harga_sewa,
                ]);

                // Kurangi stok
                $barang->decrement(
                    'jumlah_barang',
                    $item['jumlah']
                );
            }
        });

        return redirect()
            ->route(
                'peminjaman.bayar',
                $peminjaman->id_peminjaman
            )
            ->with(
                'info',
                'Form berhasil! Selesaikan pembayaran.'
            );
    }

    /**
     * Halaman pembayaran
     */
    public function bayar(string $id)
    {
        $peminjaman = Peminjaman::with(
                            'details.barang'
                        )
                        ->findOrFail($id);

        // ✅ Admin boleh akses semua
        if (
            !Auth::user()->isAdmin()
            && $peminjaman->id_user !== Auth::id()
        ) {
            abort(
                403,
                'Kamu tidak punya akses ke pembayaran ini.'
            );
        }

        $qrisImage =
            Setting::get('qris_image');

        $namaMerchant =
            Setting::get('nama_merchant');

        $infoRekening =
            Setting::get('info_rekening');

        return view(
            'peminjaman.bayar',
            compact(
                'peminjaman',
                'qrisImage',
                'namaMerchant',
                'infoRekening'
            )
        );
    }

    /**
     * Upload bukti pembayaran
     */
    public function uploadBukti(
        Request $request,
        string $id
    ) {

        $request->validate([

            'bukti_bayar' =>
                'required|image|mimes:jpg,jpeg,png|max:3048',

            'metode_pembayaran' =>
                'required|in:qris,tunai',
        ]);

        $peminjaman =
            Peminjaman::findOrFail($id);

        // ✅ Admin boleh upload semua
        if (
            !Auth::user()->isAdmin()
            && $peminjaman->id_user !== Auth::id()
        ) {
            abort(
                403,
                'Kamu tidak punya akses ke peminjaman ini.'
            );
        }

        $path = $request
                    ->file('bukti_bayar')
                    ->store(
                        'bukti_bayar',
                        'public'
                    );

        $peminjaman->update([

            'bukti_bayar' =>
                $path,

            'metode_pembayaran' =>
                $request->metode_pembayaran,

            'status_peminjaman' =>
                'disewa',
        ]);

        return redirect()
            ->route('peminjaman.index')
            ->with(
                'success',
                'Pembayaran berhasil! Status menjadi disewa.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $peminjaman = Peminjaman::with(
                            'details.barang',
                            'user'
                        )
                        ->findOrFail($id);

        // ✅ Admin boleh lihat semua
        if (
            !Auth::user()->isAdmin()
            && $peminjaman->id_user !== Auth::id()
        ) {
            abort(
                403,
                'Kamu tidak punya akses ke peminjaman ini.'
            );
        }

        return view(
            'peminjaman.show',
            compact('peminjaman')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $peminjaman =
            Peminjaman::findOrFail($id);

        // ✅ Admin boleh edit semua
        if (
            !Auth::user()->isAdmin()
            && $peminjaman->id_user !== Auth::id()
        ) {
            abort(
                403,
                'Kamu tidak punya akses ke peminjaman ini.'
            );
        }

        $barang = Barang::all();

        return view(
            'peminjaman.edit',
            compact(
                'peminjaman',
                'barang'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        string $id
    ) {

        $peminjaman =
            Peminjaman::findOrFail($id);

        // ✅ Admin boleh update semua
        if (
            !Auth::user()->isAdmin()
            && $peminjaman->id_user !== Auth::id()
        ) {
            abort(
                403,
                'Kamu tidak punya akses ke peminjaman ini.'
            );
        }

        $request->validate([

            'tanggal_peminjaman' =>
                'required|date',

            'tanggal_pengembalian' =>
                'required|date|after:tanggal_peminjaman',

            'status_peminjaman' =>
                'required',
        ]);

        $peminjaman->update([

            'tanggal_peminjaman' =>
                $request->tanggal_peminjaman,

            'tanggal_pengembalian' =>
                $request->tanggal_pengembalian,

            'status_peminjaman' =>
                $request->status_peminjaman,
        ]);

        return redirect()
            ->route('peminjaman.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $peminjaman =
            Peminjaman::findOrFail($id);

        // ✅ Admin boleh hapus semua
        if (
            !Auth::user()->isAdmin()
            && $peminjaman->id_user !== Auth::id()
        ) {
            abort(
                403,
                'Kamu tidak punya akses ke peminjaman ini.'
            );
        }

        // ✅ Kembalikan stok sebelum hapus
        foreach ($peminjaman->details as $detail) {

            $detail->barang->increment(
                'jumlah_barang',
                $detail->jumlah_pinjam
            );
        }

        DetailPeminjaman::where(
            'id_peminjaman',
            $peminjaman->id_peminjaman
        )->delete();

        $peminjaman->delete();

        return redirect()
            ->route('peminjaman.index');
    }

    /**
     * Hitung denda saat pengembalian
     */
    public function hitungDenda(
        Peminjaman $peminjaman
    ) {

        // ✅ Admin boleh proses semua
        if (
            !Auth::user()->isAdmin()
            && $peminjaman->id_user !== Auth::id()
        ) {
            abort(
                403,
                'Kamu tidak punya akses ke peminjaman ini.'
            );
        }

        $today =
            Carbon::today();

        $jatuhTempo =
            Carbon::parse(
                $peminjaman->tanggal_pengembalian
            );

        $denda = 0;

        // Jika terlambat
        if ($today->gt($jatuhTempo)) {

            $denda =
                $jatuhTempo->diffInDays($today)
                * 5000;
        }

        // Update data
        $peminjaman->update([

            'denda' =>
                $denda,

            'status_peminjaman' =>
                'dikembalikan',
        ]);

        // Kembalikan stok
        foreach (
            $peminjaman->details
            as $detail
        ) {

            $detail->barang->increment(
                'jumlah_barang',
                $detail->jumlah_pinjam
            );
        }

        // Pesan
        $pesan = $denda > 0

            ? 'Dikembalikan. Denda: Rp '
                . number_format(
                    $denda,
                    0,
                    ',',
                    '.'
                )

            : 'Dikembalikan tepat waktu.';

        return back()->with(
            'success',
            $pesan
        );
    }
}