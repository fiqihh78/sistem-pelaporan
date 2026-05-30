@extends('layouts.app')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Manajemen Kategori</h1>
        <p class="text-sm text-gray-400">Kelola kategori laporan masyarakat</p>
    </div>
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 hover:bg-blue-700">
        <i class="fa-solid fa-plus"></i> Tambah Kategori
    </button>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl shadow-sm p-5">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-400 border-b">
                <th class="pb-3">Nama Kategori</th>
                <th class="pb-3">Deskripsi</th>
                <th class="pb-3">Jumlah Laporan</th>
                <th class="pb-3">Status</th>
                <th class="pb-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategoris as $kategori)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">{{ $kategori->icon ?? '📋' }}</span>
                        <p class="font-medium text-gray-800">{{ $kategori->nama }}</p>
                    </div>
                </td>
                <td class="py-3 text-gray-500">{{ $kategori->deskripsi ?? '-' }}</td>
                <td class="py-3 text-gray-700">{{ $kategori->laporans_count }} laporan</td>
                <td class="py-3">
                    @if($kategori->status == 'aktif')
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Aktif</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">Nonaktif</span>
                    @endif
                </td>
                <td class="py-3 flex gap-2">
                    <button onclick="editKategori({{ $kategori->id }}, '{{ $kategori->nama }}', '{{ $kategori->deskripsi }}', '{{ $kategori->icon }}')"
                            class="text-blue-500 hover:text-blue-700 text-xs">
                        <i class="fa-solid fa-pen"></i> Edit
                    </button>
                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST"
                          onsubmit="return confirm('Hapus kategori ini?')">
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
                <td colspan="5" class="py-8 text-center text-gray-400">Belum ada kategori</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $kategoris->links() }}</div>
</div>

{{-- Modal Tambah --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-gray-800">Tambah Kategori</h2>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-x"></i>
            </button>
        </div>
        <form action="{{ route('kategori.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-gray-600">Nama Kategori</label>
                <input type="text" name="nama" required
                       class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div>
                <label class="text-sm text-gray-600">Icon (emoji)</label>
                <input type="text" name="icon"
                       class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                       placeholder="🏗️">
            </div>
            <div>
                <label class="text-sm text-gray-600">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"></textarea>
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                Simpan
            </button>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-gray-800">Edit Kategori</h2>
            <button onclick="document.getElementById('modalEdit').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-x"></i>
            </button>
        </div>
        <form id="formEdit" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="text-sm text-gray-600">Nama Kategori</label>
                <input type="text" id="editNama" name="nama" required
                       class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div>
                <label class="text-sm text-gray-600">Icon (emoji)</label>
                <input type="text" id="editIcon" name="icon"
                       class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div>
                <label class="text-sm text-gray-600">Deskripsi</label>
                <textarea id="editDeskripsi" name="deskripsi" rows="3"
                          class="w-full mt-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"></textarea>
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                Update
            </button>
        </form>
    </div>
</div>

<script>
function editKategori(id, nama, deskripsi, icon) {
    document.getElementById('formEdit').action = '/kategori/' + id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editDeskripsi').value = deskripsi;
    document.getElementById('editIcon').value = icon;
    document.getElementById('modalEdit').classList.remove('hidden');
}
</script>

@endsection