@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800">Statistik Laporan</h1>
    <p class="text-sm text-gray-400">Grafik dan distribusi laporan masyarakat</p>
</div>

{{-- Cards Status --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-yellow-400">
        <p class="text-xs text-gray-400">Pending</p>
        <p class="text-2xl font-bold text-gray-800">{{ $statusData['pending'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-blue-400">
        <p class="text-xs text-gray-400">Diproses</p>
        <p class="text-2xl font-bold text-gray-800">{{ $statusData['diproses'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-green-400">
        <p class="text-xs text-gray-400">Selesai</p>
        <p class="text-2xl font-bold text-gray-800">{{ $statusData['selesai'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-red-400">
        <p class="text-xs text-gray-400">Ditolak</p>
        <p class="text-2xl font-bold text-gray-800">{{ $statusData['ditolak'] }}</p>
    </div>
</div>

{{-- Grafik --}}
<div class="grid grid-cols-2 gap-6 mb-6">

    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-4">Distribusi Status</h2>
        <canvas id="statusChart"></canvas>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-4">Laporan per Kategori</h2>
        <canvas id="kategoriChart"></canvas>
    </div>

</div>

<div class="bg-white rounded-xl shadow-sm p-5">
    <h2 class="font-semibold text-gray-800 mb-4">Tren Laporan per Bulan</h2>
    <canvas id="bulananChart"></canvas>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Donut
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Diproses', 'Selesai', 'Ditolak'],
            datasets: [{
                data: [{{ $statusData['pending'] }}, {{ $statusData['diproses'] }}, {{ $statusData['selesai'] }}, {{ $statusData['ditolak'] }}],
                backgroundColor: ['#F59E0B','#3B82F6','#10B981','#EF4444'],
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    // Kategori Bar
    new Chart(document.getElementById('kategoriChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($kategoriLabels) !!},
            datasets: [{
                label: 'Jumlah Laporan',
                data: {!! json_encode($kategoriData) !!},
                backgroundColor: '#3B82F6',
                borderRadius: 6,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Bulanan Line
    new Chart(document.getElementById('bulananChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($bulananLabels) !!},
            datasets: [{
                label: 'Jumlah Laporan',
                data: {!! json_encode($bulananData) !!},
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                fill: true,
                tension: 0.4,
            }]
        },
        options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
</script>
@endsection