@extends('layouts.app')

@section('title', 'Laporan Perizinan')
@section('breadcrumb', 'Laporan')
@section('breadcrumb_parent', 'Perizinan')
@section('breadcrumb_parent_route', 'admin.licenses.index')
@section('mobile_title', 'Laporan Perizinan')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Header & Filter --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Laporan Perizinan</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Ringkasan data perizinan santri bulan ini.</p>
            </div>
            <form method="GET" action="{{ route('admin.licenses.reports') }}" class="flex items-center gap-2">
                <input type="month" name="month" value="{{ $month }}"
                    class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 focus:border-primary focus:ring-1 focus:ring-primary shadow-sm"
                    onchange="this.form.submit()">
            </form>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Izin --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-center">
                <div class="flex items-center gap-3 text-slate-500 dark:text-slate-400 mb-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                        <span class="material-symbols-outlined text-[20px]">description</span>
                    </div>
                    <span class="text-sm font-semibold">Total Izin Disetujui</span>
                </div>
                <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $totalLicenses }}</div>
            </div>

            {{-- Kembali Tepat Waktu --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-center">
                <div class="flex items-center gap-3 text-slate-500 dark:text-slate-400 mb-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    </div>
                    <span class="text-sm font-semibold">Kembali Tepat Waktu</span>
                </div>
                <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $totalOnTime }}</div>
            </div>

            {{-- Belum Kembali --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-center">
                <div class="flex items-center gap-3 text-slate-500 dark:text-slate-400 mb-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                        <span class="material-symbols-outlined text-[20px]">hourglass_empty</span>
                    </div>
                    <span class="text-sm font-semibold">Sedang Izin Aktif</span>
                </div>
                <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $totalNotReturned }}</div>
            </div>

            {{-- Terlambat --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-center">
                <div class="flex items-center gap-3 text-slate-500 dark:text-slate-400 mb-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400">
                        <span class="material-symbols-outlined text-[20px]">warning</span>
                    </div>
                    <span class="text-sm font-semibold">Kasus Terlambat</span>
                </div>
                <div class="text-3xl font-bold text-slate-800 dark:text-white">{{ $totalLate }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Tabel Keterlambatan --}}
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">list_alt</span>
                            Daftar Seluruh Izin (Bulan Ini)
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar semua santri yang mengajukan izin pada bulan terpilih.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Santri</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Alasan</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jatuh Tempo</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status Kembali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($allLicenses as $license)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-200">
                                            <a href="{{ route('admin.licenses.show', $license->id) }}" class="hover:text-primary hover:underline">
                                                {{ $license->student->name }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-slate-500">{{ $license->student->rayon->name ?? '-' }} - {{ $license->student->room->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-700 dark:text-slate-300">{{ $license->leaveCategory->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $license->end_date->locale('id')->translatedFormat('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($license->actual_return_date)
                                            @if($license->is_late)
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">
                                                    <span class="material-symbols-outlined text-[14px]">warning</span>
                                                    Telat {{ $license->late_days }} hari
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                                    Tepat Waktu
                                                </span>
                                            @endif
                                            <div class="text-[11px] text-slate-400 mt-1">Kembali: {{ $license->actual_return_date->locale('id')->translatedFormat('d M Y') }}</div>
                                        @else
                                            @if(now()->startOfDay()->gt($license->end_date))
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">
                                                    <span class="material-symbols-outlined text-[14px]">warning</span>
                                                    Belum Kembali (Telat)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                                    <span class="material-symbols-outlined text-[14px]">hourglass_empty</span>
                                                    Sedang Izin
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-2">inbox</span>
                                            <p class="text-slate-500 dark:text-slate-400 font-medium">Belum ada data perizinan di bulan ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kategori Chart --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">pie_chart</span>
                        Sebaran Kategori Izin
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($categoryStats as $stat)
                            @php
                                $percent = $totalLicenses > 0 ? round(($stat->approved_count / $totalLicenses) * 100) : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $stat->name }}</span>
                                    <div class="text-right">
                                        <span class="text-sm font-bold text-slate-800 dark:text-white">{{ $stat->approved_count }}</span>
                                        <span class="text-xs text-slate-500 ml-1">({{ $percent }}%)</span>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-primary h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
