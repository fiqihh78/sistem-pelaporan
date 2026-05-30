<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', auth()->id())
                        ->latest()
                        ->paginate(10);
        return view('notifikasi.index', compact('notifikasis'));
    }

    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        $notifikasi->update(['dibaca' => true]);
        return redirect()->back();
    }

    public function markAllAsRead()
    {
        Notifikasi::where('user_id', auth()->id())
                ->update(['dibaca' => true]);
        return redirect()->back()->with('success', 'Semua notifikasi telah dibaca!');
    }
}