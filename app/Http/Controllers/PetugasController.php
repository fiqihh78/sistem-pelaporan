<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\User;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::with('user')->paginate(10);
        return view('petugas.index', compact('petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:6',
            'spesialisasi' => 'required|string',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'petugas',
        ]);

        Petugas::create([
            'user_id'      => $user->id,
            'id_petugas'   => 'PTG-' . str_pad(Petugas::count() + 1, 4, '0', STR_PAD_LEFT),
            'spesialisasi' => $request->spesialisasi,
        ]);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $petugas->user->delete();
        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus!');
    }
}