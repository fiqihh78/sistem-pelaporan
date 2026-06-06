<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        // Admin lihat SEMUA notifikasi dari semua user
        $notifikasis = Notifikasi::with('user')
            ->latest()
            ->paginate(20);

        $belumDibaca = Notifikasi::where('sudah_dibaca', false)->count();

        return view('notifikasi.index', compact('notifikasis', 'belumDibaca'));
    }

    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        $notifikasi->update(['sudah_dibaca' => true]);
        return redirect()->back();
    }

    public function markAllAsRead()
    {
        Notifikasi::update(['sudah_dibaca' => true]);
        return redirect()->back()->with('success', 'Semua notifikasi telah dibaca!');
    }
}
