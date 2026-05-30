@extends('layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Verifikasi Laporan</h1>
        <p class="text-sm text-gray-400">Daftar laporan yang menunggu verifikasi</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <p class="text-xs text-gray-400">Total Menunggu</p>
        <p class="text-2xl font-bold text-gray-800">{{ $total_pending }}</p>
        <p class="text-xs text-yellow-500 mt-1">Menunggu verifikasi</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <p class="text-xs text-gray-400">Terverifikasi</p>
        <p class="text-2xl font-bold text-gray-800">{{ $terverifikasi }}</p>
        <p class="text-xs text-green-500 mt-1">Sudah diproses</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <p class="text-xs text-gray-400">Ditolak</p>
        <p class="text-2xl font-bold text-gray-800">{{ $ditolak }}</p>
        <p class="text-xs text-red-500 mt-1">Tidak memenuhi syarat</p>
    </div>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl shadow-sm p-5">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-400 border-b">
                <th class="pb-3">ID Laporan</th>
                <th class="pb-3">Pelapor</th>
                <th class="pb-3">Kategori</th>
                <th class="pb-3">Tanggal</th>
                <th class="pb-3">Bukti</th>
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
                <td class="py-3 text-gray-400">{{ $laporan->created_at->format('d M Y') }}</td>
                <td class="py-3">
                    @if($laporan->foto_bukti)
                        <a href="{{ asset('storage/' . $laporan->foto_bukti) }}" target="_blank"
                           class="text-blue-600 text-xs hover:underline">Lihat Foto</a>
                    @else
                        <span class="text-gray-400 text-xs">Tidak ada</span>
                    @endif
                </td>
                <td class="py-3">
                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">Pending</span>
                </td>
                <td class="py-3 flex gap-2">
                    <form action="{{ route('verifikasi.approve', $laporan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="bg-green-500 text-white px-3 py-1 rounded-lg text-xs hover:bg-green-600">
                            Verifikasi
                        </button>
                    </form>
                    <form action="{{ route('verifikasi.reject', $laporan->id) }}" method="POST"
                          onsubmit="return confirm('Tolak laporan ini?')">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs hover:bg-red-600">
                            Tolak
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-8 text-center text-gray-400">
                    Tidak ada laporan yang menunggu verifikasi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $laporans->links() }}</div>
</div>

@endsection