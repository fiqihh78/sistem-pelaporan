<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Petugas;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index()
    {
        $laporans = Laporan::with(['user', 'kategori'])
                        ->where('status', 'pending')
                        ->latest()
                        ->paginate(10);

        $total_pending    = Laporan::where('status', 'pending')->count();
        $terverifikasi    = Laporan::where('status', '!=', 'pending')->count();
        $ditolak          = Laporan::where('status', 'ditolak')->count();

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
        $laporan->update([
            'status'     => 'diproses',
            'petugas_id' => $request->petugas_id,
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil diverifikasi!');
    }

    public function reject($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update(['status' => 'ditolak']);

        return redirect()->back()->with('success', 'Laporan ditolak!');
    }
}
