@extends('layouts.app-v2-sidebar')

@section('title', 'Detail Perhitungan SAW - Santri Admin')
@section('mobile_title', 'Detail SAW')
@section('breadcrumb', 'Detail Perhitungan')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-start">
        <div class="flex flex-col gap-1">
            <h1 class="text-3xl font-black tracking-tight text-[#0d141b] dark:text-white sm:text-4xl">Detail Perhitungan SAW
            </h1>
            <p class="text-base text-[#4c739a]">Audit trail lengkap metode Simple Additive Weighting untuk rekomendasi
                santri.</p>
        </div>
        <a href="{{ route('rekomendasi-v2') }}"
            class="group flex items-center justify-center gap-2 rounded-lg bg-white dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 px-4 py-2 text-sm font-bold text-[#0d141b] dark:text-white shadow-sm transition-all hover:bg-slate-50 dark:hover:bg-slate-700">
            <span
                class="material-symbols-outlined text-[20px] transition-transform group-hover:-translate-x-1">arrow_back</span>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <!-- Santri Summary Card -->
    <div
        class="overflow-hidden rounded-xl bg-white dark:bg-slate-900 shadow-sm border border-[#e7edf3] dark:border-slate-700">
        <div class="grid gap-6 p-6 lg:grid-cols-3">
            <!-- Profile Section -->
            <div class="flex gap-4 lg:col-span-2">
                <div class="h-24 w-24 shrink-0 overflow-hidden rounded-lg bg-primary/20 flex items-center justify-center">
                    <span class="text-3xl font-bold text-primary">AF</span>
                </div>
                <div class="flex flex-col justify-center">
                    <div class="mb-1 flex items-center gap-2">
                        <h3 class="text-xl font-bold text-[#0d141b] dark:text-white">Ahmad Fulan</h3>
                        <span
                            class="rounded bg-slate-100 dark:bg-slate-700 px-2 py-0.5 text-xs font-semibold text-[#4c739a]">NIS:
                            12345</span>
                    </div>
                    <div class="space-y-1 text-sm text-[#4c739a]">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">school</span>
                            <span>Kelas 12 IPA - Asrama Al-Farabi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                            <span>Periode: Ramadhan 1445H</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Result Section -->
            <div
                class="flex flex-col items-center justify-center rounded-lg bg-primary/5 dark:bg-primary/10 border border-primary/10 p-4 text-center">
                <span class="text-sm font-medium text-primary uppercase tracking-wider mb-1">Nilai Akhir (V)</span>
                <span class="text-4xl font-black text-[#0d141b] dark:text-white tracking-tight mb-2">0.875</span>
                <div
                    class="flex items-center gap-1.5 rounded-full bg-green-100 dark:bg-green-900/30 px-3 py-1 text-xs font-bold text-green-700 dark:text-green-400">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    SANGAT DIREKOMENDASIKAN
                </div>
            </div>
        </div>
    </div>

    <!-- Step 1: Raw Data -->
    <div class="flex flex-col gap-4">
        <div class="flex items-center gap-2 px-1">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-[#0d141b] dark:bg-white text-white dark:text-[#0d141b] text-sm font-bold">
                1</div>
            <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Data Penilaian Awal (Raw Data)</h3>
        </div>
        <div
            class="overflow-hidden rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white">Kode</th>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white">Kriteria</th>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white">Atribut</th>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white text-right">Nilai Input (x)
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C1</td>
                            <td class="px-6 py-4 text-[#4c739a]">Akhlaq & Kedisiplinan</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/20 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-400 ring-1 ring-inset ring-blue-700/10">Benefit</span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-[#0d141b] dark:text-white">90</td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C2</td>
                            <td class="px-6 py-4 text-[#4c739a]">Hafalan Al-Quran</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/20 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-400 ring-1 ring-inset ring-blue-700/10">Benefit</span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-[#0d141b] dark:text-white">85</td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C3</td>
                            <td class="px-6 py-4 text-[#4c739a]">Kehadiran (Absensi)</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-full bg-blue-50 dark:bg-blue-900/20 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-400 ring-1 ring-inset ring-blue-700/10">Benefit</span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-[#0d141b] dark:text-white">100</td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C4</td>
                            <td class="px-6 py-4 text-[#4c739a]">Pelanggaran</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-full bg-orange-50 dark:bg-orange-900/20 px-2 py-1 text-xs font-medium text-orange-700 dark:text-orange-400 ring-1 ring-inset ring-orange-600/10">Cost</span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-[#0d141b] dark:text-white">5</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Step 2: Weights -->
    <div class="flex flex-col gap-4">
        <div class="flex items-center gap-2 px-1">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-[#0d141b] dark:bg-white text-white dark:text-[#0d141b] text-sm font-bold">
                2</div>
            <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Normalisasi Bobot (Weights)</h3>
        </div>
        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-900 p-4 shadow-sm">
                <p class="text-xs font-medium text-[#4c739a]">C1: Akhlaq</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-[#0d141b] dark:text-white">30%</span>
                    <span class="text-sm font-mono text-[#4c739a]">(0.30)</span>
                </div>
                <div class="mt-2 h-1.5 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                    <div class="h-1.5 rounded-full bg-primary" style="width: 30%"></div>
                </div>
            </div>
            <div class="rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-900 p-4 shadow-sm">
                <p class="text-xs font-medium text-[#4c739a]">C2: Hafalan</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-[#0d141b] dark:text-white">25%</span>
                    <span class="text-sm font-mono text-[#4c739a]">(0.25)</span>
                </div>
                <div class="mt-2 h-1.5 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                    <div class="h-1.5 rounded-full bg-purple-500" style="width: 25%"></div>
                </div>
            </div>
            <div class="rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-900 p-4 shadow-sm">
                <p class="text-xs font-medium text-[#4c739a]">C3: Kehadiran</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-[#0d141b] dark:text-white">20%</span>
                    <span class="text-sm font-mono text-[#4c739a]">(0.20)</span>
                </div>
                <div class="mt-2 h-1.5 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                    <div class="h-1.5 rounded-full bg-indigo-500" style="width: 20%"></div>
                </div>
            </div>
            <div class="rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-900 p-4 shadow-sm">
                <p class="text-xs font-medium text-[#4c739a]">C4: Pelanggaran</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-[#0d141b] dark:text-white">25%</span>
                    <span class="text-sm font-mono text-[#4c739a]">(0.25)</span>
                </div>
                <div class="mt-2 h-1.5 w-full rounded-full bg-slate-100 dark:bg-slate-800">
                    <div class="h-1.5 rounded-full bg-orange-500" style="width: 25%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 3: Normalization Logic -->
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-[#0d141b] dark:bg-white text-white dark:text-[#0d141b] text-sm font-bold">
                    3</div>
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Normalisasi Nilai (Matrix R)</h3>
            </div>
            <div class="hidden md:flex gap-4 text-xs text-[#4c739a]">
                <div class="flex items-center gap-1.5">
                    <div class="h-2 w-2 rounded-full bg-blue-500"></div> Benefit: x / Max
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="h-2 w-2 rounded-full bg-orange-500"></div> Cost: Min / x
                </div>
            </div>
        </div>
        <div
            class="overflow-hidden rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white">Kriteria</th>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white text-center">Nilai Santri</th>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white text-center">Max/Min (Global)
                            </th>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white">Rumus</th>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white text-right">Hasil (r)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C1 (Benefit)</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">90</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">100 (Max)</td>
                            <td class="px-6 py-4 text-xs font-mono text-[#4c739a]">90 / 100</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-primary">0.900</td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C2 (Benefit)</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">85</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">100 (Max)</td>
                            <td class="px-6 py-4 text-xs font-mono text-[#4c739a]">85 / 100</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-primary">0.850</td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C3 (Benefit)</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">100</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">100 (Max)</td>
                            <td class="px-6 py-4 text-xs font-mono text-[#4c739a]">100 / 100</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-primary">1.000</td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C4 (Cost)</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">5</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">2 (Min)</td>
                            <td class="px-6 py-4 text-xs font-mono text-[#4c739a]">2 / 5</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-orange-600 dark:text-orange-400">0.400
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Step 4: Final Calculation -->
    <div class="flex flex-col gap-4">
        <div class="flex items-center gap-2 px-1">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-[#0d141b] dark:bg-white text-white dark:text-[#0d141b] text-sm font-bold">
                4</div>
            <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Perhitungan Akhir (Utility × Bobot)</h3>
        </div>
        <div
            class="overflow-hidden rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white">Kriteria</th>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white text-center">Nilai Normalisasi
                                (r)</th>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white text-center">Bobot (w)</th>
                            <th class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C1</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">0.900</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">0.30</td>
                            <td class="px-6 py-4 text-right font-mono text-[#0d141b] dark:text-white">0.270</td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C2</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">0.850</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">0.25</td>
                            <td class="px-6 py-4 text-right font-mono text-[#0d141b] dark:text-white">0.2125</td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C3</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">1.000</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">0.20</td>
                            <td class="px-6 py-4 text-right font-mono text-[#0d141b] dark:text-white">0.200</td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">C4</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">0.400</td>
                            <td class="px-6 py-4 text-center font-mono text-[#4c739a]">0.25</td>
                            <td class="px-6 py-4 text-right font-mono text-[#0d141b] dark:text-white">0.100</td>
                        </tr>
                        <!-- Total Row -->
                        <tr class="bg-primary/5 dark:bg-primary/10">
                            <td class="px-6 py-4 text-right font-bold text-[#0d141b] dark:text-white" colspan="3">Total
                                Nilai Preferensi (V):</td>
                            <td class="px-6 py-4 text-right font-mono text-lg font-black text-primary">0.7825</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p class="px-1 text-xs text-[#4c739a] italic">* Nilai total mungkin sedikit berbeda karena pembulatan desimal pada
            tampilan.</p>
    </div>

    <!-- Action Footer -->
    <div class="flex justify-end gap-3 pt-6">
        <button
            class="flex cursor-pointer items-center justify-center rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2 text-sm font-medium text-[#0d141b] dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
            <span class="material-symbols-outlined mr-2 text-[18px]">print</span>
            Cetak Laporan
        </button>
        <button
            class="flex cursor-pointer items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-bold text-white shadow-md hover:bg-blue-600 transition-colors">
            <span class="material-symbols-outlined mr-2 text-[18px]">edit</span>
            Edit Penilaian
        </button>
    </div>
@endsection