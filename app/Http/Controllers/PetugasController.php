<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Petugas::query();
        if ($request->search) $query->where('nama', 'like', "%{$request->search}%");
        if ($request->status) $query->where('status', $request->status);
        if ($request->spesialisasi) $query->where('spesialisasi', $request->spesialisasi);
        $total    = Petugas::count();
        $aktif    = Petugas::where('status', 'bertugas')->count();
        $tersedia = Petugas::where('status', 'tersedia')->count();
        $petugas  = $query->paginate(10);
        return view('petugas.index', compact('petugas', 'total', 'aktif', 'tersedia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:100',
            'email'        => 'required|email|unique:petugas',
            'spesialisasi' => 'required|string',
        ]);
        $kode = '#PTG-' . str_pad(Petugas::count() + 1, 5, '0', STR_PAD_LEFT);
        Petugas::create(array_merge($request->only(['nama', 'email', 'spesialisasi', 'status']), ['kode' => $kode]));
        return back()->with('success', 'Petugas baru berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Petugas::findOrFail($id)->delete();
        return back()->with('success', 'Data petugas dihapus.');
    }
}
