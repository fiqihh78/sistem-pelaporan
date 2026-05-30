<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Kategori;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with(['user', 'kategori', 'petugas'])->latest();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $laporans = $query->paginate(10);

        return view('laporan.index', compact('laporans'));
    }

    public function show($id)
    {
        $laporan = Laporan::with(['user', 'kategori', 'petugas'])
                        ->findOrFail($id);
        return view('laporan.show', compact('laporan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'deskripsi'   => 'required|string',
            'lokasi'      => 'required|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'foto_bukti'  => 'nullable|image|max:2048',
        ]);

        $foto = null;
        if ($request->hasFile('foto_bukti')) {
            $foto = $request->file('foto_bukti')->store('bukti', 'public');
        }

        Laporan::create([
            'nomor_laporan' => 'REP-' . str_pad(Laporan::count() + 1, 4, '0', STR_PAD_LEFT),
            'user_id'       => auth()->id(),
            'kategori_id'   => $request->kategori_id,
            'judul'         => $request->judul,
            'deskripsi'     => $request->deskripsi,
            'lokasi'        => $request->lokasi,
            'foto_bukti'    => $foto,
            'status'        => 'pending',
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dikirim!');
    }

    public function updateStatus(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'status'        => $request->status,
            'petugas_id'    => $request->petugas_id,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->back()->with('success', 'Status laporan diperbarui!');
    }

    public function exportPdf()
    {
        $laporans = Laporan::with(['user', 'kategori', 'petugas'])
                        ->latest()
                        ->get();

        $pdf = Pdf::loadView('laporan.pdf', compact('laporans'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-' . date('d-m-Y') . '.pdf');
    }
}