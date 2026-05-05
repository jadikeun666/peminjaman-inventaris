<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display user profile
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email',
            'npm'      => 'required',
            'no_hp'    => 'required',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'npm'   => $request->npm,
            'no_hp' => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('profile.index');
    }
}