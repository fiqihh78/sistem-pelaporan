<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanApiController extends Controller
{
    // Laporan milik user yang login
    public function index(Request $request)
    {
        $laporans = Laporan::with('kategori')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($l) => $this->format($l));

        return response()->json(['success' => true, 'laporans' => $laporans]);
    }

    // Semua laporan dari semua warga (halaman publik Flutter)
    public function semuaLaporan()
    {
        $laporans = Laporan::with(['kategori', 'user'])
            ->latest()
            ->get()
            ->map(fn($l) => $this->format($l, withUser: true));

        return response()->json(['success' => true, 'laporans' => $laporans]);
    }

    // Kirim laporan baru dari Flutter
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id'  => 'required|exists:kategoris,id',
            'judul'        => 'required|string|max:255',
            'deskripsi'    => 'required|string',
            'lokasi'       => 'required|string',
            'foto_sebelum' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $user     = $request->user();
        $kode     = 'REP-' . strtoupper(Str::random(6));
        $fotoPath = null;

        if ($request->hasFile('foto_sebelum')) {
            $fotoPath = $request->file('foto_sebelum')
                ->store('laporan/foto', 'public');
        }

        $laporan = Laporan::create([
            'kode'         => $kode,
            'user_id'      => $user->id,
            'pelapor'      => $user->name,
            'kategori_id'  => $request->kategori_id,
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'lokasi'       => $request->lokasi,
            'foto_sebelum' => $fotoPath,
            'status'       => 'pending',
            'prioritas'    => 'medium',
            'terverifikasi'=> false,
        ]);

        // Buat notifikasi untuk user
        Notifikasi::create([
            'user_id'      => $user->id,
            'judul'        => 'Laporan Terkirim',
            'pesan'        => "Laporan \"{$laporan->judul}\" berhasil dikirim dan menunggu verifikasi.",
            'tipe'         => 'laporan_baru',
            'sudah_dibaca' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim.',
            'laporan' => $this->format($laporan->load('kategori')),
        ], 201);
    }

    // Detail satu laporan
    public function show(Request $request, $id)
    {
        $laporan = Laporan::with(['kategori', 'penugasan'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'laporan' => $this->format($laporan, withPenugasan: true),
        ]);
    }

    // ── Helper format response ─────────────────────────────────────────
    private function format(Laporan $l, bool $withUser = false, bool $withPenugasan = false): array
    {
        $data = [
            'id'            => $l->id,
            'kode'          => $l->kode,
            'pelapor'       => $l->pelapor,
            'kategori_id'   => $l->kategori_id,
            'kategori'      => $l->kategori?->nama ?? '',
            'judul'         => $l->judul,
            'deskripsi'     => $l->deskripsi,
            'lokasi'        => $l->lokasi,
            'foto_sebelum'  => $l->foto_sebelum ? asset('storage/' . $l->foto_sebelum) : null,
            'foto_sesudah'  => $l->foto_sesudah ? asset('storage/' . $l->foto_sesudah) : null,
            'status'        => $l->status,
            'prioritas'     => $l->prioritas,
            'terverifikasi' => (bool) $l->terverifikasi,
            'created_at'    => $l->created_at?->toIso8601String(),
            'updated_at'    => $l->updated_at?->toIso8601String(),
        ];

        if ($withUser) {
            $data['nama_warga'] = $l->user?->name ?? $l->pelapor;
        }

        if ($withPenugasan && $l->relationLoaded('penugasan') && $l->penugasan) {
            $data['penugasan'] = [
                'status'  => $l->penugasan->status,
                'catatan' => $l->penugasan->catatan,
            ];
        }

        return $data;
    }
}
