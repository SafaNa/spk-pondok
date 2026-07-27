@extends('layouts.app')

@section('title', 'Edit Event Liburan Serentak')
@section('breadcrumb', 'Edit Event')
@section('breadcrumb_parent', 'Libur Massal')
@section('breadcrumb_parent_route', 'admin.mass-leaves.index')
@section('content')
<div class="flex flex-col gap-6 w-full pb-10">
    {{-- Main Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
        {{-- Header --}}
        <div class="bg-gradient-to-br from-amber-500/10 via-purple-500/5 to-pink-500/5 px-4 py-6 sm:px-6 sm:py-8 border-b border-amber-500/10">
            {{-- Back Button --}}
            <a href="{{ route('admin.mass-leaves.index') }}"
                class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors group mb-6">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                <span class="text-sm font-medium">Kembali ke Daftar Liburan</span>
            </a>
            
            {{-- Title Section --}}
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-amber-500/20 text-amber-600">
                    <span class="material-symbols-outlined text-[32px]">edit_calendar</span>
                </div>
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Edit Event Liburan Serentak</h1>
                        <div class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-600 text-xs font-bold border border-amber-500/20">
                            Edit
                        </div>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-base">
                        Perbarui jadwal atau nama event liburan massal santri sebelum memasuki batas waktu <span class="whitespace-nowrap font-semibold">H-1</span>.
                    </p>
                </div>
            </div>
        </div>

        @if(session('error'))
        <div class="m-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-start gap-3">
            <span class="material-symbols-outlined text-red-500">error</span>
            {{ session('error') }}
        </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.mass-leaves.update', $mass_leaf->id) }}" method="POST" class="p-4 sm:p-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-8 w-full">
                <div class="space-y-6">
                    <div>
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 block">
                            Nama Event Liburan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-amber-500 transition-colors">
                                <span class="material-symbols-outlined">title</span>
                            </div>
                            <input type="text" name="title" value="{{ old('title', $mass_leaf->title) }}" required
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 transition-all duration-200"
                                placeholder="Contoh: Libur Idul Fitri 1447 H">
                        </div>
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 block">
                                Tanggal Mulai Libur <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-amber-500 transition-colors">
                                    <span class="material-symbols-outlined">calendar_today</span>
                                </div>
                                <input type="date" name="start_date" value="{{ old('start_date', $mass_leaf->start_date ? $mass_leaf->start_date->format('Y-m-d') : '') }}" min="{{ date('Y-m-d') }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 transition-all duration-200">
                            </div>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 block">
                                Tanggal Wajib Kembali <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-amber-500 transition-colors">
                                    <span class="material-symbols-outlined">event_busy</span>
                                </div>
                                <input type="date" name="end_date" value="{{ old('end_date', $mass_leaf->end_date ? $mass_leaf->end_date->format('Y-m-d') : '') }}" min="{{ old('start_date', $mass_leaf->start_date && $mass_leaf->start_date->isAfter(today()) ? $mass_leaf->start_date->format('Y-m-d') : date('Y-m-d')) }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-amber-500/60 focus:ring-2 focus:ring-amber-500/20 transition-all duration-200">
                            </div>
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex gap-4 border-t border-slate-100 dark:border-slate-800 pt-8">
                <button type="submit"
                    class="px-8 py-3.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition-all flex items-center gap-2 group">
                    <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startInput = document.querySelector('input[name="start_date"]');
        const endInput = document.querySelector('input[name="end_date"]');
        
        if (startInput && endInput) {
            startInput.addEventListener('change', function() {
                endInput.min = this.value;
                if (endInput.value && endInput.value < this.value) {
                    endInput.value = this.value;
                }
            });
        }
    });
</script>
@endsection
