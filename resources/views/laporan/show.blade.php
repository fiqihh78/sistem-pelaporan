@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')

<div class="mb-6">
    <a href="{{ route('laporan.index') }}" class="text-blue-600 text-sm hover:underline">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Laporan
    </a>
</div>

<div class="grid grid-cols-3 gap-6">

    {{-- Detail Laporan --}}
    <div class="col-span-2 space-y-6">

        {{-- Header --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs text-gray-400">{{ $laporan->kode ?? 'REP-' . $laporan->id }}</p>
                    <h1 class="text-xl font-bold text-gray-800">{{ $laporan->judul }}</h1>
                    <p class="text-sm text-gray-400 mt-1">
                        <i class="fa-solid fa-location-dot"></i> {{ $laporan->lokasi }}
                    </p>
                </div>
                @if($laporan->status == 'pending')
                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">Pending</span>
                @elseif($laporan->status == 'diproses')
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">Diproses</span>
                @elseif($laporan->status == 'selesai')
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Selesai</span>
                @else
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">Ditolak</span>
                @endif
            </div>

            <div class="border-t pt-4">
                <p class="text-sm text-gray-600 leading-relaxed">{{ $laporan->deskripsi }}</p>
            </div>

            {{-- ▼ FOTO DARI USER (foto_sebelum) ▼ --}}
            @if($laporan->foto_sebelum)
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-700 mb-2">
                    <i class="fa-solid fa-image text-blue-500"></i> Foto Laporan dari User
                </p>
                <img src="{{ $laporan->foto_sebelum }}"
                     alt="Foto Laporan"
                     class="rounded-lg w-full h-auto"
                     onerror="this.style.display='none'">
            </div>
            @endif

            {{-- ▼ FOTO SESUDAH (foto_sesudah) ▼ --}}
            @if($laporan->foto_sesudah)
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-700 mb-2">
                    <i class="fa-solid fa-image text-green-500"></i> Foto Sesudah Diperbaiki
                </p>
                <img src="{{ $laporan->foto_sesudah }}"
                     alt="Foto Sesudah"
                     class="rounded-lg max-h-64 object-cover w-full"
                     onerror="this.style.display='none'">
            </div>
            @endif

            {{-- ▼ FOTO BUKTI LAMA (backward compat) ▼ --}}
            @if($laporan->foto_bukti)
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-700 mb-2">Foto Bukti</p>
                <img src="{{ asset('storage/' . $laporan->foto_bukti) }}"
                     alt="Bukti"
                     class="rounded-lg max-h-64 object-cover">
            </div>
            @endif
        </div>

        {{-- Update Status --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Update Status Laporan</h2>
            <form action="{{ route('laporan.updateStatus', $laporan->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Status</label>
                        <select name="status"
                                class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="pending"  {{ $laporan->status == 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="diproses" {{ $laporan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai"  {{ $laporan->status == 'selesai'  ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak"  {{ $laporan->status == 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Assign Petugas</label>
                        <select name="petugas_id"
                                class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option value="">-- Pilih Petugas --</option>
                            @forelse($petugas as $p)
                                <option value="{{ $p->id }}"
                                    {{ optional($laporan->penugasan)->petugas_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} ({{ $p->spesialisasi ?? '-' }})
                                </option>
                            @empty
                                <option disabled>Belum ada petugas aktif</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Catatan Admin</label>
                    <textarea name="catatan_admin" rows="3"
                              class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">{{ $laporan->catatan_admin }}</textarea>
                </div>
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    {{-- Sidebar Info --}}
    <div class="space-y-4">

        {{-- Info Pelapor --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-3">Info Pelapor</h2>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                    {{ strtoupper(substr($laporan->user->name ?? $laporan->pelapor ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">
                        {{ $laporan->user->name ?? $laporan->pelapor ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $laporan->user->email ?? '-' }}</p>
                </div>
            </div>
            <div class="text-xs text-gray-500 space-y-1">
                <p><span class="font-medium">Kategori:</span> {{ $laporan->kategori->nama ?? '-' }}</p>
                <p><span class="font-medium">Kode:</span> {{ $laporan->kode ?? 'REP-' . $laporan->id }}</p>
                <p><span class="font-medium">Tanggal:</span> {{ $laporan->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        {{-- Info Petugas Assigned --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-3">Petugas Assigned</h2>
            @if($laporan->penugasan && $laporan->penugasan->petugas)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-bold">
                        {{ strtoupper(substr($laporan->penugasan->petugas->nama ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $laporan->penugasan->petugas->nama ?? '-' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $laporan->penugasan->petugas->spesialisasi ?? '-' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $laporan->penugasan->petugas->telepon ?? '-' }}
                        </p>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-400">Belum ada petugas assigned</p>
            @endif
        </div>

    </div>
</div>

@endsection
