@extends('layouts.app')

@section('title', 'Input Penilaian - Santri Admin')
@section('mobile_title', 'Input Penilaian')
@section('breadcrumb', 'Input Penilaian')

@section('content')
    <!-- Page Heading -->
    <div
        class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 rounded-2xl p-6 border border-primary/20 mb-6">
        <div class="flex flex-col gap-1">
            <h1 class="text-3xl font-black tracking-tight text-[#0d141b] dark:text-white">Input Penilaian Santri</h1>
            <p class="text-[#4c739a] text-base">Isi formulir penilaian objektif untuk rekomendasi kepulangan santri periode
                ini.</p>
        </div>
    </div>

    <!-- Main Form Card -->
    <!-- Main Form Card -->
    <form action="{{ route('perhitungan.hitung') }}" method="POST"
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
        @csrf
        <!-- Section 1: Data Identitas -->
        <div class="p-6 md:p-8 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-lg font-bold text-[#0d141b] dark:text-white mb-6 flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <span class="material-symbols-outlined text-[20px]">person_search</span>
                </span>
                Identitas & Periode
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Santri Searchable Select -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-[#0d141b] dark:text-white" for="santri-select">
                        Nama Santri <span class="text-red-500">*</span>
                    </label>
                    <select id="santri-select" name="santri_id" required placeholder="Pilih Santri...">
                        <option value="">-- Pilih Santri --</option>
                        @foreach($santri as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->nis }})</option>
                        @endforeach
                    </select>
                </div>
                <!-- Periode Select -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-[#0d141b] dark:text-white" for="periode">
                        Periode Penilaian <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select
                            class="block w-full appearance-none rounded-lg border-[#e7edf3] dark:border-slate-700 bg-[#f6f7f8] dark:bg-slate-800 py-3 pl-3 pr-10 text-sm text-[#0d141b] dark:text-white focus:border-primary focus:ring-1 focus:ring-primary shadow-sm cursor-not-allowed"
                            id="periode" disabled style="background-image: none;">
                            @if($activePeriode)
                                <option value="{{ $activePeriode->id }}" selected>{{ $activePeriode->nama }} (Aktif)</option>
                            @else
                                <option value="" selected>Tidak ada periode aktif</option>
                            @endif
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-[#4c739a]">
                            <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Kriteria Penilaian -->
        <div class="p-6 md:p-8 bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-lg font-bold text-[#0d141b] dark:text-white mb-6 flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <span class="material-symbols-outlined text-[20px]">fact_check</span>
                </span>
                Kriteria Penilaian
            </h3>
            <div class="space-y-6">
                @foreach($kriteria as $index => $k)
                    <div
                        class="rounded-xl border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-900 p-5 transition-all hover:shadow-md">
                        <div class="mb-4 flex items-center justify-between">
                            <label class="text-base font-semibold text-[#0d141b] dark:text-white flex items-center gap-2">
                                {{ $index + 1 }}. {{ $k->nama_kriteria }}
                                <span
                                    class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-700/30">Bobot:
                                    {{ $k->bobot }}%</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                            <div class="md:col-span-3">
                                <label class="block text-xs font-medium text-[#4c739a] mb-1">Pilih Subkriteria</label>
                                <select name="nilai[{{ $k->id }}]" required
                                    class="block w-full rounded-lg border-[#e7edf3] dark:border-slate-700 py-2.5 text-sm text-[#0d141b] dark:text-white dark:bg-slate-800 focus:border-primary focus:ring-1 focus:ring-primary">
                                    <option disabled selected value="">Pilih kondisi...</option>
                                    @foreach($k->subkriteria as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->nama_subkriteria }} (Nilai: {{ $sub->nilai }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Footer -->
        <div
            class="flex items-center justify-end gap-3 p-6 md:px-8 border-t border-[#e7edf3] dark:border-slate-800 bg-white dark:bg-slate-900">
            <a href="{{ route('penilaian.index') }}"
                class="rounded-lg border border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-transparent px-5 py-2.5 text-sm font-semibold text-[#0d141b] dark:text-white shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                Batal
            </a>
            <button
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 transition-all"
                type="submit" {{ !$activePeriode ? 'disabled' : '' }}>
                <span class="material-symbols-outlined text-[20px]">save</span>
                Simpan Data
            </button>
        </div>
    </form>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect("#santri-select", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: 'Cari nama santri...'
            });
        });
    </script>
@endsection