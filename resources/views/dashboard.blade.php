@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Dashboard
    </h1>

    <p class="text-sm text-gray-400">
        Selamat datang di Sistem Si Lapor
    </p>
</div>

{{-- CARD STATISTIK --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

    {{-- Total --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-400">
                    Total Laporan
                </p>

                <h2 class="text-3xl font-bold text-gray-800 mt-2">
                    {{ $totalLaporan }}
                </h2>
            </div>

            <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center">
                <i class="fa-solid fa-file-lines text-blue-600 text-xl"></i>
            </div>

        </div>
    </div>

    {{-- Diproses --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-400">
                    Diproses
                </p>

                <h2 class="text-3xl font-bold text-yellow-500 mt-2">
                    {{ $diproses }}
                </h2>
            </div>

            <div class="w-14 h-14 rounded-xl bg-yellow-100 flex items-center justify-center">
                <i class="fa-solid fa-spinner text-yellow-500 text-xl"></i>
            </div>

        </div>
    </div>

    {{-- Selesai --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-400">
                    Selesai
                </p>

                <h2 class="text-3xl font-bold text-green-500 mt-2">
                    {{ $selesai }}
                </h2>
            </div>

            <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
            </div>

        </div>
    </div>

    {{-- Ditolak --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-400">
                    Ditolak
                </p>

                <h2 class="text-3xl font-bold text-red-500 mt-2">
                    {{ $ditolak }}
                </h2>
            </div>

            <div class="w-14 h-14 rounded-xl bg-red-100 flex items-center justify-center">
                <i class="fa-solid fa-circle-xmark text-red-500 text-xl"></i>
            </div>

        </div>
    </div>

</div>

{{-- GRID --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- CHART --}}
    <div class="xl:col-span-2 bg-white rounded-2xl p-6 shadow-sm">

        <div class="mb-5">
            <h2 class="text-lg font-bold text-gray-800">
                Statistik Laporan
            </h2>

            <p class="text-sm text-gray-400">
                Grafik laporan per minggu
            </p>
        </div>

        <div style="height: 320px;">
            <canvas id="laporanChart"></canvas>
        </div>

    </div>

    {{-- RECENT --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm">

        <div class="mb-5">
            <h2 class="text-lg font-bold text-gray-800">
                Aktivitas Terbaru
            </h2>

            <p class="text-sm text-gray-400">
                5 laporan terakhir
            </p>
        </div>

        <div class="space-y-4">

            @forelse($recentLaporan as $laporan)

                <div class="border-b last:border-0 pb-3">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $laporan->judul }}
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                {{ $laporan->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <span class="
                            text-xs px-3 py-1 rounded-full

                            @if($laporan->status == 'pending')
                                bg-yellow-100 text-yellow-700
                            @elseif($laporan->status == 'diproses')
                                bg-blue-100 text-blue-700
                            @elseif($laporan->status == 'selesai')
                                bg-green-100 text-green-700
                            @else
                                bg-red-100 text-red-700
                            @endif
                        ">
                            {{ ucfirst($laporan->status) }}
                        </span>

                    </div>

                </div>

            @empty

                <p class="text-sm text-gray-400">
                    Belum ada laporan
                </p>

            @endforelse

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('laporanChart');

    new Chart(ctx, {
        type: 'line',

        data: {
            labels: ['Mei'],

            datasets: [{
                label: 'Jumlah Laporan',
                data: [7],

                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.15)',
                pointBackgroundColor: '#2563eb',
                pointRadius: 5,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

});

</script>

@endsection