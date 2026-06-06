<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PetugasController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Petugas::query();
        if ($request->search) $query->where('nama', 'like', "%{$request->search}%");
        if ($request->status) $query->where('status', $request->status);
        if ($request->spesialisasi) $query->where('spesialisasi', $request->spesialisasi);
        $total    = Petugas::count();
        $aktif    = Petugas::where('status', 'bertugas')->count();
        $tersedia = Petugas::where('status', 'tersedia')->count();
        $petugas  = $query->paginate(10);
        return view('petugas.index', compact('petugas', 'total', 'aktif', 'tersedia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:100',
            'email'        => 'required|email|unique:petugas,email',
            'spesialisasi' => 'required|string',
            'status'       => 'nullable|in:tersedia,bertugas,istirahat',
        ]);

        // Kode unik: #PTG- + 5 karakter random huruf besar
        do {
            $kode = '#PTG-' . strtoupper(Str::random(5));
        } while (Petugas::where('id_petugas', $kode)->exists());

        $petugas = Petugas::create([
            'kode'         => $kode,
            'nama'         => $request->nama,
            'email'        => $request->email,
            'spesialisasi' => $request->spesialisasi,
            'status'       => $request->status ?? 'tersedia',
            'beban_kerja'  => 0,
        ]);

        // Buat notifikasi admin
        Notifikasi::create([
            'judul' => 'Petugas Baru Ditambahkan',
            'pesan' => "Petugas \"{$petugas->nama}\" ({$kode}) berhasil ditambahkan ke sistem.",
            'tipe'  => 'sistem',
            'link'  => '/petugas',
        ]);

        return back()->with('success', "Petugas {$petugas->nama} ({$kode}) berhasil ditambahkan.");
    }

    public function destroy($id)
    {
        $petugas = Petugas::findOrFail($id);
        $nama    = $petugas->nama;
        $petugas->delete();

        Notifikasi::create([
            'judul' => 'Petugas Dihapus',
            'pesan' => "Data petugas \"{$nama}\" telah dihapus dari sistem.",
            'tipe'  => 'sistem',
            'link'  => '/petugas',
        ]);

        return back()->with('success', "Data petugas {$nama} berhasil dihapus.");
    }
}
