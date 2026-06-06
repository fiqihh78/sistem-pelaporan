<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menunggu      = Laporan::where('terverifikasi', false)->where('status', '!=', 'ditolak')->count();
        $terverifikasi = Laporan::where('terverifikasi', true)->whereDate('updated_at', today())->count();
        $belumDiproses = Laporan::where('status', 'pending')->where('terverifikasi', true)->count();
        $laporans      = Laporan::with('kategori')
            ->where('terverifikasi', false)
            ->where('status', '!=', 'ditolak')
            ->latest()
            ->paginate(10);
        return view('verifikasi.index', compact('menunggu', 'terverifikasi', 'belumDiproses', 'laporans'));
    }

    public function verifikasi($id)
    {
        $laporan = Laporan::findOrFail($id);
        $kode    = $laporan->kode ?? '#REP-' . $laporan->id;
        $laporan->update(['terverifikasi' => true]);

        // Notifikasi: laporan terverifikasi → siap ditugaskan
        Notifikasi::create([
            'judul' => 'Laporan Diverifikasi ✅',
            'pesan' => "Laporan \"{$laporan->judul}\" ({$kode}) telah diverifikasi dan siap untuk ditugaskan ke petugas.",
            'tipe'  => 'status_berubah',
            'link'  => "/laporan/{$laporan->id}",
        ]);

        return back()->with('success', "Laporan {$kode} berhasil diverifikasi.");
    }

    public function tolak($id)
    {
        $laporan = Laporan::findOrFail($id);
        $kode    = $laporan->kode ?? '#REP-' . $laporan->id;
        $laporan->update(['status' => 'ditolak']);

        // Notifikasi: laporan ditolak
        Notifikasi::create([
            'judul' => 'Laporan Ditolak ❌',
            'pesan' => "Laporan \"{$laporan->judul}\" ({$kode}) telah ditolak oleh admin.",
            'tipe'  => 'status_berubah',
            'link'  => "/laporan/{$laporan->id}",
        ]);

        return back()->with('success', "Laporan {$kode} berhasil ditolak.");
    }
}
