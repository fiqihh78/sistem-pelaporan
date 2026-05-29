<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $total    = Kategori::count();
        $aktif    = Kategori::where('status', 'aktif')->count();
        $kategoris = Kategori::withCount('laporans')->paginate(10);
        return view('kategori.index', compact('total', 'aktif', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:100']);
        Kategori::create($request->only(['nama', 'ikon', 'deskripsi', 'status']));
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        Kategori::findOrFail($id)->update($request->only(['nama', 'ikon', 'deskripsi', 'status']));
        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}
