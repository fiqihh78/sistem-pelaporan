@extends('layouts.app') @section('title', 'Pusat Notifikasi') @section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Pusat Notifikasi</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Pantau semua aktivitas dan pembaruan sistem secara real-time.</p>
    </div>
    <form method="POST" action="{{ route('notifikasi.tandai-semua') }}">
        @csrf
        <button class="btn btn-outline-primary btn-sm"><i class="bi bi-check-all me-1"></i> Tandai Semua Sudah Dibaca</button>
    </form>
</div>

<div class="stat-card">
    @foreach($notifikasis as $n)
    <div class="d-flex gap-3 p-3 mb-2 rounded {{ $n->sudah_dibaca ? 'bg-white' : 'bg-light border-start border-primary border-3' }}">
        <div style="font-size:1.5rem;">@if($n->tipe == 'laporan_baru') 📋 @elseif($n->tipe == 'penugasan') 👷 @elseif($n->tipe == 'status_berubah') ✅ @else ⚙️ @endif</div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start">
                <div class="fw-semibold" style="font-size:0.9rem;">{{ $n->judul }} @if(!$n->sudah_dibaca) <span class="badge bg-primary ms-1" style="font-size:0.65rem;">Baru</span> @endif</div>
                <span class="text-muted" style="font-size:0.75rem;">{{ $n->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-muted mb-1" style="font-size:0.83rem;">{{ $n->pesan }}</p>
            @if($n->link) <a href="{{ $n->link }}" class="text-primary" style="font-size:0.8rem;">Lihat Detail</a> @endif
        </div>
    </div>
    @endforeach
    <div class="text-center mt-3">{{ $notifikasis->links() }}</div>
</div>
@endsection