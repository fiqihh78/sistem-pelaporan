<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Petugas;
use App\Models\Kategori;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Grafik 7 hari terakhir
        $labels = [];
        $grafik_mingguan = [];
        $grafik_selesai = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('D d/m');
            $grafik_mingguan[] = Laporan::whereDate('created_at', $date)->count();
            $grafik_selesai[] = Laporan::whereDate('updated_at', $date)
                                    ->where('status', 'selesai')->count();
        }

        $data = [
            'total_laporan'        => Laporan::count(),
            'pending'              => Laporan::where('status', 'pending')->count(),
            'diproses'             => Laporan::where('status', 'diproses')->count(),
            'selesai'              => Laporan::where('status', 'selesai')->count(),
            'total_petugas'        => Petugas::count(),
            'total_kategori'       => Kategori::count(),
            'laporan_terbaru'      => Laporan::with(['user', 'kategori', 'petugas'])
                                        ->latest()->take(5)->get(),
            'laporan_per_kategori' => Kategori::withCount('laporans')->get(),
            'grafik_mingguan'      => $grafik_mingguan,
            'grafik_selesai'       => $grafik_selesai,
            'grafik_labels'        => $labels,
        ];

        return view('dashboard.index', $data);
    }
}