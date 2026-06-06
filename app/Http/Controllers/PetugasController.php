<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $query = Petugas::query();
        if ($request->search) $query->where('nama', 'like', "%{$request->search}%");
        if ($request->status) $query->where('status', $request->status);
        if ($request->spesialisasi) $query->where('spesialisasi', $request->spesialisasi);

        $total    = Petugas::count();
        $aktif    = Petugas::where('status', 'aktif')->count();
        $tersedia = Petugas::where('status', 'nonaktif')->count();
        $petugas  = $query->paginate(10);

        return view('petugas.index', compact('petugas', 'total', 'aktif', 'tersedia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:255',
            'email'        => 'required|email|unique:petugas,email',
            'spesialisasi' => 'required|string',
            'status'       => 'nullable|in:aktif,nonaktif',
        ]);

        // Generate id_petugas unik
        $lastId     = Petugas::max('id') ?? 0;
        $id_petugas = 'PTG-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        Petugas::create([
            'user_id'     => auth()->id(),
            'id_petugas'  => $id_petugas,
            'nama'        => $request->nama,
            'email'       => $request->email,
            'spesialisasi'=> $request->spesialisasi,
            'status'      => $request->status ?? 'aktif',
            'beban_kerja' => 0,
        ]);

        return back()->with('success', "Petugas {$request->nama} ({$id_petugas}) berhasil ditambahkan.");
    }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $nama    = $petugas->nama;
        $petugas->delete();

        return back()->with('success', "Data petugas {$nama} berhasil dihapus.");
    }
}
