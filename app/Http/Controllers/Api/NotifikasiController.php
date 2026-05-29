<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        // Tampilkan notifikasi yang relevan untuk user ini
        // (laporan_baru ditampilkan ke admin, status_berubah ke user)
        $notifikasis = Notifikasi::latest()->take(20)->get()->map(function ($n) {
            return [
                'id'          => $n->id,
                'judul'       => $n->judul,
                'pesan'       => $n->pesan,
                'tipe'        => $n->tipe,
                'sudah_dibaca' => $n->sudah_dibaca,
                'created_at'  => $n->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'success'     => true,
            'notifikasis' => $notifikasis,
        ]);
    }
}
