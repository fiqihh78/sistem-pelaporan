<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menunggu    = Laporan::where('terverifikasi', false)->count();
        $terverifikasi = Laporan::where('terverifikasi', true)->whereDate('updated_at', today())->count();
        $belumDiproses = Laporan::where('status', 'pending')->where('terverifikasi', true)->count();
        $laporans    = Laporan::with('kategori')->where('terverifikasi', false)->paginate(10);
        return view('verifikasi.index', compact('menunggu', 'terverifikasi', 'belumDiproses', 'laporans'));
    }

    public function verifikasi($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update(['terverifikasi' => true, 'status' => 'diproses']);
        return back()->with('success', 'Laporan #' . $laporan->kode . ' berhasil diverifikasi.');
    }

    public function tolak($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update(['status' => 'ditolak']);
        return back()->with('success', 'Laporan berhasil ditolak.');
    }
}
