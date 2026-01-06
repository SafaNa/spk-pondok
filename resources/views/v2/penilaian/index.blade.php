@extends('layouts.app')

@section('title', 'Daftar Penilaian - Santri Admin')
@section('mobile_title', 'Penilaian')
@section('breadcrumb', 'Daftar Penilaian')

@section('content')
    <!-- Page Header & Main Actions -->
    <!-- Page Header & Main Actions -->
    <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Manajemen
                    Penilaian Santri</h1>
                <p class="text-[#4c739a] text-sm sm:text-base font-normal">
                    Daftar hasil penilaian santri berdasarkan metode SAW untuk rekomendasi kepulangan.
                </p>
            </div>
            <a href="{{ route('penilaian.create') }}"
                class="group flex shrink-0 cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-xl h-11 px-6 bg-primary hover:bg-primary/90 hover:shadow-xl hover:shadow-primary/30 text-white text-sm font-bold leading-normal tracking-[0.015em] transition-all transform hover:-translate-y-0.5">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                <span class="truncate">Tambah Penilaian</span>
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div
            class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filters & Search Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 p-5">
        <div class="flex flex-col lg:flex-row gap-4 justify-between items-end lg:items-center">
            <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto">
                <!-- Search -->
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#4c739a]">
                        <span class="material-symbols-outlined">search</span>
                    </div>
                    <input
                        class="block w-full p-2.5 pl-10 text-sm text-[#0d141b] dark:text-white bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-lg focus:ring-primary focus:border-primary placeholder-[#4c739a]"
                        placeholder="Cari nama santri..." type="text" />
                </div>
                <!-- Filter Periode -->
                <div class="relative w-full md:w-48">
                    <select
                        class="block w-full p-2.5 text-sm text-[#0d141b] dark:text-white bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-lg focus:ring-primary focus:border-primary appearance-none cursor-pointer">
                        <option selected value="">Semua Periode</option>
                        @foreach($periodes as $periode)
                            <option value="{{ $periode->id }}">{{ $periode->nama }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-[#4c739a]">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                </div>
                <!-- Filter Santri -->
                <div class="relative w-full md:w-48">
                    <select
                        class="block w-full p-2.5 text-sm text-[#0d141b] dark:text-white bg-[#f6f7f8] dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 rounded-lg focus:ring-primary focus:border-primary appearance-none cursor-pointer">
                        <option selected value="">Semua Santri</option>
                        @foreach($santriList as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-[#4c739a]">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-[#4c739a]">
                <thead
                    class="text-xs uppercase bg-[#f6f7f8] dark:bg-slate-800 text-[#0d141b] dark:text-white border-b border-[#e7edf3] dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-bold tracking-wider whitespace-nowrap" scope="col">Santri Name</th>
                        <th class="px-6 py-4 font-bold tracking-wider whitespace-nowrap" scope="col">Kriteria</th>
                        <th class="px-6 py-4 font-bold tracking-wider whitespace-nowrap" scope="col">Subkriteria</th>
                        <th class="px-6 py-4 font-bold tracking-wider whitespace-nowrap" scope="col">Nilai</th>
                        <th class="px-6 py-4 font-bold tracking-wider text-right whitespace-nowrap" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @forelse($penilaian as $p)
                        @php
                            $initials = strtoupper(substr($p->santri->nama ?? 'NA', 0, 1) . substr($p->santri->nama ?? 'NA', strpos($p->santri->nama ?? 'NA', ' ') + 1 ?: 1, 1));
                            $colors = ['primary', 'purple', 'orange', 'teal', 'pink', 'indigo'];
                            $colorIndex = crc32($p->santri_id ?? '') % count($colors);
                            $color = $colors[$colorIndex];
                        @endphp
                        <tr
                            class="bg-white dark:bg-slate-900 hover:bg-[#f6f7f8] dark:hover:bg-slate-800 transition-colors group">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-8 w-8 rounded-full bg-{{ $color }}/20 text-{{ $color }} flex items-center justify-center text-xs font-bold">
                                        {{ $initials }}
                                    </div>
                                    <span>{{ $p->santri->nama ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="text-xs font-semibold text-[#0d141b] dark:text-white">{{ $p->kriteria->nama_kriteria ?? 'N/A' }}</span>
                                <span class="text-xs opacity-75 ml-1">({{ $p->kriteria->kode_kriteria ?? '' }})</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs">{{ $p->subkriteria->nama ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="font-bold text-[#0d141b] dark:text-white">{{ $p->nilai ?? $p->subkriteria->nilai ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <button
                                        class="p-2 text-[#4c739a] hover:text-primary bg-transparent hover:bg-primary/10 rounded-lg transition-colors"
                                        title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    <button
                                        class="p-2 text-[#4c739a] hover:text-red-600 bg-transparent hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                        title="Delete">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-[#4c739a]">
                                <span class="material-symbols-outlined text-4xl mb-2">fact_check</span>
                                <p>Belum ada data penilaian</p>
                                <a href="{{ route('penilaian.create') }}"
                                    class="text-primary hover:underline text-sm mt-2 inline-block">Tambah penilaian pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($penilaian->hasPages())
            <div
                class="bg-white dark:bg-slate-900 px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700 flex flex-col md:flex-row items-center justify-between gap-4">
                <span class="text-sm text-[#4c739a] text-center md:text-left">
                    Showing <span class="font-semibold text-[#0d141b] dark:text-white">{{ $penilaian->firstItem() }}</span> to
                    <span class="font-semibold text-[#0d141b] dark:text-white">{{ $penilaian->lastItem() }}</span> of
                    <span class="font-semibold text-[#0d141b] dark:text-white">{{ $penilaian->total() }}</span> entries
                </span>
                <div class="flex items-center gap-1">
                    @if($penilaian->onFirstPage())
                        <button disabled
                            class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] disabled:opacity-50">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </button>
                    @else
                        <a href="{{ $penilaian->previousPageUrl() }}"
                            class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700">
                            <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                        </a>
                    @endif

                    @foreach($penilaian->getUrlRange(max(1, $penilaian->currentPage() - 2), min($penilaian->lastPage(), $penilaian->currentPage() + 2)) as $page => $url)
                        @if($page == $penilaian->currentPage())
                            <span
                                class="flex items-center justify-center size-9 rounded-lg bg-primary text-white font-medium text-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700 font-medium text-sm">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($penilaian->hasMorePages())
                        <a href="{{ $penilaian->nextPageUrl() }}"
                            class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </a>
                    @else
                        <button disabled
                            class="flex items-center justify-center size-9 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] disabled:opacity-50">
                            <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection