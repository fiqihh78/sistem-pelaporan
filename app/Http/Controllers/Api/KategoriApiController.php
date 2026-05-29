<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;

class KategoriApiController extends Controller
{
    /**
     * Daftar kategori aktif (untuk dropdown di Flutter).
     */
    public function index()
    {
        $kategoris = Kategori::where('status', 'aktif')
            ->orderBy('nama')
            ->get(['id', 'nama', 'ikon', 'deskripsi']);

        return response()->json([
            'success'  => true,
            'kategoris' => $kategoris,
        ]);
    }
}
