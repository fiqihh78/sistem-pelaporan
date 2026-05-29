@extends('layouts.app')
@section('title', 'Laporan Masuk')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Laporan Masuk</h4>
</div>
<div class="stat-card">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari kode atau pelapor..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="diproses" {{ request('status')=='diproses'?'selected':'' }}>Diproses</option>
                <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-primary btn-sm w-100">Filter</button></div>
    </form>
    <table class="table table-custom mb-3">
        <thead>
            <tr>
                <th>ID LAPORAN</th>
                <th>PELAPOR</th>
                <th>KATEGORI</th>
                <th>TANGGAL</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $l)
            <tr>
                <td><a href="{{ route('laporan.show', $l->id) }}" class="kode-link">{{ $l->kode }}</a></td>
                <td>{{ $l->pelapor }}</td>
                <td>{{ $l->kategori->nama ?? '-' }}</td>
                <td>{{ $l->created_at->format('d M Y, H:i') }}</td>
                <td><span class="badge-status badge-{{ $l->status }}">{{ strtoupper($l->status) }}</span></td>
                <td>
                    <a href="{{ route('laporan.show', $l->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">Tidak ada laporan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $laporans->links() }}
</div>
@endsection