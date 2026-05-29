@extends('layouts.app')

@section('title', 'Statistik Laporan')

@section('content')

<div class="mb-4">
    <h4 class="fw-bold mb-1">
        Statistik Laporan
    </h4>

    <p class="text-muted mb-0" style="font-size:0.875rem;">
        Analisis data laporan masyarakat secara real-time
    </p>
</div>

<!-- Statistik Cards -->
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="stat-card">

            <div class="label">
                Total Laporan
            </div>

            <div class="value">
                {{ number_format($total ?? 0) }}
            </div>

        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">

            <div class="label">
                Laporan Selesai
            </div>

            <div class="value text-success">
                {{ number_format($selesai ?? 0) }}
            </div>

            <div class="text-muted" style="font-size:0.78rem;">
                Tingkat penyelesaian
                {{ ($total ?? 0) > 0 ? round((($selesai ?? 0) / $total) * 100) : 0 }}%
            </div>

        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">

            <div class="label">
                Waktu Penyelesaian
            </div>

            <div class="value">
                1.5 Hari
            </div>

            <div class="text-muted" style="font-size:0.78rem;">
                Rata-rata durasi
            </div>

        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card">

            <div class="label">
                Kepuasan Masyarakat
            </div>

            <div class="value">
                4.8 / 5.0
            </div>

            <div class="text-muted" style="font-size:0.78rem;">
                ⭐ Berdasarkan 1.8k rating
            </div>

        </div>
    </div>

</div>

<!-- Chart Section -->
<div class="row g-3">

    <!-- Tren Bulanan -->
    <div class="col-md-8">

        <div class="stat-card h-100">

            <h6 class="fw-semibold mb-3">
                Tren Laporan Bulanan
            </h6>

            <canvas id="trenBulanan" height="120"></canvas>

        </div>

    </div>

    <!-- Distribusi -->
    <div class="col-md-4">

        <div class="stat-card h-100">

            <h6 class="fw-semibold mb-3">
                Distribusi Kategori
            </h6>

            <canvas id="distribusiChart" height="180"></canvas>

            <div class="mt-3">

                @forelse($distribusi ?? [] as $d)

                <div class="d-flex justify-content-between mb-1"
                    style="font-size:0.83rem;">

                    <span>
                        ● {{ $d->nama ?? '-' }}
                    </span>

                    <span>
                        {{ ($total ?? 0) > 0
                                ? round((($d->laporans_count ?? 0) / $total) * 100)
                                : 0 }}%
                    </span>

                </div>

                @empty

                <div class="text-muted">
                    Belum ada data distribusi.
                </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        /*
        |--------------------------------------------------------------------------
        | Chart Tren Bulanan
        |--------------------------------------------------------------------------
        */

        const trenCanvas = document.getElementById('trenBulanan');

        if (trenCanvas) {

            new Chart(trenCanvas, {

                type: 'line',

                data: {

                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],

                    datasets: [

                        {
                            label: 'Masuk',

                            data: [200, 300, 450, 400, 520, 480],

                            borderColor: '#2563eb',

                            backgroundColor: 'rgba(37,99,235,0.08)',

                            fill: false,

                            tension: 0.4
                        },

                        {
                            label: 'Selesai',

                            data: [150, 250, 400, 350, 470, 430],

                            borderColor: '#22c55e',

                            backgroundColor: 'rgba(34,197,94,0.08)',

                            fill: false,

                            tension: 0.4
                        }

                    ]

                },

                options: {

                    responsive: true,

                    plugins: {

                        legend: {
                            position: 'top'
                        }

                    },

                    scales: {

                        y: {
                            beginAtZero: true
                        }

                    }

                }

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Chart Distribusi
        |--------------------------------------------------------------------------
        */

        const distribusiCanvas = document.getElementById('distribusiChart');

        if (distribusiCanvas) {

            new Chart(distribusiCanvas, {

                type: 'doughnut',

                data: {

                    labels: @json(($distribusi ?? collect()) - > pluck('nama')),

                    datasets: [{

                        data: @json(($distribusi ?? collect()) - > pluck('laporans_count')),

                        backgroundColor: [
                            '#2563eb',
                            '#22c55e',
                            '#f59e0b',
                            '#ef4444',
                            '#8b5cf6',
                            '#06b6d4'
                        ]

                    }]

                },

                options: {

                    responsive: true,

                    plugins: {

                        legend: {
                            display: false
                        }

                    }

                }

            });

        }

    });
</script>

@endpush