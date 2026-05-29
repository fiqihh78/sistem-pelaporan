<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Kategori;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Laporan::with('kategori')->latest();
        if ($request->search) $query->where('kode', 'like', "%{$request->search}%")->orWhere('pelapor', 'like', "%{$request->search}%");
        if ($request->status) $query->where('status', $request->status);
        $laporans = $query->paginate(10);
        return view('laporan.index', compact('laporans'));
    }

    public function show($id)
    {
        $laporan = Laporan::with(['kategori', 'penugasan.petugas'])->findOrFail($id);
        return view('laporan.show', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update($request->only(['status', 'foto_sesudah']));
        return back()->with('success', 'Laporan berhasil diperbarui.');
    }
}
