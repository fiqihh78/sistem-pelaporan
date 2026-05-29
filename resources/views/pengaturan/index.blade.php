@extends('layouts.app') @section('title', 'Pengaturan Sistem') @section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Pengaturan Sistem</h4>
    <p class="text-muted mb-0" style="font-size:0.875rem;">Kelola profil, keamanan, dan preferensi akun administrasi Anda.</p>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="list-group list-group-flush">
                <a href="#profil" class="list-group-item list-group-item-action active border-0 rounded mb-1"><i class="bi bi-person me-2"></i>Profil Admin</a>
                <a href="#keamanan" class="list-group-item list-group-item-action border-0 rounded mb-1"><i class="bi bi-shield me-2"></i>Keamanan</a>
                <a href="#notif" class="list-group-item list-group-item-action border-0 rounded mb-1"><i class="bi bi-bell me-2"></i>Notifikasi Sistem</a>
                <a href="#preferensi" class="list-group-item list-group-item-action border-0 rounded"><i class="bi bi-sliders me-2"></i>Preferensi</a>
            </div>
            <div class="mt-3 p-3 bg-light rounded">
                <div class="fw-semibold text-primary" style="font-size:0.9rem;"><i class="bi bi-info-circle me-1"></i>Bantuan Admin</div>
                <p class="text-muted mt-1 mb-2" style="font-size:0.8rem;">Butuh bantuan mengelola pengaturan akses? Hubungi tim IT pusat.</p>
                <a href="#" class="text-primary" style="font-size:0.8rem;">Tiket Dukungan →</a>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <!-- Profil -->
        <div class="stat-card mb-3" id="profil">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0">Profil Admin</h6>
                <button form="formProfil" class="btn btn-primary btn-sm">Simpan Perubahan</button>
            </div>
            <form id="formProfil" method="POST" action="{{ route('pengaturan.update') }}">
                @csrf @method('PUT')
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="user-avatar" style="width:64px;height:64px;font-size:1.5rem;">{{ substr($user->name, 0, 1) }}</div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Dinas</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jabatan</label>
                        <select name="jabatan" class="form-select">
                            <option>Super Admin</option>
                            <option>Admin</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Keamanan -->
        <div class="stat-card mb-3" id="keamanan">
            <h6 class="fw-semibold mb-3">Keamanan & Akses</h6>
            <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-2">
                <div>
                    <div class="fw-semibold" style="font-size:0.9rem;">🔒 Kata Sandi</div>
                    <div class="text-muted" style="font-size:0.8rem;">Terakhir diubah 3 bulan yang lalu</div>
                </div>
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalPassword">Ubah Password</button>
            </div>
            <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                <div>
                    <div class="fw-semibold" style="font-size:0.9rem;">🛡️ Autentikasi Dua Faktor (2FA) <span class="badge bg-success ms-1" style="font-size:0.65rem;">AKTIF</span></div>
                    <div class="text-muted" style="font-size:0.8rem;">Amankan akun Anda dengan verifikasi tambahan</div>
                </div>
                <button class="btn btn-outline-secondary btn-sm">Konfigurasi</button>
            </div>
        </div>

        <!-- Preferensi -->
        <div class="stat-card" id="preferensi">
            <h6 class="fw-semibold mb-3">Preferensi Sistem</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Bahasa Interface</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm">🇮🇩 Indonesia</button>
                        <button class="btn btn-outline-secondary btn-sm">🇺🇸 English</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tema Tampilan</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm">☀️ Terang</button>
                        <button class="btn btn-outline-secondary btn-sm">🌙 Gelap</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Password -->
<div class="modal fade" id="modalPassword" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('pengaturan.password') }}">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Password Saat Ini</label><input type="password" name="current_password" class="form-control" required /></div>
                    <div class="mb-3"><label class="form-label">Password Baru</label><input type="password" name="password" class="form-control" required /></div>
                    <div class="mb-3"><label class="form-label">Konfirmasi Password</label><input type="password" name="password_confirmation" class="form-control" required /></div>
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