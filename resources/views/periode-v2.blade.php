@extends('layouts.app-v2-sidebar')

@section('title', 'Periode Penilaian - Santri Admin')
@section('mobile_title', 'Periode')
@section('breadcrumb', 'Periode Penilaian')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-wrap justify-between gap-4 items-end">
        <div class="flex flex-col gap-2 max-w-2xl">
            <h1 class="text-[#0d141b] dark:text-white text-3xl md:text-4xl font-black leading-tight tracking-tight">Periode
                Penilaian</h1>
            <p class="text-[#4c739a] text-base font-normal leading-normal">
                Kelola siklus penilaian kelayakan mudik santri. Periode aktif akan digunakan sebagai acuan perhitungan
                metode SAW saat ini.
            </p>
        </div>
        <button
            class="flex min-w-[140px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-5 bg-primary hover:bg-blue-600 transition-colors text-white text-sm font-bold shadow-sm">
            <span class="material-symbols-outlined text-[20px]">add</span>
            <span class="truncate">Tambah Periode</span>
        </button>
    </div>

    <!-- Active Period Section -->
    <div class="flex flex-col gap-4">
        <h2 class="text-[#0d141b] dark:text-white text-[20px] font-bold leading-tight">Periode Aktif</h2>
        <div
            class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-primary/20 shadow-[0_4px_20px_-4px_rgba(19,127,236,0.1)] relative overflow-hidden group">
            <!-- Decorative background element -->
            <div
                class="absolute -right-10 -top-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-all">
            </div>

            <div class="flex flex-col md:flex-row gap-6 items-start md:items-center justify-between relative z-10">
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-semibold text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            Aktif
                        </span>
                        <span class="text-[#4c739a] text-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            Tahun Ajaran 2023/2024
                        </span>
                    </div>
                    <div>
                        <h3 class="text-[#0d141b] dark:text-white text-xl md:text-2xl font-bold leading-tight mb-1">Mudik
                            Idul Fitri 1445H</h3>
                        <p class="text-[#4c739a] text-sm max-w-xl">
                            Penilaian kelayakan mudik untuk libur hari raya Idul Fitri. Termasuk kriteria hafalan,
                            kedisiplinan, dan administrasi.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto items-stretch">
                    <div
                        class="flex flex-col justify-center px-4 py-2 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-100 dark:border-slate-700">
                        <span class="text-xs text-[#4c739a] uppercase font-semibold tracking-wider">Mulai</span>
                        <span class="text-sm font-bold text-[#0d141b] dark:text-white">10 Mar 2024</span>
                    </div>
                    <div class="hidden sm:flex items-center text-slate-300">
                        <span class="material-symbols-outlined">arrow_right_alt</span>
                    </div>
                    <div
                        class="flex flex-col justify-center px-4 py-2 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-100 dark:border-slate-700">
                        <span class="text-xs text-[#4c739a] uppercase font-semibold tracking-wider">Selesai</span>
                        <span class="text-sm font-bold text-[#0d141b] dark:text-white">25 Mar 2024</span>
                    </div>
                    <button
                        class="flex items-center justify-center gap-2 rounded-lg h-full min-h-[44px] px-4 bg-white dark:bg-slate-700 border border-[#e7edf3] dark:border-slate-600 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-600 hover:text-primary transition-all md:ml-4">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- History Section -->
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <h2 class="text-[#0d141b] dark:text-white text-[20px] font-bold leading-tight">Riwayat Periode</h2>
            <!-- Search -->
            <div class="relative hidden sm:block">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-[#4c739a]">
                    <span class="material-symbols-outlined text-[20px]">search</span>
                </span>
                <input
                    class="pl-10 pr-4 py-2 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none w-64 transition-all"
                    placeholder="Cari periode..." type="text" />
            </div>
        </div>

        <div
            class="bg-white dark:bg-slate-800 rounded-xl border border-[#e7edf3] dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 dark:bg-slate-700/50 border-b border-[#e7edf3] dark:border-slate-700 text-xs uppercase tracking-wider text-[#4c739a] font-semibold">
                            <th class="px-6 py-4">Nama Periode</th>
                            <th class="px-6 py-4">Durasi</th>
                            <th class="px-6 py-4">Tahun Ajaran</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <!-- Row 1 -->
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-[#0d141b] dark:text-white">Liburan Semester
                                        Ganjil</span>
                                    <span class="text-xs text-[#4c739a] truncate max-w-[200px]">Evaluasi akhir semester
                                        satu.</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm text-[#0d141b] dark:text-white font-medium">15 Des - 30 Des</span>
                                    <span class="text-xs text-[#4c739a]">2023</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#4c739a]">2023/2024</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-0.5 text-xs font-medium text-[#4c739a]">
                                    Non-Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <button
                                        class="text-xs font-semibold text-primary hover:text-primary/80 hover:underline px-2">
                                        Aktifkan
                                    </button>
                                    <button
                                        class="p-1.5 hover:bg-slate-200 dark:hover:bg-slate-600 rounded text-[#4c739a] hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button
                                        class="p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded text-[#4c739a] hover:text-red-600 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Row 2 -->
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-[#0d141b] dark:text-white">Mudik Idul Adha
                                        1444H</span>
                                    <span class="text-xs text-[#4c739a] truncate max-w-[200px]">Periode khusus libur
                                        qurban.</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm text-[#0d141b] dark:text-white font-medium">25 Jun - 05 Jul</span>
                                    <span class="text-xs text-[#4c739a]">2023</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#4c739a]">2022/2023</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-0.5 text-xs font-medium text-[#4c739a]">
                                    Non-Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <button
                                        class="text-xs font-semibold text-primary hover:text-primary/80 hover:underline px-2">
                                        Aktifkan
                                    </button>
                                    <button
                                        class="p-1.5 hover:bg-slate-200 dark:hover:bg-slate-600 rounded text-[#4c739a] hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button
                                        class="p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded text-[#4c739a] hover:text-red-600 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Row 3 -->
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-[#0d141b] dark:text-white">Liburan Ramadhan
                                        1444H</span>
                                    <span class="text-xs text-[#4c739a] truncate max-w-[200px]">Libur puasa dan lebaran
                                        tahun lalu.</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm text-[#0d141b] dark:text-white font-medium">01 Apr - 20 Apr</span>
                                    <span class="text-xs text-[#4c739a]">2023</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#4c739a]">2022/2023</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-700 px-2.5 py-0.5 text-xs font-medium text-[#4c739a]">
                                    Non-Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <button
                                        class="text-xs font-semibold text-primary hover:text-primary/80 hover:underline px-2">
                                        Aktifkan
                                    </button>
                                    <button
                                        class="p-1.5 hover:bg-slate-200 dark:hover:bg-slate-600 rounded text-[#4c739a] hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <button
                                        class="p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded text-[#4c739a] hover:text-red-600 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700 flex items-center justify-between">
                <span class="text-sm text-[#4c739a]">Menampilkan 1-3 dari 12 periode</span>
                <div class="flex gap-2">
                    <button
                        class="px-3 py-1 text-sm rounded border border-[#e7edf3] dark:border-slate-700 text-[#4c739a] disabled:opacity-50 hover:bg-slate-50 dark:hover:bg-slate-700"
                        disabled>Sebelumnya</button>
                    <button
                        class="px-3 py-1 text-sm rounded border border-[#e7edf3] dark:border-slate-700 text-[#0d141b] dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700">Selanjutnya</button>
                </div>
            </div>
        </div>
    </div>
@endsection