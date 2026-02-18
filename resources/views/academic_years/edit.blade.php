@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran')
@section('breadcrumb', 'Edit')
@section('breadcrumb_parent', 'Tahun Ajaran')
@section('breadcrumb_parent_route', 'academic-years.index')
@section('mobile_title', 'Edit Tahun Ajaran')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('academic-years.index') }}"
                class="p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Edit Tahun Ajaran</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Perbarui data tahun ajaran.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div
            class="bg-white dark:bg-slate-800 rounded-[20px] shadow-sm border border-slate-200 dark:border-slate-700 p-6 md:p-8">
            <form action="{{ route('academic-years.update', $academicYear->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    {{-- Name --}}
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-bold text-slate-700 dark:text-slate-300">Tahun Ajaran <span
                                class="text-red-500">*</span></label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-purple-600 transition-colors">
                                <span class="material-symbols-outlined">calendar_month</span>
                            </div>
                            <input type="text" name="name" id="name" value="{{ old('name', $academicYear->name) }}"
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-purple-600 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-600/10 transition-all duration-200"
                                placeholder="Contoh: 2023/2024" required>
                        </div>
                        @error('name') <p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span
                        class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p> @enderror
                    </div>

                    {{-- Deadlines --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="stage1_deadline" class="text-sm font-bold text-slate-700 dark:text-slate-300">Batas
                                Waktu Tahap 1</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">event</span>
                                </div>
                                <input type="date" name="stage1_deadline" id="stage1_deadline"
                                    value="{{ old('stage1_deadline', $academicYear->stage1_deadline ? $academicYear->stage1_deadline->format('Y-m-d') : '') }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                            </div>
                            @error('stage1_deadline') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="stage2_deadline" class="text-sm font-bold text-slate-700 dark:text-slate-300">Batas
                                Waktu Tahap 2</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">event</span>
                                </div>
                                <input type="date" name="stage2_deadline" id="stage2_deadline"
                                    value="{{ old('stage2_deadline', $academicYear->stage2_deadline ? $academicYear->stage2_deadline->format('Y-m-d') : '') }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                            </div>
                            @error('stage2_deadline') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-slate-100 dark:border-slate-700">
                    <a href="{{ route('academic-years.index') }}"
                        class="px-6 py-2.5 rounded-xl font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl font-medium bg-purple-600 hover:bg-purple-700 text-white shadow-lg shadow-purple-600/25 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection