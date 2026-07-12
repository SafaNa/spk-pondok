@extends('layouts.app')

@section('title', 'Edit Hafalan Santri')
@section('breadcrumb', 'Edit Hafalan')
@section('breadcrumb_parent', 'Hafalan Santri')
@section('breadcrumb_parent_route', 'admin.memorization.index')
@section('mobile_title', 'Edit Hafalan Santri')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Header --}}
        <div class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-4 py-6 sm:px-6 sm:py-8 border-b border-primary/10 rounded-t-3xl">
            <a href="{{ route('admin.memorization.index') }}"
                class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors group mb-6">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                <span class="text-sm font-medium">Kembali ke Riwayat Hafalan</span>
            </a>

            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                    <span class="material-symbols-outlined text-[32px]">edit_note</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Edit Hafalan Santri</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Perbarui data pencapaian hafalan santri.</p>
                </div>
            </div>
        </div>

        @if($memorization->is_used)
            <div class="mx-6 sm:mx-10 bg-amber-50 dark:bg-amber-500/10 border-l-4 border-amber-500 p-4 rounded-r-xl">
                <p class="text-sm text-amber-700 dark:text-amber-400 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">warning</span>
                    Perhatian: Data hafalan ini sudah pernah digunakan sebagai syarat perizinan pulang.
                </p>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.memorization.update', $memorization->id) }}" method="POST" class="p-6 sm:p-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-8">
                {{-- SECTION 1: Data Santri --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Data Santri
                    </h3>

                    <div class="grid grid-cols-1 gap-6">
                        {{-- Santri (Select2) --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Pilih Santri <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                                <select name="student_id" required id="student_id" style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                    <option value="">-- Pilih Santri --</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" data-info="({{ $student->rayon?->name }} - {{ $student->room?->name }})" {{ (old('student_id') ?? $memorization->student_id) == $student->id ? 'selected' : '' }}>
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

                {{-- SECTION 2: Detail Hafalan --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">menu_book</span>
                        Target Hafalan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Filter Jenjang --}}
                        <div class="md:col-span-2">
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Filter Target Hafalan</p>
                            <div class="flex flex-wrap gap-2">
                                <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400">school</span>
                                    <span class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-400 whitespace-nowrap">Jenjang:</span>
                                    <div class="flex gap-1">
                                        <button type="button" data-filter-level="all"
                                            class="filter-level-btn px-2.5 py-1 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 bg-primary text-white">
                                            Semua
                                        </button>
                                        <button type="button" data-filter-level="MTS"
                                            class="filter-level-btn px-2.5 py-1 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">
                                            MTs
                                        </button>
                                        <button type="button" data-filter-level="MA"
                                            class="filter-level-btn px-2.5 py-1 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">
                                            MA
                                        </button>
                                        <button type="button" data-filter-level="PT"
                                            class="filter-level-btn px-2.5 py-1 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-150 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">
                                            PT
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs text-slate-400 dark:text-slate-500">Tampil:</span>
                                    <span id="type-count" class="text-sm font-bold text-primary">{{ $types->count() }}</span>
                                    <span class="text-xs text-slate-400 dark:text-slate-500">/ {{ $types->count() }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Target Hafalan --}}
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Target Hafalan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">auto_stories</span>
                                </div>
                                <select name="memorization_type_id" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                    <option value="">-- Pilih Target Hafalan --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" data-level="{{ $type->education_level }}" {{ (old('memorization_type_id') ?? $memorization->memorization_type_id) == $type->id ? 'selected' : '' }}>
                                            [{{ $type->education_level }}] {{ $type->target_description }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('memorization_type_id')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Status Hafalan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">check_circle</span>
                                </div>
                                <select name="status" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                    <option value="pending" {{ (old('status') ?? $memorization->status) == 'pending' ? 'selected' : '' }}>Belum Selesai</option>
                                    <option value="completed" {{ (old('status') ?? $memorization->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('status')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Jumlah Hari --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Jumlah Hari Hafalan <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">today</span>
                                </div>
                                <input type="number" name="days" value="{{ old('days') ?? $memorization->days }}" min="1" max="365"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200"
                                    placeholder="Contoh: 7">
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
                                    placeholder="Catatan tambahan mengenai setoran hafalan...">{{ old('notes') ?? $memorization->notes }}</textarea>
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
                        <span class="material-symbols-outlined">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
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

                // ─── Filter Target Hafalan by Jenjang ────────────────────────
                let activeLevel = 'all';
                const $typeSelect = $('#memorization_type_id');
                const allTypeOptions = $typeSelect.find('option').clone();

                function applyLevelFilter() {
                    const currentVal = $typeSelect.val();
                    $typeSelect.find('option:not(:first-child)').remove();

                    let visible = 0;
                    allTypeOptions.filter(':not(:first-child)').each(function() {
                        const level = $(this).data('level');
                        if (activeLevel === 'all' || level === activeLevel) {
                            $typeSelect.append($(this).clone());
                            visible++;
                        }
                    });

                    if ($typeSelect.find('option[value="' + currentVal + '"]').length) {
                        $typeSelect.val(currentVal);
                    } else {
                        $typeSelect.val('');
                    }
                    $('#type-count').text(visible);
                }

                $('.filter-level-btn').on('click', function() {
                    activeLevel = $(this).data('filter-level');
                    $('.filter-level-btn')
                        .removeClass('bg-primary text-white')
                        .addClass('text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700');
                    $(this)
                        .addClass('bg-primary text-white')
                        .removeClass('text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700');
                    applyLevelFilter();
                });
            });
        </script>

        <style>
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
            .select2-container--default.select2-container--focus .select2-selection--single,
            .select2-container--default.select2-container--open .select2-selection--single {
                border-color: rgba(99, 102, 241, 0.6) !important;
                box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
                outline: none !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #334155 !important;
                line-height: normal !important;
                padding: 0 !important;
                font-size: 1rem !important;
            }
            .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #e2e8f0 !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: #94a3b8 !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 52px !important;
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
            .select2-dropdown {
                border: 1px solid #e2e8f0 !important;
                border-radius: 0.75rem !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
                margin-top: 4px !important;
                z-index: 50 !important;
            }
            .dark .select2-dropdown { background-color: #1e293b !important; border-color: #475569 !important; }
            .select2-search--dropdown .select2-search__field {
                border: 1px solid #e2e8f0 !important;
                border-radius: 0.5rem !important;
                padding: 0.6rem 1rem !important;
                margin: 0.5rem !important;
                width: calc(100% - 1rem) !important;
                outline: none !important;
            }
            .select2-search--dropdown .select2-search__field:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 1px #6366f1 !important; }
            .dark .select2-search--dropdown .select2-search__field { background-color: #0f172a !important; border-color: #334155 !important; color: #cbd5e1 !important; }
            .select2-results__option { padding: 0.75rem 1rem !important; font-size: 0.95rem !important; }
            .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { background-color: rgba(99, 102, 241, 0.1) !important; color: #4f46e5 !important; }
            .dark .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { background-color: rgba(99, 102, 241, 0.2) !important; color: #818cf8 !important; }
            .select2-container--default .select2-results__option--selected { background-color: #e0e7ff !important; color: #4338ca !important; font-weight: 600 !important; }
            .dark .select2-container--default .select2-results__option--selected { background-color: #312e81 !important; color: #c7d2fe !important; }
        </style>
    @endpush
@endsection
