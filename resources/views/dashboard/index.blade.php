@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-sm text-gray-400 mt-1">{{ now()->format('l, d F Y') }} • Auto refresh setiap 30 detik</p>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm flex items-center gap-2 hover:bg-blue-700 shadow-sm">
        <i class="fa-solid fa-download"></i> Export Laporan
    </button>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-blue-50 p-2.5 rounded-xl">
                <i class="fa-solid fa-file-lines text-blue-600"></i>
            </div>
            <span class="text-xs text-green-500 font-medium bg-green-50 px-2 py-1 rounded-full">+12%</span>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $total_laporan }}</p>
        <p class="text-sm text-gray-400 mt-1">Total Laporan</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-yellow-50 p-2.5 rounded-xl">
                <i class="fa-solid fa-clock text-yellow-500"></i>
            </div>
            <span class="text-xs text-yellow-500 font-medium bg-yellow-50 px-2 py-1 rounded-full">Pending</span>
        </div>
        <p class="text-3xl font-bold text-yellow-500">{{ $pending }}</p>
        <p class="text-sm text-gray-400 mt-1">Menunggu Proses</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-blue-50 p-2.5 rounded-xl">
                <i class="fa-solid fa-spinner text-blue-500"></i>
            </div>
            <span class="text-xs text-blue-500 font-medium bg-blue-50 px-2 py-1 rounded-full">Aktif</span>
        </div>
        <p class="text-3xl font-bold text-blue-500">{{ $diproses }}</p>
        <p class="text-sm text-gray-400 mt-1">Sedang Diproses</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="bg-green-50 p-2.5 rounded-xl">
                <i class="fa-solid fa-circle-check text-green-500"></i>
            </div>
            <span class="text-xs text-green-500 font-medium bg-green-50 px-2 py-1 rounded-full">Done</span>
        </div>
        <p class="text-3xl font-bold text-green-500">{{ $selesai }}</p>
        <p class="text-sm text-gray-400 mt-1">Terselesaikan</p>
    </div>
</div>

{{-- Grafik + Aktivitas --}}
<div class="grid grid-cols-3 gap-4 mb-6">

    {{-- Grafik Mingguan --}}
    <div class="col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-semibold text-gray-800">Tren Laporan Mingguan</h2>
                <p class="text-xs text-gray-400">7 hari terakhir</p>
            </div>
            <div class="flex gap-2 text-xs">
                <span class="flex items-center gap-1"><span class="w-3 h-3 bg-blue-500 rounded-full inline-block"></span> Masuk</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 bg-green-500 rounded-full inline-block"></span> Selesai</span>
            </div>
        </div>
        <canvas id="grafikMingguan" height="120"></canvas>
    </div>

    {{-- Distribusi Kategori --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="mb-4">
            <h2 class="font-semibold text-gray-800">Distribusi Kategori</h2>
            <p class="text-xs text-gray-400">Berdasarkan jumlah laporan</p>
        </div>
        <canvas id="grafikKategori" height="180"></canvas>
        <div class="mt-3 space-y-2">
            @foreach($laporan_per_kategori->take(4) as $index => $kat)
            @php
                $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500'];
                $color = $colors[$index % 4];
            @endphp
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full {{ $color }} inline-block"></span>
                    <span class="text-gray-600">{{ $kat->nama }}</span>
                </div>
                <span class="font-medium text-gray-800">{{ $kat->laporans_count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Tabel + Aktivitas --}}
<div class="grid grid-cols-3 gap-4">

    {{-- Laporan Terbaru --}}
    <div class="col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-semibold text-gray-800">Laporan Terbaru</h2>
                <p class="text-xs text-gray-400">5 laporan terakhir masuk</p>
            </div>
            <a href="{{ route('laporan.index') }}"
               class="text-blue-600 text-xs hover:underline font-medium">Lihat Semua →</a>
        </div>
        <div class="space-y-3">
            @forelse($laporan_terbaru as $laporan)
            <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-file text-blue-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $laporan->judul }}</p>
                        <p class="text-xs text-gray-400">{{ $laporan->nomor_laporan }} • {{ $laporan->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if($laporan->status == 'pending')
                    <span class="bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-full text-xs font-medium">Pending</span>
                @elseif($laporan->status == 'diproses')
                    <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-medium">Diproses</span>
                @elseif($laporan->status == 'selesai')
                    <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-medium">Selesai</span>
                @else
                    <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-medium">Ditolak</span>
                @endif
            </div>
            @empty
            <div class="text-center py-8 text-gray-400">
                <i class="fa-solid fa-inbox text-3xl mb-2"></i>
                <p class="text-sm">Belum ada laporan masuk</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Laporan per Kategori --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="mb-4">
            <h2 class="font-semibold text-gray-800">Per Kategori</h2>
            <p class="text-xs text-gray-400">Persentase laporan</p>
        </div>
        <div class="space-y-4">
            @php $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-purple-500']; @endphp
            @forelse($laporan_per_kategori as $index => $kat)
            @php
                $persen = $total_laporan > 0 ? round(($kat->laporans_count / $total_laporan) * 100) : 0;
                $color = $colors[$index % 5];
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-medium text-gray-700">{{ $kat->nama }}</p>
                    <p class="text-xs text-gray-400">{{ $persen }}%</p>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="{{ $color }} h-2 rounded-full transition-all duration-500"
                         style="width: {{ $persen }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada kategori</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
// Data dari Laravel
const weeklyData = @json($grafik_mingguan);
const weeklySelesai = @json($grafik_selesai);
const labels = @json($grafik_labels);
const kategoriLabels = @json($laporan_per_kategori->pluck('nama'));
const kategoriData = @json($laporan_per_kategori->pluck('laporans_count'));

// Grafik Mingguan
const ctx1 = document.getElementById('grafikMingguan').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Laporan Masuk',
                data: weeklyData,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59,130,246,0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#3B82F6',
                pointRadius: 4,
                tension: 0.4,
                fill: true,
            },
            {
                label: 'Selesai',
                data: weeklySelesai,
                borderColor: '#10B981',
                backgroundColor: 'rgba(16,185,129,0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#10B981',
                pointRadius: 4,
                tension: 0.4,
                fill: true,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1e293b',
                titleColor: '#fff',
                bodyColor: '#94a3b8',
                padding: 10,
                cornerRadius: 8,
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1, color: '#94a3b8', font: { size: 11 } },
                grid: { color: '#f1f5f9' },
            },
            x: {
                ticks: { color: '#94a3b8', font: { size: 11 } },
                grid: { display: false },
            }
        }
    }
});

// Grafik Donut Kategori
const ctx2 = document.getElementById('grafikKategori').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: kategoriLabels,
        datasets: [{
            data: kategoriData,
            backgroundColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6'],
            borderWidth: 0,
            hoverOffset: 4,
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1e293b',
                titleColor: '#fff',
                bodyColor: '#94a3b8',
                padding: 10,
                cornerRadius: 8,
            }
        }
    }
});
</script>

@endsection