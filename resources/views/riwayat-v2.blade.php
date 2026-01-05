@extends('layouts.app-v2-sidebar')

@section('title', 'Riwayat Perhitungan - Santri Admin')
@section('mobile_title', 'Riwayat')
@section('breadcrumb', 'Riwayat Perhitungan')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="flex flex-col gap-1">
            <h1 class="text-[#0d141b] dark:text-white text-3xl font-black tracking-tight">Riwayat Perhitungan</h1>
            <p class="text-[#4c739a] text-base font-normal">Arsip hasil perhitungan SAW berdasarkan periode penilaian</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-5">
        <form action="{{ route('riwayat-v2') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#4c739a]">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <input name="search" value="{{ request('search') }}"
                    class="block w-full p-2.5 pl-10 text-sm text-[#0d141b] dark:text-white bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-lg focus:ring-primary focus:border-primary placeholder-[#4c739a]"
                    placeholder="Cari nama/NIS santri..." type="text" />
            </div>
            <div class="relative w-full md:w-48">
                <select name="periode_filter"
                    class="block w-full p-2.5 text-sm text-[#0d141b] dark:text-white bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-lg focus:ring-primary focus:border-primary appearance-none cursor-pointer">
                    <option value="">Semua Periode</option>
                    @foreach($allPeriodes as $p)
                        <option value="{{ $p->id }}" {{ request('periode_filter') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} {{ $p->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-[#4c739a]">
                    <span class="material-symbols-outlined">expand_more</span>
                </div>
            </div>
            <button type="submit"
                class="h-[42px] px-4 bg-primary text-white rounded-lg text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">filter_list</span>
                Filter
            </button>
            @if(request('search') || request('periode_filter'))
                <a href="{{ route('riwayat-v2') }}"
                    class="h-[42px] px-4 bg-slate-100 dark:bg-slate-700 text-[#4c739a] rounded-lg text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">clear</span>
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Results grouped by Periode -->
    @forelse($periodesPaginated as $periode)
        <div
            class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
            <div
                class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">calendar_month</span>
                    <div>
                        <h3 class="font-semibold text-[#0d141b] dark:text-white">{{ $periode->nama }}</h3>
                        <p class="text-xs text-[#4c739a]">{{ $periode->keterangan ?? 'Periode penilaian' }}</p>
                    </div>
                </div>
                @if($periode->is_active)
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        Aktif
                    </span>
                @endif
            </div>

            @if($periode->riwayatHitung->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-[#e7edf3] dark:border-slate-700 text-xs uppercase tracking-wider text-[#4c739a]">
                                <th class="px-6 py-3 font-semibold">Nama Santri</th>
                                <th class="px-6 py-3 font-semibold">NIS</th>
                                <th class="px-6 py-3 font-semibold">Nilai Akhir</th>
                                <th class="px-6 py-3 font-semibold">Status</th>
                                <th class="px-6 py-3 font-semibold">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                            @foreach($periode->riwayatHitung as $r)
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
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-3 font-medium text-[#0d141b] dark:text-white">{{ $r->santri->nama ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-3 text-[#4c739a]">{{ $r->santri->nis ?? '-' }}</td>
                                    <td class="px-6 py-3 font-bold text-[#0d141b] dark:text-white">
                                        {{ number_format($nilai, 2, ',', '.') }}</td>
                                    <td class="px-6 py-3">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-[#4c739a] text-sm">{{ $r->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center text-[#4c739a]">
                    <p>Tidak ada data riwayat untuk periode ini</p>
                </div>
            @endif
        </div>
    @empty
        <div
            class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-8 text-center">
            <span class="material-symbols-outlined text-4xl text-[#4c739a] mb-2">history</span>
            <p class="text-[#4c739a]">Tidak ada riwayat perhitungan ditemukan</p>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($periodesPaginated->hasPages())
        <div class="flex justify-center">
            {{ $periodesPaginated->appends(request()->query())->links() }}
        </div>
    @endif
@endsection