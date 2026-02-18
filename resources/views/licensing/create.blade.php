@extends('layouts.app')

@section('title', 'Input Izin Individu')
@section('breadcrumb', 'Input Izin')
@section('breadcrumb_parent', 'Perizinan')
@section('breadcrumb_parent_route', 'licenses.index')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Main Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            {{-- Header --}}
            <div class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-4 py-6 sm:px-6 sm:py-8 border-b border-primary/10">
                {{-- Back Button --}}
                <a href="{{ route('licenses.index') }}"
                    class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors group mb-6">
                    <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    <span class="text-sm font-medium">Kembali ke Daftar Izin</span>
                </a>
                
                {{-- Title Section --}}
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">person_add</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Input Izin Individu</h1>
                            <div class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold border border-primary/20">
                                New
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">
                            Catat perizinan santri untuk keperluan mendesak (Sakit, Keluarga, dll).
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('licenses.store-individual') }}" method="POST" class="p-4 sm:p-8">
                @csrf

                <div class="grid grid-cols-1 gap-8">
                    {{-- SECTION 1: Informasi Santri --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            {{-- Santri --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Pilih Santri <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">school</span>
                                    </div>
                                    <select name="student_id" required style="background-image: none;"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                        <option value="">-- Cari Nama Santri --</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}" data-info="({{ $student->rayon?->name }} - {{ $student->room?->name }})"
                                                {{ old('student_id') == $student->id ? 'selected' : '' }}>
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
                    </div>

                    {{-- SECTION 1.5: Tahun Ajaran --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Tahun Ajaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">calendar_month</span>
                                    </div>
                                    <select name="academic_year_id" required style="background-image: none;"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                        @foreach ($academicYears as $year)
                                            <option value="{{ $year->id }}" {{ old('academic_year_id', $activeYear?->id) == $year->id ? 'selected' : '' }}>
                                                {{ $year->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('academic_year_id')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Detail Waktu --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Tanggal Mulai --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Mulai Izin <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">event</span>
                                    </div>
                                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required onchange="calculateDuration()"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200">
                                </div>
                            </div>

                            {{-- Tanggal Selesai --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Sampai Tanggal <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">event_upcoming</span>
                                    </div>
                                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required onchange="calculateDuration()"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200">
                                </div>
                            </div>
                        </div>

                        {{-- Durasi Info (Dynamic) --}}
                         <div id="targetTokenWrapper" class="hidden rounded-xl border border-blue-100 bg-blue-50/50 p-4 dark:border-blue-900/30 dark:bg-blue-900/10">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 text-blue-600 dark:text-blue-400">
                                    <span class="material-symbols-outlined">info</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-blue-900 dark:text-blue-100">Estimasi Durasi & Target</h4>
                                    <p id="targetToken" class="text-sm text-blue-700 dark:text-blue-300 mt-1"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: Detail Izin --}}
                    <div class="space-y-6">
                        {{-- Alasan --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Alasan Izin <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">description</span>
                                </div>
                                <textarea name="description" rows="3" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal resize-none focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200"
                                    placeholder="Contoh: Sakit demam tinggi, Pulang karena ada acara nikahan kakak">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- Konfirmasi Hafalan --}}
                        <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-4 dark:border-emerald-900/30 dark:bg-emerald-900/10 transition-colors hover:bg-emerald-50 dark:hover:bg-emerald-900/20">
                            <label class="flex cursor-pointer items-start gap-3">
                                <div class="flex h-6 items-center">
                                    <input type="checkbox" name="memorization_check" value="1" id="memorization_check"
                                        class="h-5 w-5 rounded border-emerald-300 text-emerald-600 transition-all focus:ring-2 focus:ring-emerald-500/20 dark:border-emerald-700 dark:bg-slate-800 dark:checked:bg-emerald-500">
                                </div>
                                <div>
                                    <span class="block font-bold text-emerald-900 dark:text-emerald-100">Konfirmasi Setoran Hafalan</span>
                                    <span class="block text-sm text-emerald-700 dark:text-emerald-300">
                                        Saya menyatakan bahwa santri ini sudah menyetorkan hafalan sesuai target yang ditentukan.
                                    </span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-10 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('licenses.index') }}"
                        class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="order-1 sm:order-2 flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        Simpan & Cetak Surat
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('select[name="student_id"]').select2({
                placeholder: '-- Cari Nama Santri --',
                allowClear: true,
                width: '100%',
                dropdownCssClass: 'select2-premium-dropdown',
                containerCssClass: 'select2-premium-container',
                templateResult: formatStudent,
                templateSelection: formatStudent
            });

            function formatStudent(student) {
                if (!student.id) { return student.text; }
                var $student = $(
                    '<span>' + student.text + ' <span class="text-slate-400 text-xs font-normal ml-1">' + ($(student.element).data('info') || '') + '</span></span>'
                );
                return $student;
            }

            // Initial calculation
            calculateDuration();
        });

        // Duration Calculation Logic
        function calculateDuration() {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            const display = document.getElementById('targetToken');
            const wrapper = document.getElementById('targetTokenWrapper');

            if (start && end) {
                const startDate = new Date(start);
                const endDate = new Date(end);

                const diffTime = endDate - startDate; // miliseconds
                
                // Allow same day (0 diff) -> 1 day duration
                if (diffTime >= 0) {
                     const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                     wrapper.classList.remove('hidden');
                     display.innerHTML = `Durasi Izin: <strong class="text-blue-800 dark:text-blue-200">${diffDays} Hari</strong>. <br>Target Hafalan: <strong>Hafalan Hari ke-1 s/d ${Math.min(diffDays, 30)}</strong>`;
                } else {
                    wrapper.classList.remove('hidden');
                    display.innerHTML = `<span class="text-red-500 font-bold">⚠️ Tanggal selesai tidak boleh lebih awal dari tanggal mulai.</span>`;
                }
            } else {
                wrapper.classList.add('hidden');
            }
        }
    </script>

    {{-- Styles for Select2 --}}
    <style>
        /* Shared Styles matching SPP Create */
        .select2-container--default .select2-selection--single {
            height: 54px !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important;
            background-color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 1rem 0 3rem !important;
            transition: all 0.2s;
        }
        .dark .select2-container--default .select2-selection--single {
            background-color: #1e293b !important;
            border-color: #475569 !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: rgba(99, 102, 241, 0.6) !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155 !important;
            font-size: 1rem !important;
        }
        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e2e8f0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 52px !important;
            right: 0.75rem !important;
        }
        .select2-dropdown {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
        }
        .dark .select2-dropdown {
            background-color: #1e293b !important;
            border-color: #475569 !important;
        }
        .select2-search--dropdown .select2-search__field {
            border-radius: 0.5rem !important;
            padding: 0.6rem 1rem !important;
        }
        .dark .select2-search--dropdown .select2-search__field {
             background-color: #0f172a !important;
             border-color: #334155 !important;
             color: #cbd5e1 !important;
        }
        .select2-results__option {
            padding: 0.75rem 1rem !important;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: rgba(99, 102, 241, 0.1) !important;
            color: #4f46e5 !important;
        }
        .dark .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
             background-color: rgba(99, 102, 241, 0.2) !important;
             color: #818cf8 !important;
        }
    </style>
@endsection