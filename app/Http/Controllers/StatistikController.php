<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Kategori;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $total       = Laporan::count();
        $selesai     = Laporan::where('status', 'selesai')->count();
        $tren        = Laporan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as masuk, SUM(status="selesai") as selesai')
            ->groupBy('bulan')->orderBy('bulan')->get();
        $distribusi  = Kategori::withCount('laporans')->get();
        return view('statistik.index', compact('total', 'selesai', 'tren', 'distribusi'));
    }
}
