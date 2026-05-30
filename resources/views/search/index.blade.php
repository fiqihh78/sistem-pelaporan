@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800">Hasil Pencarian</h1>
    @if($query)
        <p class="text-sm text-gray-400">
            Menampilkan <span class="font-medium text-gray-700">{{ $total }} hasil</span> 
            untuk kata kunci "<span class="font-medium text-blue-600">{{ $query }}</span>"
        </p>
    @endif
</div>

{{-- Search Bar Besar --}}
<div class="bg-white rounded-xl shadow-sm p-5 mb-6">
    <form action="{{ route('search.index') }}" method="GET">
        <div class="flex gap-3">
            <input type="text" name="q" value="{{ $query }}"
                   placeholder="Cari laporan, petugas, atau kategori..."
                   class="flex-1 px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                   autofocus>
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg text-sm font-medium hover:bg-blue-700">
                <i class="fa-solid fa-search"></i> Cari
            </button>
        </div>
    </form>
</div>

@if($query)

    {{-- Hasil Laporan --}}
    <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
        <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-file-lines text-blue-600"></i>
            Laporan ({{ $laporans->count() }})
        </h2>
        @forelse($laporans as $laporan)
        <a href="{{ route('laporan.show', $laporan->id) }}"
           class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 border-b last:border-0">
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $laporan->judul }}</p>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $laporan->nomor_laporan }} &bull; 
                    {{ $laporan->kategori->nama ?? '-' }} &bull; 
                    {{ $laporan->lokasi }}
                </p>
            </div>
            @if($laporan->status == 'pending')
                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">Pending</span>
            @elseif($laporan->status == 'diproses')
                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">Diproses</span>
            @elseif($laporan->status == 'selesai')
                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Selesai</span>
            @else
                <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">Ditolak</span>
            @endif
        </a>
        @empty
        <p class="text-sm text-gray-400 py-2">Tidak ada laporan yang cocok</p>
        @endforelse
    </div>

    {{-- Hasil Petugas --}}
    <div class="bg-white rounded-xl shadow-sm p-5 mb-4">
        <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-users text-blue-600"></i>
            Petugas ({{ $petugas->count() }})
        </h2>
        @forelse($petugas as $p)
        <a href="{{ route('petugas.index') }}"
           class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 border-b last:border-0">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">
                {{ strtoupper(substr($p->user->name ?? 'P', 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $p->user->name ?? '-' }}</p>
                <p class="text-xs text-gray-400">{{ $p->id_petugas }} &bull; {{ $p->spesialisasi }}</p>
            </div>
        </a>
        @empty
        <p class="text-sm text-gray-400 py-2">Tidak ada petugas yang cocok</p>
        @endforelse
    </div>

    {{-- Hasil Kategori --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-tags text-blue-600"></i>
            Kategori ({{ $kategoris->count() }})
        </h2>
        @forelse($kategoris as $kategori)
        <a href="{{ route('kategori.index') }}"
           class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 border-b last:border-0">
            <span class="text-2xl">{{ $kategori->icon ?? '📋' }}</span>
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $kategori->nama }}</p>
                <p class="text-xs text-gray-400">{{ $kategori->deskripsi ?? '-' }}</p>
            </div>
        </a>
        @empty
        <p class="text-sm text-gray-400 py-2">Tidak ada kategori yang cocok</p>
        @endforelse
    </div>

@else
    <div class="bg-white rounded-xl shadow-sm p-10 text-center">
        <i class="fa-solid fa-search text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-400">Ketik kata kunci di atas untuk mulai mencari</p>
    </div>
@endif

@endsection