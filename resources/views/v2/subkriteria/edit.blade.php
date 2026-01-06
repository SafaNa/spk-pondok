@extends('layouts.app')

@section('title', 'Edit Subkriteria - ' . $kriteria->nama_kriteria)
@section('mobile_title', 'Edit Subkriteria')
@section('breadcrumb', 'Kriteria / Subkriteria / Edit')

@section('content')
    <!-- Page Heading -->
    <div
        class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 rounded-2xl p-6 border border-primary/20 mb-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-[#0d141b] dark:text-white tracking-tight">
                    Edit Subkriteria
                </h2>
                <p class="text-[#4c739a] mt-1">
                    Update sub-criteria parameters for <strong>{{ $kriteria->nama_kriteria }}</strong>.
                </p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if($errors->any())
        <div
            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-6">
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
        <form action="{{ route('kriteria.subkriteria.update', [$kriteria->id, $subkriteria->id]) }}" method="POST"
            class="p-6 md:p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Nama Subkriteria -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-[#0d141b] dark:text-white">
                    Nama Subkriteria <span class="text-red-500">*</span>
                </label>
                <input name="nama_subkriteria" value="{{ old('nama_subkriteria', $subkriteria->nama_subkriteria) }}"
                    required
                    class="w-full h-11 px-3 rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary/20 placeholder:text-[#4c739a]"
                    placeholder="e.g., Sangat Baik, Cukup, Kurang" type="text" />
                <p class="text-xs text-[#4c739a]">The label displayed for this option.</p>
            </div>

            <div class="h-px bg-slate-100 dark:bg-slate-700 w-full"></div>

            <!-- Nilai -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-[#0d141b] dark:text-white">
                    Nilai (Value) <span class="text-red-500">*</span>
                </label>
                <div class="relative max-w-xs">
                    <input name="nilai" value="{{ old('nilai', $subkriteria->nilai) }}" required
                        class="w-full h-12 pl-4 pr-4 rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-lg font-semibold focus:border-primary focus:ring-primary/20 placeholder:text-[#4c739a]"
                        step="0.01" min="0" placeholder="0" type="number" />
                </div>
                <p class="text-xs text-[#4c739a]">The numerical value used in calculation.</p>
            </div>

            <!-- Keterangan -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-[#0d141b] dark:text-white">
                    Keterangan (Optional)
                </label>
                <textarea name="keterangan" rows="2"
                    class="w-full px-3 py-2 rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary/20 placeholder:text-[#4c739a]"
                    placeholder="Additional description...">{{ old('keterangan', $subkriteria->keterangan) }}</textarea>
            </div>

            <!-- Action Bar -->
            <div
                class="bg-[#f6f7f8] dark:bg-slate-800 -mx-8 -mb-8 px-6 py-4 md:px-8 border-t border-[#e7edf3] dark:border-slate-700 flex flex-col-reverse md:flex-row items-center justify-end gap-3 mt-8">
                <a href="{{ route('kriteria.subkriteria.index', $kriteria->id) }}"
                    class="w-full md:w-auto px-6 py-2.5 rounded-lg border border-[#e7edf3] dark:border-slate-600 text-[#0d141b] dark:text-white font-medium hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit"
                    class="w-full md:w-auto px-6 py-2.5 rounded-lg bg-primary text-white font-medium hover:bg-blue-600 shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Update Subkriteria
                </button>
            </div>
        </form>
    </div>
@endsection