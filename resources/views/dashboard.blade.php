@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="glass-card rounded-2xl p-6 shadow-xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1
                        class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-[var(--gradient-from)] to-[var(--gradient-to)]">
                        Selamat Datang di SPK P2AL II</h1>
                    <p class="mt-2 text-gray-600">Sistem Pendukung Keputusan Kepulangan Santri</p>
                </div>

            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card Jumlah Santri -->
            <div class="glass-card rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Jumlah Santri</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalSantri ?? '0' }}</h3>
                        </div>
                        <div class="p-3 rounded-xl bg-[var(--color-primary-100)] text-[var(--color-primary-600)]">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('santri.index') }}"
                            class="text-sm font-medium text-[var(--color-primary-600)] hover:text-[var(--color-primary-500)] flex items-center">
                            Lihat detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-[var(--color-primary-50)] to-white h-1.5 w-full"></div>
            </div>

            <!-- Card Kriteria -->
            <div class="glass-card rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Jumlah Kriteria</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalKriteria ?? '0' }}</h3>
                        </div>
                        <div class="p-3 rounded-xl bg-red-100 text-red-600">
                            <i class="fas fa-list-check text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('kriteria.index') }}"
                            class="text-sm font-medium text-red-600 hover:text-red-500 flex items-center">
                            Lihat detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-red-50 to-white h-1.5 w-full"></div>
            </div>

            <!-- Card Hasil Perhitungan -->
            <div class="glass-card rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Hasil Rekomendasi</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-1">Lihat</h3>
                        </div>
                        <div class="p-3 rounded-xl bg-amber-100 text-amber-600">
                            <i class="fas fa-calculator text-2xl"></i>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('perhitungan.rekomendasi') }}"
                            class="text-sm font-medium text-amber-600 hover:text-amber-500 flex items-center">
                            Lihat detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-amber-50 to-white h-1.5 w-full"></div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Chart Status Santri -->
            <div class="glass-card rounded-2xl p-6 shadow-xl">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Status Santri</h3>
                <div class="relative h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Chart Top 5 Santri -->
            <div class="glass-card rounded-2xl p-6 shadow-xl">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Top 5 Santri (SMART Score)</h3>
                <div class="relative h-64">
                    <canvas id="topSantriChart"></canvas>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <div class="glass-card rounded-2xl p-6 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">Tentang Aplikasi</h2>
                <div
                    class="h-1 flex-1 bg-gradient-to-r from-[var(--gradient-from)] to-[var(--gradient-to)] ml-4 rounded-full">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem Pendukung Keputusan (SPK) ini dibangun untuk membantu dalam menentukan keputusan kepulangan
                        santri
                        di Pondok Pesantren Annuqayah Latee II (P2AL II) dengan menggunakan metode Simple Multi Attribute
                        Rating
                        Technique (SMART). Sistem ini mempertimbangkan berbagai kriteria yang telah ditetapkan untuk
                        memberikan
                        rekomendasi keputusan yang objektif dan terukur.
                    </p>

                    <div class="mt-6">
                        <a href="#"
                            class="inline-flex items-center text-[var(--color-primary-600)] hover:text-[var(--color-primary-500)] font-medium">
                            Pelajari lebih lanjut
                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <span class="w-1.5 h-5 bg-[var(--color-primary-500)] rounded-full mr-2"></span>
                        Fitur Utama:
                    </h4>
                    <ul class="list-disc list-inside text-gray-600 mt-2 space-y-1">
                        <li>Manajemen Data Santri</li>
                        <li>Pengaturan Kriteria Penilaian</li>
                        <li>Perhitungan Metode SMART</li>
                        <li>Laporan Hasil Keputusan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart Status Santri (Doughnut)
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const santriStatus = @json($santriStatus);

            const statusLabels = Object.keys(santriStatus).map(s => s.charAt(0).toUpperCase() + s.slice(1));
            const statusData = Object.values(santriStatus);

            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: [
                            '#10b981', // emerald-500 (aktif)
                            '#ef4444', // red-500 (non-aktif)
                            '#3b82f6', // blue-500 (lulus)
                            '#f59e0b'  // amber-500 (drop-out)
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Chart Top Santri (Bar)
            const topCtx = document.getElementById('topSantriChart').getContext('2d');
            const topSantri = @json($topSantri);

            new Chart(topCtx, {
                type: 'bar',
                data: {
                    labels: topSantri.map(s => s.nama),
                    datasets: [{
                        label: 'Nilai Akhir',
                        data: topSantri.map(s => s.nilai_akhir),
                        backgroundColor: 'rgba(16, 185, 129, 0.6)',
                        borderColor: '#10b981',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1
                        }
                    }
                }
            });
        });
    </script>
@endsection