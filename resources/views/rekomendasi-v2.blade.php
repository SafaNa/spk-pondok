@extends('layouts.app-v2-sidebar')

@section('title', 'Hasil Rekomendasi SAW - Santri Admin')
@section('mobile_title', 'Rekomendasi SAW')
@section('breadcrumb', 'Rekomendasi SAW')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight text-[#0d141b] dark:text-white">Hasil Rekomendasi SAW
            </h1>
            <p class="mt-2 text-[#4c739a] max-w-2xl">
                Daftar peringkat kepulangan santri berdasarkan perhitungan algoritma Simple Additive Weighting untuk periode
                aktif.
            </p>
        </div>
        <div class="flex gap-3">
            <button
                class="flex items-center justify-center gap-2 h-10 px-4 bg-white dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 text-[#0d141b] dark:text-white text-sm font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[20px]">history</span>
                <span>Riwayat Perhitungan</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
            class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-[#e7edf3] dark:border-slate-800 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-[#4c739a] text-sm font-medium">Total Santri Dinilai</p>
                <p class="text-3xl font-bold text-[#0d141b] dark:text-white mt-2">1,250</p>
                <p class="text-xs text-emerald-600 mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    +5% dari tahun lalu
                </p>
            </div>
            <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-primary">
                <span class="material-symbols-outlined">groups</span>
            </div>
        </div>
        <div
            class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-[#e7edf3] dark:border-slate-800 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-[#4c739a] text-sm font-medium">Direkomendasikan</p>
                <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">850</p>
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full" style="width: 68%"></div>
                </div>
            </div>
            <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
        </div>
        <div
            class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-[#e7edf3] dark:border-slate-800 shadow-sm flex items-start justify-between">
            <div>
                <p class="text-[#4c739a] text-sm font-medium">Tidak Direkomendasikan</p>
                <p class="text-3xl font-bold text-rose-600 dark:text-rose-400 mt-2">400</p>
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-rose-500 h-full rounded-full" style="width: 32%"></div>
                </div>
            </div>
            <div class="p-2 bg-rose-50 dark:bg-rose-900/20 rounded-lg text-rose-600 dark:text-rose-400">
                <span class="material-symbols-outlined">cancel</span>
            </div>
        </div>
    </div>

    <!-- Main Data Section -->
    <div
        class="bg-white dark:bg-slate-900 border border-[#e7edf3] dark:border-slate-800 rounded-xl shadow-sm overflow-hidden flex flex-col">
        <!-- Toolbar -->
        <div
            class="p-4 border-b border-[#e7edf3] dark:border-slate-800 flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center bg-slate-50/50 dark:bg-slate-900/50">
            <!-- Search & Filter -->
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <div class="relative group">
                    <span
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-[#4c739a] group-focus-within:text-primary transition-colors material-symbols-outlined text-[20px]">search</span>
                    <input
                        class="pl-10 pr-4 py-2 w-full sm:w-64 bg-white dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-lg text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                        placeholder="Cari Nama atau NIS..." type="text" />
                </div>
                <div class="relative">
                    <span
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-[#4c739a] material-symbols-outlined text-[20px]">calendar_month</span>
                    <select
                        class="pl-10 pr-8 py-2 w-full sm:w-48 bg-white dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-lg text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary appearance-none cursor-pointer">
                        <option>Ramadhan 1445H</option>
                        <option>Syawal 1445H</option>
                        <option>Dzulhijjah 1444H</option>
                    </select>
                    <span
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#4c739a] material-symbols-outlined text-[20px] pointer-events-none">expand_more</span>
                </div>
            </div>
            <!-- Actions -->
            <div class="flex gap-3 w-full lg:w-auto">
                <button
                    class="flex-1 lg:flex-none flex items-center justify-center gap-2 h-10 px-4 bg-white dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 text-[#0d141b] dark:text-white text-sm font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
                    <span>Export PDF</span>
                </button>
                <button
                    class="flex-1 lg:flex-none flex items-center justify-center gap-2 h-10 px-5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-blue-600 transition-colors shadow-md shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">calculate</span>
                    <span>Hitung Ulang</span>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-[#4c739a] w-16 text-center">#</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-[#4c739a] w-32">NIS</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-[#4c739a]">Nama Santri</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-[#4c739a] w-32 text-right">Nilai
                            Akhir</th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-[#4c739a] w-48 text-center">Status
                        </th>
                        <th class="p-4 text-xs font-bold uppercase tracking-wider text-[#4c739a] w-24 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <!-- Row 1 -->
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4 text-center font-medium text-[#4c739a] group-hover:text-primary">1</td>
                        <td class="p-4 font-mono text-sm text-[#4c739a]">14450023</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold text-xs">
                                    MR</div>
                                <div class="font-semibold text-[#0d141b] dark:text-white">Muhammad Rizki</div>
                            </div>
                        </td>
                        <td class="p-4 text-right font-bold text-[#0d141b] dark:text-white text-base">0.985</td>
                        <td class="p-4 text-center">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                Direkomendasikan
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <button
                                class="p-2 text-[#4c739a] hover:text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                title="Lihat Detail">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </td>
                    </tr>
                    <!-- Row 2 -->
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4 text-center font-medium text-[#4c739a] group-hover:text-primary">2</td>
                        <td class="p-4 font-mono text-sm text-[#4c739a]">14450089</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                    AF</div>
                                <div class="font-semibold text-[#0d141b] dark:text-white">Ahmad Fauzi</div>
                            </div>
                        </td>
                        <td class="p-4 text-right font-bold text-[#0d141b] dark:text-white text-base">0.942</td>
                        <td class="p-4 text-center">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                                Direkomendasikan
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <button
                                class="p-2 text-[#4c739a] hover:text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                title="Lihat Detail">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </td>
                    </tr>
                    <!-- Row 3 -->
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4 text-center font-medium text-[#4c739a] group-hover:text-primary">3</td>
                        <td class="p-4 font-mono text-sm text-[#4c739a]">14450112</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                    SP</div>
                                <div class="font-semibold text-[#0d141b] dark:text-white">Siti Putri</div>
                            </div>
                        </td>
                        <td class="p-4 text-right font-bold text-[#0d141b] dark:text-white text-base">0.880</td>
                        <td class="p-4 text-center">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                <span class="size-1.5 rounded-full bg-amber-500"></span>
                                Pertimbangkan
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <button
                                class="p-2 text-[#4c739a] hover:text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                title="Lihat Detail">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </td>
                    </tr>
                    <!-- Row 4 -->
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4 text-center font-medium text-[#4c739a] group-hover:text-primary">4</td>
                        <td class="p-4 font-mono text-sm text-[#4c739a]">14450045</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xs">
                                    BS</div>
                                <div class="font-semibold text-[#0d141b] dark:text-white">Budi Santoso</div>
                            </div>
                        </td>
                        <td class="p-4 text-right font-bold text-[#0d141b] dark:text-white text-base">0.650</td>
                        <td class="p-4 text-center">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                <span class="size-1.5 rounded-full bg-amber-500"></span>
                                Pertimbangkan
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <button
                                class="p-2 text-[#4c739a] hover:text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                title="Lihat Detail">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </td>
                    </tr>
                    <!-- Row 5 -->
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4 text-center font-medium text-[#4c739a] group-hover:text-primary">5</td>
                        <td class="p-4 font-mono text-sm text-[#4c739a]">14450210</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-xs">
                                    DA</div>
                                <div class="font-semibold text-[#0d141b] dark:text-white">Doni Aryanto</div>
                            </div>
                        </td>
                        <td class="p-4 text-right font-bold text-[#0d141b] dark:text-white text-base">0.420</td>
                        <td class="p-4 text-center">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20">
                                <span class="size-1.5 rounded-full bg-rose-500"></span>
                                Tidak Direkomendasikan
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <button
                                class="p-2 text-[#4c739a] hover:text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                title="Lihat Detail">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            class="p-4 border-t border-[#e7edf3] dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-[#4c739a]">
                Menampilkan <span class="font-medium text-[#0d141b] dark:text-white">1</span> sampai <span
                    class="font-medium text-[#0d141b] dark:text-white">5</span> dari <span
                    class="font-medium text-[#0d141b] dark:text-white">1,250</span> santri
            </p>
            <div class="flex gap-2">
                <button
                    class="px-3 py-1 text-sm border border-[#e7edf3] dark:border-slate-700 rounded-md text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-800 disabled:opacity-50"
                    disabled>Previous</button>
                <button class="px-3 py-1 text-sm bg-primary text-white rounded-md">1</button>
                <button
                    class="px-3 py-1 text-sm border border-[#e7edf3] dark:border-slate-700 rounded-md text-[#0d141b] dark:text-white hover:bg-slate-50 dark:hover:bg-slate-800">2</button>
                <button
                    class="px-3 py-1 text-sm border border-[#e7edf3] dark:border-slate-700 rounded-md text-[#0d141b] dark:text-white hover:bg-slate-50 dark:hover:bg-slate-800">3</button>
                <span class="px-2 text-[#4c739a]">...</span>
                <button
                    class="px-3 py-1 text-sm border border-[#e7edf3] dark:border-slate-700 rounded-md text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-800">Next</button>
            </div>
        </div>
    </div>
@endsection