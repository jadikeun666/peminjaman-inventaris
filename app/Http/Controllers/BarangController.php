<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));

            $query = Barang::query();

    if ($request->search) {
        $query->where('nama_barang', 'like', '%' . $request->search . '%');
    }

    $barang = $query->get();

    return view('barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'   => 'required',
            'kode_barang'   => 'required|unique:barang,kode_barang',
            'jumlah_barang' => 'required|integer',
            'harga_sewa'    => 'required|numeric',
        ]);

        Barang::create([
            'nama_barang'   => $request->nama_barang,
            'kode_barang'   => $request->kode_barang,
            'jumlah_barang' => $request->jumlah_barang,
            'harga_sewa'    => $request->harga_sewa,
        ]);

        return redirect()->route('barang.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama_barang'   => 'required',
            'kode_barang'   => 'required|unique:barang,kode_barang,' . $id . ',id_barang',
            'jumlah_barang' => 'required|integer',
            'harga_sewa'    => 'required|numeric',
        ]);

        $barang->update([
            'nama_barang'   => $request->nama_barang,
            'kode_barang'   => $request->kode_barang,
            'jumlah_barang' => $request->jumlah_barang,
            'harga_sewa'    => $request->harga_sewa,
        ]);

        return redirect()->route('barang.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index');
    }
}