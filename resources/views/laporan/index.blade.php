@extends('layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Laporan Masuk</h1>
        <p class="text-sm text-gray-400">Daftar semua laporan dari masyarakat</p>
    </div>
    <a href="{{ route('laporan.exportPdf') }}"
        style="background-color: #8B2E2E;" class="text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 hover:opacity-90">
        <i class="fa-solid fa-file-pdf"></i> Export PDF
    </a>
</div>

{{-- Filter Status --}}
<div class="flex gap-2 mb-4">
    <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}"
       class="px-4 py-2 rounded-lg text-sm {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
        Semua
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
       class="px-4 py-2 rounded-lg text-sm {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
        Pending
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'diproses']) }}"
       class="px-4 py-2 rounded-lg text-sm {{ request('status') == 'diproses' ? 'bg-blue-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
        Diproses
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'selesai']) }}"
       class="px-4 py-2 rounded-lg text-sm {{ request('status') == 'selesai' ? 'bg-green-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
        Selesai
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'ditolak']) }}"
       class="px-4 py-2 rounded-lg text-sm {{ request('status') == 'ditolak' ? 'bg-red-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
        Ditolak
    </a>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl shadow-sm p-5">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-400 border-b">
                <th class="pb-3">ID Laporan</th>
                <th class="pb-3">Pelapor</th>
                <th class="pb-3">Kategori</th>
                <th class="pb-3">Lokasi</th>
                <th class="pb-3">Tanggal</th>
                <th class="pb-3">Status</th>
                <th class="pb-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $laporan)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 text-blue-600 font-medium">{{ $laporan->nomor_laporan }}</td>
                <td class="py-3 text-gray-700">{{ $laporan->user->name ?? '-' }}</td>
                <td class="py-3 text-gray-700">{{ $laporan->kategori->nama ?? '-' }}</td>
                <td class="py-3 text-gray-500">{{ $laporan->lokasi }}</td>
                <td class="py-3 text-gray-400">{{ $laporan->created_at->format('d M Y') }}</td>
                <td class="py-3">
                    @if($laporan->status == 'pending')
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">Pending</span>
                    @elseif($laporan->status == 'diproses')
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">Diproses</span>
                    @elseif($laporan->status == 'selesai')
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Selesai</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">Ditolak</span>
                    @endif
                </td>
                <td class="py-3">
                    <a href="{{ route('laporan.show', $laporan->id) }}"
                       class="text-blue-600 hover:underline text-xs">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-8 text-center text-gray-400">Belum ada laporan masuk</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $laporans->links() }}
    </div>
</div>

@endsection
