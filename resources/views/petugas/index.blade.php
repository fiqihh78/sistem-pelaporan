@extends('layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Data Petugas</h1>
        <p class="text-sm text-gray-400">Kelola data petugas lapangan</p>
    </div>
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 hover:bg-blue-700">
        <i class="fa-solid fa-plus"></i> Tambah Petugas
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3">
        <div class="bg-blue-100 p-3 rounded-lg">
            <i class="fa-solid fa-users text-blue-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400">Total Petugas</p>
            <p class="text-xl font-bold text-gray-800">{{ $petugas->total() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3">
        <div class="bg-green-100 p-3 rounded-lg">
            <i class="fa-solid fa-user-check text-green-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400">Petugas Aktif</p>
            <p class="text-xl font-bold text-gray-800">{{ $petugas->where('status', 'aktif')->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3">
        <div class="bg-red-100 p-3 rounded-lg">
            <i class="fa-solid fa-user-xmark text-red-600"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400">Tidak Aktif</p>
            <p class="text-xl font-bold text-gray-800">{{ $petugas->where('status', 'nonaktif')->count() }}</p>
        </div>
    </div>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl shadow-sm p-5">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-400 border-b">
                <th class="pb-3">ID Petugas</th>
                <th class="pb-3">Nama Petugas</th>
                <th class="pb-3">Spesialisasi</th>
                <th class="pb-3">Status</th>
                <th class="pb-3">Beban Kerja</th>
                <th class="pb-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($petugas as $p)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 text-blue-600 font-medium">{{ $p->id_petugas }}</td>
                <td class="py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">
                            {{ strtoupper(substr($p->user->name ?? 'P', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $p->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $p->user->email ?? '-' }}</p>
                        </div>
                    </div>
                </td>
                <td class="py-3 text-gray-600">{{ $p->spesialisasi ?? '-' }}</td>
                <td class="py-3">
                    @if($p->status == 'aktif')
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Aktif</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">Nonaktif</span>
                    @endif
                </td>
                <td class="py-3 text-gray-600">{{ $p->beban_kerja }} Tugas</td>
                <td class="py-3">
                    <form action="{{ route('petugas.destroy', $p->id) }}" method="POST"
                          onsubmit="return confirm('Hapus petugas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-8 text-center text-gray-400">Belum ada data petugas</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $petugas->links() }}</div>
</div>

{{-- Modal Tambah Petugas --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-gray-800">Tambah Petugas Baru</h2>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-x"></i>
            </button>
        </div>
        <form action="{{ route('petugas.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-gray-600">Nama Lengkap</label>
                <input type="text" name="name" required
                       class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div>
                <label class="text-sm text-gray-600">Email</label>
                <input type="email" name="email" required
                       class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div>
                <label class="text-sm text-gray-600">Password</label>
                <input type="password" name="password" required
                       class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div>
                <label class="text-sm text-gray-600">Spesialisasi</label>
                <input type="text" name="spesialisasi" required
                       class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                       placeholder="Infrastruktur, Kebersihan, dll">
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                Simpan
            </button>
        </form>
    </div>
</div>

@endsection