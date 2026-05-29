<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Petugas;
use App\Models\Penugasan;
use Illuminate\Http\Request;

class PenugasanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menunggu      = Laporan::where('status', 'pending')->where('terverifikasi', true)->count();
        $tersedia      = Petugas::where('status', 'tersedia')->count();
        $dalamProses   = Penugasan::where('status', 'dalam_proses')->count();
        $laporans      = Laporan::with('kategori')->where('status', 'pending')->where('terverifikasi', true)->paginate(10);
        $semuaPetugas  = Petugas::where('status', 'tersedia')->get();
        return view('penugasan.index', compact('menunggu', 'tersedia', 'dalamProses', 'laporans', 'semuaPetugas'));
    }

    public function tugaskan(Request $request, $id)
    {
        $request->validate(['petugas_id' => 'required|exists:petugas,id']);
        $laporan = Laporan::findOrFail($id);
        Penugasan::updateOrCreate(
            ['laporan_id' => $laporan->id],
            ['petugas_id' => $request->petugas_id, 'ditugaskan_pada' => now(), 'status' => 'dalam_proses']
        );
        $laporan->update(['status' => 'diproses']);
        Petugas::find($request->petugas_id)->increment('beban_kerja');
        return back()->with('success', 'Petugas berhasil ditugaskan.');
    }
}
