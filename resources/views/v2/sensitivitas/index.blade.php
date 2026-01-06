@extends('layouts.app')

@section('title', 'Analisis Sensitivitas - Santri Admin')
@section('mobile_title', 'Sensitivitas')
@section('breadcrumb', 'Analisis Sensitivitas')

@section('content')
    <!-- Page Heading -->
    <!-- Page Heading -->
    <div class="bg-blue-50 rounded-2xl p-4 sm:p-6 border border-blue-200 mb-6">
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-black tracking-tight text-[#0d141b] dark:text-white">Sensitivity
                Analysis</h1>
            <p class="text-sm sm:text-base font-normal text-[#4c739a]">Evaluate how changes in criteria weights impact
                santri ranking recommendations
                for homecoming.</p>
        </div>
    </div>

    <!-- Weight Adjustment Card -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
        <div
            class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">tune</span>
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Atur Bobot Simulasi</h3>
            </div>
            <button type="button" onclick="resetWeights()"
                class="text-sm font-medium text-[#4c739a] hover:text-primary transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                Reset Default
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm text-[#4c739a] mb-6">Adjust the sliders below to simulate different weight distributions for
                the SAW criteria. The total weight impact will be calculated dynamically.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                @foreach($kriteria as $k)
                    <div class="weight-slider-container">
                        <div class="flex justify-between mb-2">
                            <label class="text-sm font-medium text-[#0d141b] dark:text-white">{{ $k->kode_kriteria }} -
                                {{ $k->nama_kriteria }}</label>
                            <span class="text-sm font-bold text-primary bg-primary/10 px-2 py-0.5 rounded weight-display"
                                data-id="{{ $k->id }}">{{ $k->bobot }}%</span>
                        </div>
                        <input
                            class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer dark:bg-slate-700 accent-primary weight-slider"
                            data-id="{{ $k->id }}" data-original="{{ $k->bobot }}" max="100" min="0" type="range"
                            value="{{ $k->bobot }}" oninput="updateWeight(this)" />
                        <p class="text-xs text-[#4c739a] mt-1">{{ $k->jenis == 'benefit' ? 'Benefit' : 'Cost' }} | Original:
                            {{ $k->bobot }}%
                        </p>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-[#4c739a]">Total Bobot Simulasi:</span>
                    <span id="totalSimBobot"
                        class="text-xl font-bold text-[#0d141b] dark:text-white">{{ $kriteria->sum('bobot') }}%</span>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-[#e7edf3] dark:border-slate-700 flex justify-end">
                <button onclick="runSimulation()"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-primary hover:bg-blue-600 text-sm font-semibold text-white shadow-sm transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">play_arrow</span>
                    Jalankan Simulasi
                </button>
            </div>
        </div>
    </div>

    <!-- Current Rankings (from DB) -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
        <div
            class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">analytics</span>
                <div>
                    <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Peringkat Saat Ini</h3>
                    <p class="text-xs text-[#4c739a]">Top 10 santri berdasarkan periode {{ $periode->nama ?? 'aktif' }}</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead
                    class="text-xs text-[#4c739a] uppercase bg-slate-50 dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap" scope="col">Rank</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap" scope="col">Nama Santri</th>
                        <th class="px-6 py-4 font-semibold text-center whitespace-nowrap" scope="col">Nilai Akhir</th>
                        <th class="px-6 py-4 font-semibold text-center whitespace-nowrap" scope="col">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($riwayat as $index => $r)
                        @php
                            $nilai = $r->nilai_akhir;
                            if ($nilai >= 0.7) {
                                $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                                $statusText = 'Direkomendasikan';
                            } elseif ($nilai >= 0.4) {
                                $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
                                $statusText = 'Dipertimbangkan';
                            } else {
                                $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                                $statusText = 'Tidak Direk.';
                            }
                        @endphp
                        <tr class="bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4">
                                @if($index < 3)
                                    <span
                                        class="flex items-center justify-center w-8 h-8 rounded-full {{ $index == 0 ? 'bg-amber-100 text-amber-600' : ($index == 1 ? 'bg-slate-200 text-slate-600' : 'bg-orange-100 text-orange-600') }} font-bold text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                @else
                                    <span class="text-[#4c739a] font-medium pl-2">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="size-9 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($r->santri->nama ?? 'NA', 0, 2)) }}
                                    </div>
                                    <span>{{ $r->santri->nama ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-[#0d141b] dark:text-white">
                                {{ number_format($nilai, 3, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-[#4c739a]">
                                <span class="material-symbols-outlined text-4xl mb-2">analytics</span>
                                <p>Belum ada data perhitungan untuk periode ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const originalWeights = {};

        document.querySelectorAll('.weight-slider').forEach(slider => {
            originalWeights[slider.dataset.id] = parseFloat(slider.dataset.original);
        });

        function updateWeight(slider) {
            const id = slider.dataset.id;
            const value = slider.value;
            document.querySelector(`.weight-display[data-id="${id}"]`).textContent = value + '%';
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.weight-slider').forEach(slider => {
                total += parseFloat(slider.value);
            });
            document.getElementById('totalSimBobot').textContent = total + '%';
        }

        function resetWeights() {
            document.querySelectorAll('.weight-slider').forEach(slider => {
                const original = slider.dataset.original;
                slider.value = original;
                document.querySelector(`.weight-display[data-id="${slider.dataset.id}"]`).textContent = original + '%';
            });
            updateTotal();
        }

        function runSimulation() {
            alert('Fitur simulasi interaktif memerlukan AJAX. Untuk saat ini, silakan gunakan fitur Analisis Sensitivitas di halaman V1 untuk kalkulasi lengkap.');
        }
    </script>
@endsection