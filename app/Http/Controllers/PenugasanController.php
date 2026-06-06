<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Petugas;
use App\Models\Penugasan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class PenugasanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menunggu    = Laporan::where('status', 'pending')->where('terverifikasi', true)->count();
        $tersedia    = Petugas::where('status', 'tersedia')->count();
        $dalamProses = Penugasan::where('status', 'dalam_proses')->count();
        $laporans    = Laporan::with('kategori')
            ->where('status', 'pending')
            ->where('terverifikasi', true)
            ->paginate(10);
        $semuaPetugas = Petugas::where('status', 'tersedia')->get();
        return view('penugasan.index', compact('menunggu', 'tersedia', 'dalamProses', 'laporans', 'semuaPetugas'));
    }

    public function tugaskan(Request $request, $id)
    {
        $request->validate(['petugas_id' => 'required|exists:petugas,id']);

        $laporan = Laporan::findOrFail($id);
        $petugas = Petugas::findOrFail($request->petugas_id);

        Penugasan::updateOrCreate(
            ['laporan_id' => $laporan->id],
            [
                'petugas_id'      => $petugas->id,
                'ditugaskan_pada' => now(),
                'status'          => 'dalam_proses',
            ]
        );

        $laporan->update(['status' => 'diproses']);
        $petugas->increment('beban_kerja');

        // ── Notifikasi: penugasan baru ──
        Notifikasi::create([
            'judul' => 'Petugas Ditugaskan',
            'pesan' => "Laporan \"{$laporan->judul}\" ({$laporan->kode ?? '#REP-'.$laporan->id}) ditugaskan ke {$petugas->nama}.",
            'tipe'  => 'penugasan',
            'link'  => "/laporan/{$laporan->id}",
        ]);

        return back()->with('success', "Petugas {$petugas->nama} berhasil ditugaskan untuk laporan ini.");
    }
}
