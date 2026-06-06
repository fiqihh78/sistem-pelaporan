<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanApiController extends Controller
{
    public function index(Request $request)
    {
        $laporans = Laporan::with('kategori')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($l) => $this->format($l));

        return response()->json(['success' => true, 'laporans' => $laporans]);
    }

    public function semuaLaporan()
    {
        $laporans = Laporan::with(['kategori', 'user'])
            ->latest()
            ->get()
            ->map(fn($l) => $this->format($l, withUser: true));

        return response()->json(['success' => true, 'laporans' => $laporans]);
    }

    public function store(Request $request)
{
    $request->validate([
        'kategori_id'  => 'required|exists:kategoris,id',
        'judul'        => 'required|string|max:255',
        'deskripsi'    => 'required|string',
        'lokasi'       => 'required|string',
        'foto_sebelum' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    ]);

    $user = $request->user();
    $kode = 'REP-' . strtoupper(Str::random(6));

    // Upload ke Cloudinary via HTTP (tanpa package tambahan)
    $fotoUrl = null;
    if ($request->hasFile('foto_sebelum')) {
        $file      = $request->file('foto_sebelum');
        $cloudName = env('CLOUDINARY_CLOUD_NAME', 'drmuuupkd');
        $apiKey    = env('CLOUDINARY_API_KEY', '536198542151349');
        $apiSecret = env('CLOUDINARY_API_SECRET', 'j83vDe-VN7cp2gWE_ahN3C4Elec');
        $timestamp = time();
        $signature = sha1("folder=silapor/laporan&timestamp={$timestamp}{$apiSecret}");

        $response = \Illuminate\Support\Facades\Http::attach(
            'file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", [
            'api_key'   => $apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
            'folder'    => 'silapor/laporan',
        ]);

        if ($response->successful()) {
            $fotoUrl = $response->json('secure_url');
        }
    }

    $laporan = Laporan::create([
        'kode'          => $kode,
        'user_id'       => $user->id,
        'pelapor'       => $user->name,
        'kategori_id'   => $request->kategori_id,
        'judul'         => $request->judul,
        'deskripsi'     => $request->deskripsi,
        'lokasi'        => $request->lokasi,
        'foto_sebelum'  => $fotoUrl,
        'status'        => 'pending',
        'prioritas'     => 'medium',
        'terverifikasi' => false,
    ]);

    Notifikasi::create([
        'user_id' => $user->id,
        'judul'   => 'Laporan Terkirim ✅',
        'pesan'   => "Laporan \"{$laporan->judul}\" ({$kode}) berhasil dikirim.",
        'tipe'    => 'laporan_baru',
        'dibaca'  => false,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Laporan berhasil dikirim.',
        'laporan' => $this->format($laporan->load('kategori')),
    ], 201);
}

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

    private function format(
        Laporan $l,
        bool $withUser = false,
        bool $withPenugasan = false
    ): array {
        $data = [
            'id'            => $l->id,
            'kode'          => $l->kode ?? 'REP-' . $l->id,
            'pelapor'       => $l->pelapor,
            'kategori_id'   => $l->kategori_id,
            'kategori'      => $l->kategori?->nama ?? '',
            'judul'         => $l->judul,
            'deskripsi'     => $l->deskripsi,
            'lokasi'        => $l->lokasi,
            // URL Cloudinary langsung, tidak perlu prefix storage/
            'foto_sebelum'  => $l->foto_sebelum,
            'foto_sesudah'  => $l->foto_sesudah,
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
