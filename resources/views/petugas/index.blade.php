@extends('layouts.app') @section('title', 'Data Petugas') @section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Data Petugas</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Kelola dan pantau seluruh personil lapangan secara real-time.</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPetugas"><i class="bi bi-plus me-1"></i> Tambah Petugas Baru</button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Total Petugas</div>
            <div class="value">{{ $total }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Petugas Aktif</div>
            <div class="value text-primary">{{ $aktif }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="label">Tersedia</div>
            <div class="value text-success">{{ $tersedia }}</div>
        </div>
    </div>
</div>

<div class="stat-card">
    <h6 class="fw-semibold mb-3">Daftar Personil</h6>
    <table class="table table-custom mb-3">
        <thead>
            <tr>
                <th>ID PETUGAS</th>
                <th>NAMA PETUGAS</th>
                <th>SPESIALISASI</th>
                <th>STATUS</th>
                <th>BEBAN KERJA</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($petugas as $p)
            <tr>
                <td class="text-muted" style="font-size:0.82rem;">{{ $p->kode }}</td>
                <td>
                    <div class="fw-semibold">{{ $p->nama }}</div>
                    <div class="text-muted" style="font-size:0.78rem;">{{ $p->email }}</div>
                </td>
                <td><span class="text-primary" style="font-size:0.85rem;">{{ $p->spesialisasi }}</span></td>
                <td>
                    @if($p->status == 'tersedia') <span class="badge bg-success">● Tersedia</span> @elseif($p->status == 'bertugas') <span class="badge bg-primary">● Bertugas</span> @else <span class="badge bg-warning text-dark">● Istirahat</span>
                    @endif
                </td>
                <td>{{ $p->beban_kerja }} Tugas</td>
                <td>
                    <form method="POST" action="{{ route('petugas.destroy', $p->id) }}" onsubmit="return confirm('Hapus petugas ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">Belum ada data petugas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $petugas->links() }}
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahPetugas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Petugas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('petugas.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Nama Lengkap</label><input type="text" name="nama" class="form-control" required /></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Email Dinas</label><input type="email" name="email" class="form-control" required /></div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Spesialisasi</label>
                        <select name="spesialisasi" class="form-select">
                            <option>Infrastruktur</option>
                            <option>Lingkungan</option>
                            <option>Keamanan</option>
                            <option>Penerangan Jalan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Awal</label>
                        <select name="status" class="form-select">
                            <option value="tersedia">Tersedia</option>
                            <option value="bertugas">Bertugas</option>
                            <option value="istirahat">Istirahat</option>
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