<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::where('status', 'aktif')->get(['id', 'nama', 'ikon']);

        return response()->json([
            'success'  => true,
            'kategoris' => $kategoris,
        ]);
    }
}
