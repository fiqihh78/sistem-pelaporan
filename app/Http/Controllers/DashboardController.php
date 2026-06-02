<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Petugas;
use App\Models\Kategori;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalLaporan  = Laporan::count();
        $pending       = Laporan::where('status', 'pending')->count();
        $diproses      = Laporan::where('status', 'diproses')->count();
        $selesai       = Laporan::where('status', 'selesai')->count();

        // Gunakan 'penugasan' bukan 'petugas' (sesuai relasi di Model Laporan)
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

        // Tren mingguan
        $tren = Laporan::selectRaw('DAYOFWEEK(created_at) as hari, COUNT(*) as total')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('hari')
            ->pluck('total', 'hari');

        return view('dashboard', compact(
            'totalLaporan',
            'pending',
            'diproses',
            'selesai',
            'laporanTerbaru',
            'perKategori',
            'tren'
        ));
    }
}
