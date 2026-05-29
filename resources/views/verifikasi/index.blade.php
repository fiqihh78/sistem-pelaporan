@extends('layouts.app') @section('title', 'Verifikasi Laporan') @section('content')
<h4 class="fw-bold mb-4">Verifikasi Laporan</h4>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card border-start border-primary border-3">
            <div class="label">Total Menunggu Verifikasi</div>
            <div class="value text-primary">{{ number_format($menunggu) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card border-start border-success border-3">
            <div class="label">Terverifikasi Hari Ini</div>
            <div class="value text-success">{{ $terverifikasi }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card border-start border-danger border-3">
            <div class="label">Laporan Belum Diproses</div>
            <div class="value text-danger">{{ $belumDiproses }}</div>
        </div>
    </div>
</div>

<div class="stat-card">
    <h6 class="fw-semibold mb-1">Daftar Antrean Verifikasi</h6>
    <p class="text-muted mb-3" style="font-size:0.83rem;">Mengelola validasi data laporan publik yang masuk</p>
    <table class="table table-custom mb-3">
        <thead>
            <tr>
                <th>LAPORAN</th>
                <th>TANGGAL MASUK</th>
                <th>PELAPOR</th>
                <th>KATEGORI</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $l)
            <tr>
                <td><a href="{{ route('laporan.show', $l->id) }}" class="kode-link">{{ $l->kode }}</a></td>
                <td>{{ $l->created_at->format('d M Y, H:i') }}</td>
                <td>{{ $l->pelapor }}</td>
                <td><span class="badge bg-light text-dark border" style="font-size:0.75rem;">{{ strtoupper($l->kategori->nama ?? '-') }}</span></td>
                <td><span class="badge-status badge-pending">MENUNGGU</span></td>
                <td class="d-flex gap-1">
                    <form method="POST" action="{{ route('verifikasi.verifikasi', $l->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-primary">Verifikasi</button>
                    </form>
                    <form method="POST" action="{{ route('verifikasi.tolak', $l->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Tolak</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">Semua laporan sudah diverifikasi. 🎉</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $laporans->links() }}
</div>
@endsection