<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PengaturanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pengaturan.index', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100', 'email' => 'required|email']);
        Auth::user()->update($request->only(['name', 'email']));
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function ubahPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
            'current_password' => 'required',
        ]);
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
        }
        Auth::user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Kata sandi berhasil diubah.');
    }
}
