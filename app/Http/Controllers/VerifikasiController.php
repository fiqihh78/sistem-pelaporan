<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Petugas;
use App\Models\Penugasan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index()
    {
        $laporans = Laporan::with(['user', 'kategori'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        $total_pending = Laporan::where('status', 'pending')->count();
        $terverifikasi = Laporan::where('status', '!=', 'pending')->count();
        $ditolak       = Laporan::where('status', 'ditolak')->count();

        return view('verifikasi.index', compact(
            'laporans',
            'total_pending',
            'terverifikasi',
            'ditolak'
        ));
    }

    public function approve(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        $laporan->update(['status' => 'diproses']);

        // Buat penugasan jika ada petugas dipilih
        if ($request->filled('petugas_id')) {
            Penugasan::updateOrCreate(
                ['laporan_id' => $laporan->id],
                [
                    'petugas_id'      => $request->petugas_id,
                    'status'          => 'aktif',
                    'ditugaskan_pada' => now(),
                    'catatan'         => $request->catatan ?? null,
                ]
            );
        }

        // Kirim notifikasi ke user Flutter
        if ($laporan->user_id) {
            Notifikasi::create([
                'user_id' => $laporan->user_id,
                'judul'   => 'Laporan Diverifikasi ✅',
                'pesan'   => "Laporan \"{$laporan->judul}\" telah diverifikasi dan sedang diproses.",
                'tipe'    => 'status_berubah',
                'sudah_dibaca' => false,
                'link'    => "/laporan/{$laporan->id}",
            ]);
        }

        return redirect()->back()->with('success', 'Laporan berhasil diverifikasi!');
    }

    public function reject(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        $laporan->update(['status' => 'ditolak']);

        // Kirim notifikasi ke user Flutter
        if ($laporan->user_id) {
            Notifikasi::create([
                'user_id' => $laporan->user_id,
                'judul'   => 'Laporan Ditolak ❌',
                'pesan'   => "Laporan \"{$laporan->judul}\" tidak memenuhi syarat dan telah ditolak.",
                'tipe'    => 'status_berubah',
                'sudah_dibaca' => false,
                'link'    => "/laporan/{$laporan->id}",
            ]);
        }

        return redirect()->back()->with('success', 'Laporan ditolak!');
    }
}
