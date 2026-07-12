@extends('layouts.app')

@section('title', 'Catat Pelanggaran')
@section('breadcrumb', 'Catat')
@section('breadcrumb_parent', 'Pelanggaran')
@section('breadcrumb_parent_route', 'admin.violations.index')
@section('mobile_title', 'Catat Pelanggaran')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Main Card --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            {{-- Header --}}
            <div
                class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-4 py-6 sm:px-6 sm:py-8 border-b border-primary/10">
                {{-- Back Button --}}
                <a href="{{ route('admin.violations.index') }}"
                    class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors group mb-6">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    <span class="text-sm font-medium">Kembali ke Daftar Pelanggaran</span>
                </a>

                {{-- Title Section --}}
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">warning</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Catat Pelanggaran
                            </h1>
                            @if($activePeriod)
                            <div class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold border border-primary/20">
                                {{ $activePeriod->name }}
                            </div>
                            @endif
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Silakan isi formulir di bawah ini
                            untuk mencatat pelanggaran yang dilakukan oleh santri.</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.violations.store') }}" method="POST" class="p-4 sm:p-8">
                @csrf

                <div class="grid grid-cols-1 gap-8">
                    {{-- SECTION 1: Informasi Dasar --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Santri Select --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Pilih Santri <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">person</span>
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

                            {{-- Tanggal Kejadian --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Tanggal Kejadian <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">calendar_today</span>
                                    </div>
                                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}"
                                        required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200">
                                </div>
                                @error('date')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Detail Pelanggaran --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- FILTER BAR --}}
                            <div class="md:col-span-2">
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Filter Jenis Pelanggaran</p>
                                <div class="flex flex-wrap gap-2">
                                    {{-- Filter Ruleset --}}
                                    <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2">
                                        <span class="material-symbols-outlined text-[18px] text-slate-400">menu_book</span>
                                        <span class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-400 whitespace-nowrap">Tata Tertib:</span>
                                        <div class="flex gap-1">
                                            <button type="button" data-filter-ruleset="all"
                                                class="filter-ruleset-btn px-2.5 py-1 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 bg-primary text-white">
                                                Semua
                                            </button>
                                            <button type="button" data-filter-ruleset="pesantren"
                                                class="filter-ruleset-btn px-2.5 py-1 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">
                                                Pondok
                                            </button>
                                            <button type="button" data-filter-ruleset="madrasah"
                                                class="filter-ruleset-btn px-2.5 py-1 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">
                                                Madrasah
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Filter Section --}}
                                    <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2">
                                        <span class="material-symbols-outlined text-[18px] text-slate-400">rule</span>
                                        <span class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-400 whitespace-nowrap">Jenis:</span>
                                        <div class="flex gap-1">
                                            <button type="button" data-filter-section="all"
                                                class="filter-section-btn px-2.5 py-1 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 bg-primary text-white">
                                                Semua
                                            </button>
                                            <button type="button" data-filter-section="kewajiban"
                                                class="filter-section-btn px-2.5 py-1 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">
                                                Kewajiban
                                            </button>
                                            <button type="button" data-filter-section="larangan"
                                                class="filter-section-btn px-2.5 py-1 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">
                                                Larangan
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Counter --}}
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs text-slate-400 dark:text-slate-500">Tampil:</span>
                                        <span id="violation-count" class="text-sm font-bold text-primary">{{ $violationTypes->count() }}</span>
                                        <span class="text-xs text-slate-400 dark:text-slate-500">/ {{ $violationTypes->count() }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Jenis Pelanggaran --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Jenis Pelanggaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">category</span>
                                    </div>
                                    <select name="violation_type_id" required id="violation_type_id" style="background-image: none;"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                        <option value="">-- Pilih Jenis Pelanggaran --</option>
                                        @foreach ($violationTypes as $type)
                                            @php
                                                $code = $type->code; // e.g. PP-KW-01, MDL-LR-02
                                                $section = str_contains($code, '-KW-') ? 'kewajiban' : 'larangan';
                                            @endphp
                                            <option value="{{ $type->id }}"
                                                data-sanction="{{ $type->default_sanction }}"
                                                data-ruleset="{{ $type->ruleset }}"
                                                data-section="{{ $section }}"
                                                {{ old('violation_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }} — {{ $type->category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                        <span class="material-symbols-outlined">expand_more</span>
                                    </div>
                                </div>
                                @error('violation_type_id')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                             {{-- Sanksi (Editable) --}}
                             <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Sanksi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">gavel</span>
                                    </div>
                                    <textarea name="sanction" id="sanction" rows="3" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal resize-none focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200"
                                        placeholder="Sanksi akan muncul otomatis saat jenis pelanggaran dipilih...">{{ old('sanction') }}</textarea>
                                </div>
                                @error('sanction')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Catatan Tambahan --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Catatan Tambahan <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">notes</span>
                                    </div>
                                    <textarea name="notes" rows="3"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal resize-none focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200"
                                        placeholder="Tambahkan detail kejadian jika perlu...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-10 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('admin.violations.index') }}"
                        class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="order-1 sm:order-2 flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        Simpan Pelanggaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // ─── State filter aktif ───────────────────────────────────────────
            let activeRuleset = 'all';
            let activeSection = 'all';
            const totalCount = {{ $violationTypes->count() }};

            // ─── Simpan semua option asli ─────────────────────────────────────
            const $select   = $('#violation_type_id');
            const allOptions = $select.find('option').clone(); // snapshot semua option

            // ─── Fungsi filter utama ──────────────────────────────────────────
            function applyFilters() {
                const currentVal = $select.val();

                $select.find('option:not(:first-child)').remove();

                let visible = 0;
                allOptions.filter(':not(:first-child)').each(function() {
                    const ruleset = $(this).data('ruleset');
                    const section = $(this).data('section');

                    const rulesetMatch = activeRuleset === 'all' || ruleset === activeRuleset;
                    const sectionMatch = activeSection === 'all' || section === activeSection;

                    if (rulesetMatch && sectionMatch) {
                        $select.append($(this).clone());
                        visible++;
                    }
                });

                // Kembalikan nilai sebelumnya jika masih tersedia
                if ($select.find('option[value="' + currentVal + '"]').length) {
                    $select.val(currentVal);
                } else {
                    $select.val('');
                    $('#sanction').val('');
                }

                // Update counter
                $('#violation-count').text(visible);
            }

            // ─── Tombol filter Ruleset ────────────────────────────────────────
            $('.filter-ruleset-btn').on('click', function() {
                activeRuleset = $(this).data('filter-ruleset');

                $('.filter-ruleset-btn')
                    .removeClass('bg-primary text-white')
                    .addClass('text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700');
                $(this)
                    .addClass('bg-primary text-white')
                    .removeClass('text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700');

                applyFilters();
            });

            // ─── Tombol filter Section ────────────────────────────────────────
            $('.filter-section-btn').on('click', function() {
                activeSection = $(this).data('filter-section');

                $('.filter-section-btn')
                    .removeClass('bg-primary text-white')
                    .addClass('text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700');
                $(this)
                    .addClass('bg-primary text-white')
                    .removeClass('text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700');

                applyFilters();
            });

            // ─── Auto-isi sanksi saat jenis pelanggaran dipilih ──────────────
            $select.on('change', function() {
                var sanction = $(this).find(':selected').data('sanction');
                $('#sanction').val(sanction || '');
            });

            // Trigger jika ada nilai lama (validation error)
            if ($select.val() && !$('#sanction').val()) {
                $select.trigger('change');
            }

            // ─── Select2 untuk Santri ─────────────────────────────────────────
            $('#student_id').select2({
                placeholder: '-- Pilih Santri --',
                allowClear: true,
                width: '100%',
                dropdownCssClass: 'select2-premium-dropdown',
                containerCssClass: 'select2-premium-container',
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
        /* Base Container */
        .select2-container--default .select2-selection--single {
            height: 54px !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important; /* rounded-xl */
            background-color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 1rem 0 3rem !important; /* pl-12 equivalent */
            transition: all 0.2s;
        }

        /* Dark Mode */
        .dark .select2-container--default .select2-selection--single {
            background-color: #1e293b !important; /* bg-slate-800 */
            border-color: #475569 !important; /* border-slate-600 */
        }

        /* Focus State */
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: rgba(99, 102, 241, 0.6) !important; /* border-primary/60 */
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important; /* ring-2 ring-primary/20 */
            outline: none !important;
        }

        /* Text Styling */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155 !important; /* text-slate-700 */
            line-height: normal !important;
            padding: 0 !important;
            font-size: 1rem !important; /* text-base */
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e2e8f0 !important; /* text-slate-200 */
        }

        /* Placeholder */
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important; /* text-slate-400 */
        }

        /* Arrow/Chevron */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 52px !important;
            position: absolute !important;
            top: 0 !important;
            right: 0.75rem !important;
            width: 20px !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #64748b transparent transparent transparent !important;
            border-style: solid !important;
            border-width: 5px 4px 0 4px !important;
            height: 0 !important;
            left: 50% !important;
            margin-left: -4px !important;
            margin-top: -2px !important;
            position: absolute !important;
            top: 50% !important;
            width: 0 !important;
        }

        /* Dropdown Menu */
        .select2-dropdown {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important; /* rounded-xl */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            margin-top: 4px !important;
            z-index: 50 !important;
        }

        .dark .select2-dropdown {
            background-color: #1e293b !important;
            border-color: #475569 !important;
        }

        /* Search Input in Dropdown */
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.5rem !important; /* rounded-lg */
            padding: 0.6rem 1rem !important;
            margin: 0.5rem !important;
            width: calc(100% - 1rem) !important;
            outline: none !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 1px #6366f1 !important;
        }

        .dark .select2-search--dropdown .select2-search__field {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }

        /* Options */
        .select2-results__option {
            padding: 0.75rem 1rem !important;
            font-size: 0.95rem !important;
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: rgba(99, 102, 241, 0.1) !important; /* primary/10 */
            color: #4f46e5 !important; /* primary-600 */
        }

        .dark .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: rgba(99, 102, 241, 0.2) !important;
            color: #818cf8 !important;
        }

        .select2-container--default .select2-results__option--selected {
            background-color: #e0e7ff !important;
            color: #4338ca !important;
            font-weight: 600 !important;
        }

        .dark .select2-container--default .select2-results__option--selected {
            background-color: #312e81 !important;
            color: #c7d2fe !important;
        }
    </style>
@endsection
