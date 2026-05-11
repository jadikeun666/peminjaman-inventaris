<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function qrisForm()
    {
        return view('admin.qris', [
            'qrisImage'    => Setting::get('qris_image'),
            'namaMerchant' => Setting::get('nama_merchant'),
            'infoRekening' => Setting::get('info_rekening'),
        ]);
    }

    public function qrisUpdate(Request $request)
    {
        $request->validate([
            'qris_image'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama_merchant' => 'required|string|max:100',
            'info_rekening' => 'required|string|max:100',
        ]);

        if ($request->hasFile('qris_image')) {
            $path = $request->file('qris_image')->store('qris', 'public');
            Setting::set('qris_image', $path);
        }

        Setting::set('nama_merchant', $request->nama_merchant);
        Setting::set('info_rekening', $request->info_rekening);

        return back()->with('success', 'QR Code berhasil disimpan!');
    }
}