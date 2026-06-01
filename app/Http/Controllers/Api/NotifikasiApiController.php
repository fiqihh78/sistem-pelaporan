<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiApiController extends Controller
{
    public function index(Request $request)
    {
        $notifikasis = Notifikasi::where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($n) => [
                'id'           => $n->id,
                'judul'        => $n->judul,
                'pesan'        => $n->pesan,
                'tipe'         => $n->tipe ?? 'info',
                'sudah_dibaca' => (bool) $n->sudah_dibaca,
                'created_at'   => $n->created_at?->toIso8601String(),
            ]);

        return response()->json(['success' => true, 'notifikasis' => $notifikasis]);
    }

    public function tandaiSemua(Request $request)
    {
        Notifikasi::where('user_id', $request->user()->id)
            ->where('sudah_dibaca', false)
            ->update(['sudah_dibaca' => true]);

        return response()->json(['success' => true, 'message' => 'Semua notifikasi dibaca.']);
    }

    public function tandaiSatu(Request $request, $id)
    {
        Notifikasi::where('user_id', $request->user()->id)
            ->findOrFail($id)
            ->update(['sudah_dibaca' => true]);

        return response()->json(['success' => true, 'message' => 'Notifikasi ditandai dibaca.']);
    }
}
