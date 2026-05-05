<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index()
    {
        $totalBarang      = Barang::count();
        $totalPeminjaman  = Peminjaman::count();
        $dipinjam         = Peminjaman::where('status_peminjaman', 'dipinjam')->count();
        $dikembalikan     = Peminjaman::where('status_peminjaman', 'dikembalikan')->count();

        return view('dashboard.index', compact(
            'totalBarang',
            'totalPeminjaman',
            'dipinjam',
            'dikembalikan'
        ));
    }
}