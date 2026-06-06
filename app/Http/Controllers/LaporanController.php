<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Kategori;
use App\Models\Notifikasi;
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

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode', 'like', "%{$request->search}%")
                  ->orWhere('pelapor', 'like', "%{$request->search}%")
                  ->orWhere('judul', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

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
        $laporan    = Laporan::findOrFail($id);
        $statusLama = $laporan->status;
        $kode       = $laporan->kode ?? '#REP-' . $laporan->id;

        $data = $request->only(['status', 'foto_sesudah']);
        $laporan->update($data);

        // Notifikasi jika status berubah
        if (isset($data['status']) && $data['status'] !== $statusLama) {
            $labelStatus = match($data['status']) {
                'diproses' => 'sedang diproses',
                'selesai'  => 'telah selesai',
                'ditolak'  => 'ditolak',
                default    => $data['status'],
            };

            Notifikasi::create([
                'judul' => 'Status Laporan Diperbarui',
                'pesan' => "Laporan \"{$laporan->judul}\" ({$kode}) {$labelStatus}.",
                'tipe'  => 'status_berubah',
                'link'  => "/laporan/{$laporan->id}",
            ]);
        }

        return back()->with('success', 'Laporan berhasil diperbarui.');
    }
}
