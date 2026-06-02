<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Petugas;
use App\Models\Kategori;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLaporan  = Laporan::count();
        $pending       = Laporan::where('status', 'pending')->count();
        $diproses      = Laporan::where('status', 'diproses')->count();
        $selesai       = Laporan::where('status', 'selesai')->count();
        $ditolak       = Laporan::where('status', 'ditolak')->count();

        $laporanTerbaru = Laporan::with(['kategori', 'penugasan', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $perKategori = Kategori::withCount('laporans')->get()->map(fn($k) => [
            'nama'   => $k->nama,
            'jumlah' => $k->laporans_count,
            'persen' => $totalLaporan > 0
                ? round(($k->laporans_count / $totalLaporan) * 100)
                : 0,
        ]);

        $tren = Laporan::selectRaw('DAYOFWEEK(created_at) as hari, COUNT(*) as total')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('hari')
            ->pluck('total', 'hari');

        return view('dashboard', compact(
            'totalLaporan',
            'pending',
            'diproses',
            'selesai',
            'ditolak',
            'laporanTerbaru',
            'perKategori',
            'tren'
        ));
    }
}
