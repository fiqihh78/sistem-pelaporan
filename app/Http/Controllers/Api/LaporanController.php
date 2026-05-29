<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
    // Daftar laporan milik user yang login
    public function index(Request $request)
    {
        $laporans = Laporan::with('kategori')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(function ($l) {
                return [
                    'id'          => $l->id,
                    'kode'        => $l->kode,
                    'judul'       => $l->judul,
                    'kategori'    => $l->kategori?->nama,
                    'lokasi'      => $l->lokasi,
                    'status'      => $l->status,
                    'prioritas'   => $l->prioritas,
                    'foto_sebelum' => $l->foto_sebelum
                        ? url('storage/' . $l->foto_sebelum)
                        : null,
                    'created_at'  => $l->created_at->format('d M Y'),
                ];
            });

        return response()->json([
            'success'  => true,
            'laporans' => $laporans,
        ]);
    }

    // Detail satu laporan
    public function show(Request $request, $id)
    {
        $laporan = Laporan::with(['kategori', 'penugasan.petugas'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'laporan' => [
                'id'           => $laporan->id,
                'kode'         => $laporan->kode,
                'judul'        => $laporan->judul,
                'deskripsi'    => $laporan->deskripsi,
                'kategori'     => $laporan->kategori?->nama,
                'lokasi'       => $laporan->lokasi,
                'status'       => $laporan->status,
                'prioritas'    => $laporan->prioritas,
                'terverifikasi' => $laporan->terverifikasi,
                'foto_sebelum' => $laporan->foto_sebelum
                    ? url('storage/' . $laporan->foto_sebelum)
                    : null,
                'foto_sesudah' => $laporan->foto_sesudah
                    ? url('storage/' . $laporan->foto_sesudah)
                    : null,
                'petugas'      => $laporan->penugasan?->petugas?->nama,
                'created_at'   => $laporan->created_at->format('d M Y H:i'),
            ],
        ]);
    }

    // Kirim laporan baru dari Flutter
    public function store(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'deskripsi'   => 'required|string',
            'lokasi'      => 'required|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'foto_sebelum' => 'nullable|image|max:5120', // max 5MB
        ]);

        $user = $request->user();

        // Generate kode unik
        $kode = '#REP-' . strtoupper(Str::random(6));

        // Simpan foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto_sebelum')) {
            $fotoPath = $request->file('foto_sebelum')
                ->store('laporan/foto', 'public');
        }

        $laporan = Laporan::create([
            'user_id'     => $user->id,
            'kode'        => $kode,
            'pelapor'     => $user->name,
            'foto_pelapor' => null,
            'kategori_id' => $request->kategori_id,
            'judul'       => $request->judul,
            'deskripsi'   => $request->deskripsi,
            'lokasi'      => $request->lokasi,
            'foto_sebelum' => $fotoPath,
            'status'      => 'pending',
            'prioritas'   => 'medium',
            'terverifikasi' => false,
        ]);

        // Buat notifikasi untuk admin
        Notifikasi::create([
            'judul' => 'Laporan Baru Masuk',
            'pesan' => "Laporan \"{$laporan->judul}\" dikirim oleh {$user->name}.",
            'tipe'  => 'laporan_baru',
            'link'  => "/laporan/{$laporan->id}",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim',
            'laporan' => [
                'id'   => $laporan->id,
                'kode' => $laporan->kode,
            ],
        ], 201);
    }
}
