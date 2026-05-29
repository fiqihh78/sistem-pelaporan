@extends('layouts.app') @section('title', 'Penugasan Laporan') @section('content')
<h4 class="fw-bold mb-4">Penugasan Laporan</h4>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Menunggu Penugasan</div>
            <div class="value">{{ $menunggu }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Petugas Tersedia</div>
            <div class="value text-success">{{ $tersedia }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Tugas Dalam Proses</div>
            <div class="value text-warning">{{ $dalamProses }}</div>
        </div>
    </div>
</div>

<div class="stat-card">
    <h6 class="fw-semibold mb-1">Daftar Penugasan</h6>
    <p class="text-muted mb-3" style="font-size:0.83rem;">Kelola dan tentukan petugas untuk laporan masyarakat</p>
    <table class="table table-custom mb-3">
        <thead>
            <tr>
                <th>ID LAPORAN</th>
                <th>JUDUL LAPORAN</th>
                <th>PRIORITAS</th>
                <th>LOKASI</th>
                <th>PILIH PETUGAS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $l)
            <tr>
                <td><a href="{{ route('laporan.show', $l->id) }}" class="kode-link">{{ $l->kode }}</a></td>
                <td>
                    <div class="fw-semibold" style="font-size:0.875rem;">{{ $l->judul }}</div>
                    <div class="text-muted" style="font-size:0.78rem;">Diterima {{ $l->created_at->diffForHumans() }}</div>
                </td>
                <td><span class="prioritas-{{ $l->prioritas }}">{{ strtoupper($l->prioritas) }}</span></td>
                <td style="font-size:0.83rem;">📍 {{ Str::limit($l->lokasi, 25) }}</td>
                <td>
                    <form method="POST" action="{{ route('penugasan.tugaskan', $l->id) }}" class="d-flex gap-1">
                        @csrf
                        <select name="petugas_id" class="form-select form-select-sm" style="min-width:160px;" required>
                            <option value="">Pilih Petugas...</option>
                            @foreach($semuaPetugas as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-primary">Tugaskan</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">Tidak ada laporan yang perlu ditugaskan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $laporans->links() }}
</div>
@endsection