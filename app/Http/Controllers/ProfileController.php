<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profile
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Update data profile user
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:30',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'npm'   => 'nullable|numeric',
            'no_hp' => 'nullable|numeric',
        ]);

        $user = Auth::user();

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'npm'   => $request->npm,
            'no_hp' => $request->no_hp,
        ]);

        return back()->with(
            'success',
            'Profil berhasil diperbarui!'
        );
    }

    /**
     * Update password user
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password'      => 'required|min:6|confirmed',
        ]);

        // cek password lama
        if (!Hash::check(
            $request->password_lama,
            Auth::user()->password
        )) {

            return back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai.'
            ]);
        }

        // update password baru
        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with(
            'success',
            'Password berhasil diubah!'
        );
    }
}