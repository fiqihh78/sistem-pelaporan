<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        $bulanNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        $laporanPerBulan = Laporan::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        $bulananLabels = $laporanPerBulan->map(function($l) use ($bulanNames) {
            return $bulanNames[$l->bulan - 1] . ' ' . $l->tahun;
        })->values()->toArray();

        $bulananData = $laporanPerBulan->pluck('total')->values()->toArray();

        $laporanPerKategori = Kategori::withCount('laporans')->get();

        $kategoriLabels = $laporanPerKategori->pluck('nama')->toArray();
        $kategoriData   = $laporanPerKategori->pluck('laporans_count')->toArray();

        $statusData = [
            'pending'  => Laporan::where('status', 'pending')->count(),
            'diproses' => Laporan::where('status', 'diproses')->count(),
            'selesai'  => Laporan::where('status', 'selesai')->count(),
            'ditolak'  => Laporan::where('status', 'ditolak')->count(),
        ];

        return view('statistik.index', compact(
            'statusData',
            'bulananLabels',
            'bulananData',
            'kategoriLabels',
            'kategoriData'
        ));
    }
}