<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;

class LogbookController extends Controller
{
    /**
     * Display logbook (riwayat peminjaman)
     */
    public function index()
    {
        $logbook = Peminjaman::with('user', 'details.barang')
                    ->latest()
                    ->get();

        return view('logbook.index', compact('logbook'));
    }
}