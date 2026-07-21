@extends('layouts.app')

@section('title', 'Catat Hafalan Santri')
@section('breadcrumb', 'Catat Hafalan')
@section('breadcrumb_parent', 'Hafalan Santri')
@section('breadcrumb_parent_route', 'admin.memorization.index')
@section('mobile_title', 'Catat Hafalan Santri')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Main Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            {{-- Header --}}
            <div class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-4 py-6 sm:px-6 sm:py-8 border-b border-primary/10">
            <a href="{{ route('admin.memorization.index') }}"
                class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors group mb-6">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                <span class="text-sm font-medium">Kembali ke Riwayat Hafalan</span>
            </a>

            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                    <span class="material-symbols-outlined text-[32px]">history_edu</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Catat Hafalan Santri</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Pilih santri, jenjang, dan jumlah hari hafalan. Sistem akan membuat daftar checklist item secara otomatis.</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.memorization.store') }}" method="POST" class="p-6 sm:p-10">
            @csrf

            <div class="grid grid-cols-1 gap-8">
                {{-- Santri --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Pilih Santri
                    </h3>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                            Santri <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">school</span>
                            </div>
                            <select name="student_id" required id="student_id" style="background-image: none;"
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                <option value="">-- Pilih Santri --</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}" data-info="({{ $student->rayon?->name }} - {{ $student->room?->name }})" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('student_id')
                            <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[16px]">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Detail Hafalan --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">menu_book</span>
                        Ketentuan Hafalan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Pilih Jenjang --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Jenjang <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">grade</span>
                                </div>
                                <select name="education_level" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="MTS" {{ old('education_level') == 'MTS' ? 'selected' : '' }}>MTs Sederajat</option>
                                    <option value="MA" {{ old('education_level') == 'MA' ? 'selected' : '' }}>MA Sederajat</option>
                                    <option value="PT" {{ old('education_level') == 'PT' ? 'selected' : '' }}>PT Sederajat</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('education_level')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Jumlah Hari --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Jumlah Hari Hafalan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">today</span>
                                </div>
                                <input type="number" name="days" value="{{ old('days') }}" min="1" max="365" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200"
                                    placeholder="Contoh: 3">
                            </div>
                            @error('days')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Catatan <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">notes</span>
                                </div>
                                <textarea name="notes" rows="3"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 resize-none"
                                    placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('admin.memorization.index') }}"
                        class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                        Batal
                    </a>
                            </div>
                            @error('student_id')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Detail Hafalan --}}
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="material-symbols-outlined text-primary">menu_book</span>
                            Ketentuan Hafalan
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Pilih Jenjang --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Jenjang <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">grade</span>
                                    </div>
                                    <select name="education_level" required style="background-image: none;"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                        <option value="">-- Pilih Jenjang --</option>
                                        <option value="MTS" {{ old('education_level') == 'MTS' ? 'selected' : '' }}>MTs Sederajat</option>
                                        <option value="MA" {{ old('education_level') == 'MA' ? 'selected' : '' }}>MA Sederajat</option>
                                        <option value="PT" {{ old('education_level') == 'PT' ? 'selected' : '' }}>PT Sederajat</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                        <span class="material-symbols-outlined">expand_more</span>
                                    </div>
                                </div>
                                @error('education_level')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Jumlah Hari --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Jumlah Hari Hafalan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">today</span>
                                    </div>
                                    <input type="number" name="days" value="{{ old('days') }}" min="1" max="365" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200"
                                        placeholder="Contoh: 3">
                                </div>
                                @error('days')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Catatan --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Catatan <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">notes</span>
                                    </div>
                                    <textarea name="notes" rows="3"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 resize-none"
                                        placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-8 border-t border-slate-200 dark:border-slate-800">
                        <a href="{{ route('admin.memorization.index') }}"
                            class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="order-1 sm:order-2 flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined">arrow_forward</span>
                            Buat & Lanjut ke Detail Hafalan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#student_id').select2({
                    placeholder: '-- Pilih Santri --',
                    allowClear: true,
                    width: '100%',
                    templateResult: formatStudent,
                    templateSelection: formatStudent
                });

                function formatStudent(student) {
                    if (!student.id) return student.text;
                    return $('<span>' + student.text + ' <span class="text-slate-400 text-xs font-normal ml-1">' + ($(student.element).data('info') || '') + '</span></span>');
                }
            });
        </script>
        <style>
            .select2-container--default .select2-selection--single { height: 54px !important; border: 1px solid #e2e8f0 !important; border-radius: 0.75rem !important; background-color: #ffffff !important; display: flex !important; align-items: center !important; padding: 0 1rem 0 3rem !important; }
            .dark .select2-container--default .select2-selection--single { background-color: #1e293b !important; border-color: #475569 !important; }
            .select2-container--default.select2-container--focus .select2-selection--single, .select2-container--default.select2-container--open .select2-selection--single { border-color: rgba(99,102,241,.6) !important; box-shadow: 0 0 0 2px rgba(99,102,241,.2) !important; outline: none !important; }
            .select2-container--default .select2-selection--single .select2-selection__rendered { color: #334155 !important; line-height: normal !important; padding: 0 !important; font-size: 1rem !important; }
            .dark .select2-container--default .select2-selection--single .select2-selection__rendered { color: #e2e8f0 !important; }
            .select2-container--default .select2-selection--single .select2-selection__placeholder { color: #94a3b8 !important; }
            .select2-container--default .select2-selection--single .select2-selection__arrow { height: 52px !important; right: .75rem !important; width: 20px !important; }
            .select2-dropdown { border: 1px solid #e2e8f0 !important; border-radius: .75rem !important; box-shadow: 0 10px 15px -3px rgba(0,0,0,.1) !important; margin-top: 4px !important; z-index: 50 !important; }
            .dark .select2-dropdown { background-color: #1e293b !important; border-color: #475569 !important; }
            .select2-search--dropdown .select2-search__field { border: 1px solid #e2e8f0 !important; border-radius: .5rem !important; padding: .6rem 1rem !important; margin: .5rem !important; width: calc(100% - 1rem) !important; outline: none !important; }
            .dark .select2-search--dropdown .select2-search__field { background-color: #0f172a !important; border-color: #334155 !important; color: #cbd5e1 !important; }
            .select2-results__option { padding: .75rem 1rem !important; font-size: .95rem !important; }
            .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { background-color: rgba(99,102,241,.1) !important; color: #4f46e5 !important; }
            .select2-container--default .select2-results__option--selected { background-color: #e0e7ff !important; color: #4338ca !important; font-weight: 600 !important; }
        </style>
    @endpush
@endsection
