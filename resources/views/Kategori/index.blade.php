@extends('layouts.app') @section('title', 'Manajemen Kategori') @section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Manajemen Kategori</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="bi bi-plus me-1"></i> Tambah Kategori Baru</button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Total Kategori</div>
            <div class="value">{{ $total }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Kategori Aktif</div>
            <div class="value text-success">{{ $aktif }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Tanpa Kategori</div>
            <div class="value text-muted">0</div>
        </div>
    </div>
</div>

<div class="stat-card">
    <h6 class="fw-semibold mb-3">Daftar Kategori</h6>
    <table class="table table-custom mb-3">
        <thead>
            <tr>
                <th>IKON</th>
                <th>NAMA KATEGORI</th>
                <th>JUMLAH LAPORAN</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategoris as $k)
            <tr>
                <td style="font-size:1.5rem;">{{ $k->ikon }}</td>
                <td>
                    <div class="fw-semibold">{{ $k->nama }}</div>
                    <div class="text-muted" style="font-size:0.78rem;">{{ $k->deskripsi }}</div>
                </td>
                <td>
                    {{ $k->laporans_count }}
                    <div class="progress mt-1" style="height:4px;width:100px;">
                        <div class="progress-bar bg-primary" style=" width:{{ min($k->laporans_count, 100) }}%"></div>
                    </div>
                </td>
                <td>
                    @if($k->status == 'aktif') <span class="badge bg-success">● Aktif</span> @else <span class="badge bg-secondary">● Non-aktif</span>
                    @endif
                </td>
                <td class="d-flex gap-1">
                    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></button>
                    <form method="POST" action="{{ route('kategori.destroy', $k->id) }}" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-4">Belum ada kategori.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $kategoris->links() }}
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('kategori.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Nama Kategori</label><input type="text" name="nama" class="form-control" required /></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Ikon (Emoji)</label><input type="text" name="ikon" class="form-control" placeholder="🔧" /></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif">Aktif</option>
                            <option value="non-aktif">Non-aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection