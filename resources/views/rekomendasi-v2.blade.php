@extends('layouts.app-v2-sidebar')

@section('title', 'Rekomendasi SAW - Santri Admin')
@section('mobile_title', 'Rekomendasi')
@section('breadcrumb', 'Hasil Analisis SAW')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex flex-col gap-1">
            <h1 class="text-[#0d141b] dark:text-white text-3xl font-black tracking-tight">Hasil Analisis SAW</h1>
            <p class="text-[#4c739a] text-base font-normal">
                Rekomendasi kepulangan santri berdasarkan metode Simple Additive Weighting
                @if($periode)
                    <span class="inline-flex items-center gap-1 ml-2 text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full">
                        {{ $periode->nama }}
                    </span>
                @endif
            </p>
        </div>
        <a href="{{ route('perhitungan.cetak') }}" target="_blank"
            class="flex items-center gap-2 bg-white dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 text-[#0d141b] dark:text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
            <span class="material-symbols-outlined text-[20px]">print</span>
            Cetak Laporan
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-[#e7edf3] dark:border-slate-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20">
                    <span class="material-symbols-outlined text-blue-600">groups</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[#0d141b] dark:text-white">{{ $stats['total'] }}</p>
                    <p class="text-sm text-[#4c739a]">Total Dinilai</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-[#e7edf3] dark:border-slate-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-lg bg-green-50 dark:bg-green-900/20">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['recommended'] }}</p>
                    <p class="text-sm text-[#4c739a]">Direkomendasikan</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-[#e7edf3] dark:border-slate-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20">
                    <span class="material-symbols-outlined text-amber-600">pending</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-600">{{ $stats['consider'] }}</p>
                    <p class="text-sm text-[#4c739a]">Dipertimbangkan</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-[#e7edf3] dark:border-slate-800 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-lg bg-red-50 dark:bg-red-900/20">
                    <span class="material-symbols-outlined text-red-600">cancel</span>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['not_recommended'] }}</p>
                    <p class="text-sm text-[#4c739a]">Tidak Direk.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-16">Rank</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase">Nama Santri</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-32">Nilai Akhir</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-40">Status</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-24 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @forelse($riwayat as $index => $r)
                    @php
                        $rank = ($riwayat->currentPage() - 1) * $riwayat->perPage() + $index + 1;
                        $nilai = $r->nilai_akhir;
                        $scorePercent = min(100, $nilai * 100);
                        
                        if ($nilai >= 0.7) {
                            $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                            $statusText = 'Direkomendasikan';
                            $barColor = 'bg-green-500';
                        } elseif ($nilai >= 0.4) {
                            $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
                            $statusText = 'Dipertimbangkan';
                            $barColor = 'bg-amber-500';
                        } else {
                            $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                            $statusText = 'Tidak Direk.';
                            $barColor = 'bg-red-500';
                        }
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <td class="p-4">
                            @if($rank <= 3)
                            <span class="flex items-center justify-center w-8 h-8 rounded-full {{ $rank == 1 ? 'bg-amber-100 text-amber-600' : ($rank == 2 ? 'bg-slate-200 text-slate-600' : 'bg-orange-100 text-orange-600') }} font-bold text-sm">
                                {{ $rank }}
                            </span>
                            @else
                            <span class="text-[#4c739a] font-medium pl-2">{{ $rank }}</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($r->santri->nama ?? 'NA', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-[#0d141b] dark:text-white">{{ $r->santri->nama ?? 'N/A' }}</p>
                                    <p class="text-xs text-[#4c739a]">{{ $r->santri->nis ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                    <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $scorePercent }}%"></div>
                                </div>
                                <span class="font-bold text-[#0d141b] dark:text-white">{{ number_format($nilai, 2, ',', '.') }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <a href="{{ route('rekomendasi-detail-v2', $r->santri_id) }}" 
                                class="inline-flex items-center justify-center p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Lihat Detail">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-[#4c739a]">
                            <span class="material-symbols-outlined text-4xl mb-2">analytics</span>
                            <p>Belum ada data perhitungan</p>
                            <a href="{{ route('perhitungan.index') }}" class="text-primary hover:underline text-sm mt-2 inline-block">Mulai perhitungan</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($riwayat->hasPages())
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 border-t border-[#e7edf3] dark:border-slate-800">
            <p class="text-sm text-[#4c739a]">
                Showing <span class="font-medium text-[#0d141b] dark:text-white">{{ $riwayat->firstItem() }}</span> to 
                <span class="font-medium text-[#0d141b] dark:text-white">{{ $riwayat->lastItem() }}</span> of 
                <span class="font-medium text-[#0d141b] dark:text-white">{{ $riwayat->total() }}</span> entries
            </p>
            <div class="flex items-center gap-1">
                @if($riwayat->onFirstPage())
                <button disabled class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] disabled:opacity-50">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </button>
                @else
                <a href="{{ $riwayat->previousPageUrl() }}" class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </a>
                @endif
                
                @foreach($riwayat->getUrlRange(max(1, $riwayat->currentPage() - 2), min($riwayat->lastPage(), $riwayat->currentPage() + 2)) as $page => $url)
                @if($page == $riwayat->currentPage())
                <span class="flex items-center justify-center size-9 rounded-lg bg-primary text-white font-medium text-sm">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700 font-medium text-sm">{{ $page }}</a>
                @endif
                @endforeach
                
                @if($riwayat->hasMorePages())
                <a href="{{ $riwayat->nextPageUrl() }}" class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700">
                    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </a>
                @else
                <button disabled class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] disabled:opacity-50">
                    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </button>
                @endif
            </div>
        </div>
        @endif
    </div>
@endsection