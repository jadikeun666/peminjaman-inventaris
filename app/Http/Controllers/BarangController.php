<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Barang::query();

        // 🔍 fitur search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $barang = $query->orderBy('nama_barang')->get();

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
            'nama_barang'   => 'required|string|max:30',
            'kode_barang'   => 'required|unique:barang,kode_barang|max:10',
            'jumlah_barang' => 'required|integer|min:0',
            'harga_sewa'    => 'required|numeric|min:0',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = null;

        // 📸 upload foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('barang', 'public');
        }

        Barang::create([
            'nama_barang'   => $request->nama_barang,
            'kode_barang'   => $request->kode_barang,
            'jumlah_barang' => $request->jumlah_barang,
            'harga_sewa'    => $request->harga_sewa,
            'foto'          => $fotoPath,
        ]);

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil ditambahkan!');
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
            'nama_barang'   => 'required|string|max:30',
            'kode_barang'   => 'required|unique:barang,kode_barang,' . $id . ',id_barang|max:10',
            'jumlah_barang' => 'required|integer|min:0',
            'harga_sewa'    => 'required|numeric|min:0',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'nama_barang'   => $request->nama_barang,
            'kode_barang'   => $request->kode_barang,
            'jumlah_barang' => $request->jumlah_barang,
            'harga_sewa'    => $request->harga_sewa,
        ];

        // 📸 kalau upload foto baru
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('barang', 'public');
        }

        $barang->update($data);

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil diupdate!');
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