@extends('layouts.app') @section('title', 'Detail Laporan') @section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size:0.85rem;">
        <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan Masuk</a></li>
        <li class="breadcrumb-item active">Detail Monitoring {{ $laporan->kode }}</li>
    </ol>
</nav>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Monitoring Laporan {{ $laporan->kode }} <span class="badge bg-info text-dark" style="font-size:0.7rem;">DATA REAL-TIME</span></h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">{{ $laporan->deskripsi }}</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer me-1"></i> Cetak Log</button>
        @if($laporan->status !== 'selesai')
        <form method="POST" action="{{ route('laporan.update', $laporan->id) }}">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="selesai" />
            <button class="btn btn-primary btn-sm"><i class="bi bi-check-circle me-1"></i> Selesaikan Laporan</button>
        </form>
        @endif
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="stat-card mb-3">
            <h6 class="fw-semibold mb-3"><i class="bi bi-list-check me-2 text-primary"></i>Monitoring Progress</h6>
            <div class="timeline">
                <div class="d-flex gap-3 mb-3">
                    <div class="text-success fs-5">✅</div>
                    <div>
                        <div class="fw-semibold" style="font-size:0.9rem;">Laporan Diterima</div>
                        <div class="text-muted" style="font-size:0.8rem;">{{ $laporan->created_at->format('d M Y, H:i') }} • Oleh Sistem</div>
                        <div class="mt-1 p-2 bg-light rounded" style="font-size:0.83rem;">Laporan awal dari warga ({{ $laporan->pelapor }}) berhasil diverifikasi oleh admin tingkat 1.</div>
                    </div>
                </div>
                @if($laporan->penugasan)
                <div class="d-flex gap-3 mb-3">
                    <div class="text-success fs-5">✅</div>
                    <div>
                        <div class="fw-semibold" style="font-size:0.9rem;">Penugasan Petugas</div>
                        <div class="text-muted" style="font-size:0.8rem;">{{ $laporan->penugasan->ditugaskan_pada?->format('d M Y, H:i') }}</div>
                        <div class="mt-1 p-2 bg-light rounded" style="font-size:0.83rem;">Petugas lapangan <strong>{{ $laporan->penugasan->petugas->nama ?? '-' }}</strong> ditugaskan ke lokasi.</div>
                    </div>
                </div>
                @endif
                <div class="d-flex gap-3 mb-3">
                    <div class="text-primary fs-5">🔵</div>
                    <div>
                        <div class="fw-semibold" style="font-size:0.9rem;">Pengerjaan <span class="badge badge-diproses">SEDANG BERLANGSUNG</span></div>
                        <div class="text-muted" style="font-size:0.8rem;">Status saat ini</div>
                    </div>
                </div>
                <div class="d-flex gap-3 opacity-50">
                    <div class="fs-5">⭕</div>
                    <div>
                        <div class="fw-semibold" style="font-size:0.9rem;">Verifikasi Akhir & Selesai</div>
                        <div class="text-muted" style="font-size:0.8rem;">Menunggu pengerjaan selesai</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <h6 class="fw-semibold mb-3"><i class="bi bi-images me-2 text-primary"></i>Evidence Documentation</h6>
            <div class="row g-2">
                <div class="col-6">
                    <div class="border rounded p-2 text-center bg-light">
                        <div class="text-danger fw-bold mb-1" style="font-size:0.75rem;">BEFORE</div>
                        @if($laporan->foto_sebelum)
                        <img src="{{ asset('storage/'.$laporan->foto_sebelum) }}" class="img-fluid rounded" style="max-height:150px;" />
                        @else
                        <div class="text-muted py-4" style="font-size:0.85rem;">📷 Foto Kondisi Awal</div>
                        @endif
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-2 text-center bg-light">
                        <div class="text-success fw-bold mb-1" style="font-size:0.75rem;">AFTER (PREVIEW)</div>
                        @if($laporan->foto_sesudah)
                        <img src="{{ asset('storage/'.$laporan->foto_sesudah) }}" class="img-fluid rounded" style="max-height:150px;" />
                        @else
                        <div class="text-muted py-4" style="font-size:0.85rem;">⬆️ Unggah Foto Hasil</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card mb-3">
            <h6 class="fw-semibold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Field Officer Tracking</h6>
            @if($laporan->penugasan && $laporan->penugasan->petugas)
            <div class="d-flex gap-2 align-items-center mb-2">
                <div class="user-avatar">{{ substr($laporan->penugasan->petugas->nama, 0, 1) }}</div>
                <div>
                    <div class="fw-semibold" style="font-size:0.9rem;">{{ $laporan->penugasan->petugas->nama }}</div>
                    <div class="text-muted" style="font-size:0.78rem;">{{ $laporan->penugasan->petugas->kode }} • {{ $laporan->penugasan->petugas->spesialisasi }}</div>
                </div>
            </div>
            @else
            <p class="text-muted" style="font-size:0.85rem;">Belum ada petugas ditugaskan.</p>
            @endif
            <div class="bg-light rounded p-2 text-center text-muted mb-2" style="font-size:0.8rem; height:80px; display:flex;align-items:center;justify-content:center;"><i class="bi bi-map me-1"></i> {{ $laporan->lokasi }}</div>
        </div>
        <div class="stat-card">
            <h6 class="fw-semibold mb-2"><i class="bi bi-clock-history me-2"></i>Internal Activity Log</h6>
            <div style="font-size:0.8rem; color:#64748b;">
                <div class="d-flex gap-2 mb-2">
                    <span class="text-primary">●</span>
                    <div><strong>Status: {{ $laporan->status }}</strong><br />{{ $laporan->updated_at->format('H:i A') }} • SYSTEM</div>
                </div>
                <div class="d-flex gap-2">
                    <span class="text-success">●</span>
                    <div>Laporan dibuat<br />{{ $laporan->created_at->format('H:i A') }} • SYSTEM</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection