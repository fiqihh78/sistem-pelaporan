<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Petugas;
use App\Models\Kategori;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');

        $laporans = collect();
        $petugas  = collect();
        $kategoris = collect();

        if ($query) {
            $laporans = Laporan::with(['user', 'kategori'])
                ->where('nomor_laporan', 'like', "%$query%")
                ->orWhere('judul', 'like', "%$query%")
                ->orWhere('lokasi', 'like', "%$query%")
                ->latest()
                ->get();

            $petugas = Petugas::with('user')
                ->whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%$query%");
                })
                ->get();

            $kategoris = Kategori::where('nama', 'like', "%$query%")->get();
        }

        $total = $laporans->count() + $petugas->count() + $kategoris->count();

        return view('search.index', compact(
            'query',
            'laporans',
            'petugas',
            'kategoris',
            'total'
        ));
    }
}