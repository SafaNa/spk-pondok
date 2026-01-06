@extends('layouts.app')

@section('title', isset($kriterium) ? 'Edit Kriteria - Santri Admin' : 'Tambah Kriteria - Santri Admin')
@section('mobile_title', isset($kriterium) ? 'Edit Kriteria' : 'Tambah Kriteria')
@section('breadcrumb', isset($kriterium) ? 'Edit Kriteria' : 'Tambah Kriteria')

@section('content')
    <!-- Page Heading -->
    <div
        class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 rounded-2xl p-6 border border-primary/20 mb-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-[#0d141b] dark:text-white tracking-tight">
                    {{ isset($kriterium) ? 'Edit Kriteria' : 'Create New Kriteria' }}
                </h2>
                <p class="text-[#4c739a] mt-1">
                    {{ isset($kriterium) ? 'Update existing criteria parameters.' : 'Define new criteria parameters for the SAW calculation method.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if($errors->any())
        <div
            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
        <form action="{{ isset($kriterium) ? route('kriteria.update', $kriterium->id) : route('kriteria.store') }}"
            method="POST" class="p-6 md:p-8 space-y-8">
            @csrf
            @isset($kriterium)
                @method('PUT')
            @endisset

            <!-- Top Row: Kode & Nama -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Kode Kriteria -->
                <div class="md:col-span-3 space-y-2">
                    <label class="block text-sm font-medium text-[#0d141b] dark:text-white">
                        Kode Kriteria <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input name="kode_kriteria" value="{{ old('kode_kriteria', $kriterium->kode_kriteria ?? '') }}"
                            required
                            class="w-full h-11 px-3 rounded-lg border-[#e7edf3] dark:border-slate-600 bg-[#f6f7f8] dark:bg-slate-800 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary/20 placeholder:text-[#4c739a] font-medium"
                            placeholder="C1" type="text" />
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-[#4c739a] text-lg">tag</span>
                        </div>
                    </div>
                    <p class="text-xs text-[#4c739a]">Unique identifier.</p>
                </div>
                <!-- Nama Kriteria -->
                <div class="md:col-span-9 space-y-2">
                    <label class="block text-sm font-medium text-[#0d141b] dark:text-white">
                        Nama Kriteria <span class="text-red-500">*</span>
                    </label>
                    <input name="nama_kriteria" value="{{ old('nama_kriteria', $kriterium->nama_kriteria ?? '') }}" required
                        class="w-full h-11 px-3 rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary/20 placeholder:text-[#4c739a]"
                        placeholder="e.g., Nilai Akhlak or Biaya Pendidikan" type="text" />
                </div>
            </div>

            <div class="h-px bg-slate-100 dark:bg-slate-700 w-full"></div>

            <!-- Bottom Row: Bobot & Jenis -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Bobot Section -->
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-[#0d141b] dark:text-white">
                        Bobot (Weight) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input name="bobot" value="{{ old('bobot', $kriterium->bobot ?? '') }}" required
                            class="w-full h-12 pl-4 pr-12 rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-lg font-semibold focus:border-primary focus:ring-primary/20 placeholder:text-[#4c739a]"
                            max="{{ $remainingBobot + ($kriterium->bobot ?? 0) }}" min="0" placeholder="0" type="number" />
                        <div
                            class="absolute inset-y-0 right-0 flex items-center px-4 bg-[#f6f7f8] dark:bg-slate-700 border-l border-[#e7edf3] dark:border-slate-600 rounded-r-lg text-[#4c739a] font-medium">
                            %
                        </div>
                    </div>
                    <!-- Helper / Validation Visualization -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-100 dark:border-blue-800/50 flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-xl mt-0.5">info</span>
                        <div class="flex-1">
                            <p class="text-sm text-[#0d141b] dark:text-white font-medium mb-1">Weight Allocation</p>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 mb-1">
                                <div class="{{ $totalBobot >= 100 ? 'bg-red-500' : ($totalBobot >= 75 ? 'bg-amber-500' : 'bg-emerald-500') }} h-2 rounded-full"
                                    style="width: {{ $totalBobot }}%"></div>
                            </div>
                            <p class="text-xs text-[#4c739a]">
                                Current total used: <span
                                    class="font-bold text-[#0d141b] dark:text-white">{{ $totalBobot }}%</span>.
                                You have <span
                                    class="font-bold {{ $remainingBobot > 0 ? 'text-primary' : 'text-red-500' }}">{{ $remainingBobot }}%</span>
                                remaining.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Jenis Attribute Section -->
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-[#0d141b] dark:text-white">
                        Jenis Atribut (Type) <span class="text-red-500">*</span>
                    </label>
                    <!-- Custom Radio Group -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Benefit Option -->
                        <label class="cursor-pointer group relative">
                            <input {{ old('jenis', $kriterium->jenis ?? 'benefit') == 'benefit' ? 'checked' : '' }}
                                class="peer sr-only" name="jenis" type="radio" value="benefit" />
                            <div
                                class="p-4 rounded-xl border-2 border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-primary/50 transition-all peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:shadow-sm">
                                <div class="flex items-center gap-3 mb-2">
                                    <div
                                        class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                                        <span class="material-symbols-outlined text-lg">trending_up</span>
                                    </div>
                                    <span class="text-base font-semibold text-[#0d141b] dark:text-white">Benefit</span>
                                </div>
                                <p class="text-xs text-[#4c739a] leading-relaxed">
                                    Higher values are preferred (e.g., Grades, Achievement).
                                </p>
                            </div>
                            <div
                                class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity">
                                <span class="material-symbols-outlined">check_circle</span>
                            </div>
                        </label>
                        <!-- Cost Option -->
                        <label class="cursor-pointer group relative">
                            <input {{ old('jenis', $kriterium->jenis ?? '') == 'cost' ? 'checked' : '' }} class="peer sr-only"
                                name="jenis" type="radio" value="cost" />
                            <div
                                class="p-4 rounded-xl border-2 border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 hover:border-primary/50 transition-all peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:shadow-sm">
                                <div class="flex items-center gap-3 mb-2">
                                    <div
                                        class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
                                        <span class="material-symbols-outlined text-lg">trending_down</span>
                                    </div>
                                    <span class="text-base font-semibold text-[#0d141b] dark:text-white">Cost</span>
                                </div>
                                <p class="text-xs text-[#4c739a] leading-relaxed">
                                    Lower values are preferred (e.g., Fees, Distance, Errors).
                                </p>
                            </div>
                            <div
                                class="absolute top-4 right-4 text-primary opacity-0 peer-checked:opacity-100 transition-opacity">
                                <span class="material-symbols-outlined">check_circle</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-[#0d141b] dark:text-white">
                    Keterangan (Optional)
                </label>
                <textarea name="keterangan" rows="2"
                    class="w-full px-3 py-2 rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary/20 placeholder:text-[#4c739a]"
                    placeholder="Deskripsi kriteria...">{{ old('keterangan', $kriterium->keterangan ?? '') }}</textarea>
            </div>

            <!-- Action Bar -->
            <div
                class="bg-[#f6f7f8] dark:bg-slate-800 -mx-8 -mb-8 px-6 py-4 md:px-8 border-t border-[#e7edf3] dark:border-slate-700 flex flex-col-reverse md:flex-row items-center justify-end gap-3 mt-8">
                <a href="{{ route('kriteria.index') }}"
                    class="w-full md:w-auto px-6 py-2.5 rounded-lg border border-[#e7edf3] dark:border-slate-600 text-[#0d141b] dark:text-white font-medium hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit"
                    class="w-full md:w-auto px-6 py-2.5 rounded-lg bg-primary text-white font-medium hover:bg-blue-600 shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    {{ isset($kriterium) ? 'Update Kriteria' : 'Save Kriteria' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Form Disclaimer -->
    <div class="text-center">
        <p class="text-xs text-[#4c739a]">
            Changes to criteria weights will require a recalculation of all alternatives.
        </p>
    </div>
@endsection