<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifikasis = Notifikasi::latest()->paginate(20);
        return view('notifikasi.index', compact('notifikasis'));
    }

    public function tandaiSemua()
    {
        Notifikasi::where('sudah_dibaca', false)->update(['sudah_dibaca' => true]);
        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
