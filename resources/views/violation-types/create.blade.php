@extends('layouts.app')

@section('title', 'Tambah Jenis Pelanggaran')
@section('breadcrumb', 'Tambah')
@section('breadcrumb_parent', 'Jenis Pelanggaran')
@section('breadcrumb_parent_route', 'violation-types.index')
@section('mobile_title', 'Tambah Jenis Pelanggaran')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
            {{-- Header --}}
            <div
                class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-4 py-6 sm:px-6 sm:py-8 border-b border-primary/10 rounded-t-3xl">
                {{-- Back Button --}}
                <a href="{{ route('violation-types.index') }}"
                    class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors group mb-6">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    <span class="text-sm font-medium">Kembali ke Daftar Jenis Pelanggaran</span>
                </a>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">add_circle</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Tambah Jenis
                            Pelanggaran</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Tambahkan jenis pelanggaran baru
                            beserta poin dan sanksi defaultnya.</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('violation-types.store') }}" method="POST" class="p-6 sm:p-10">
                @csrf

                <div class="grid grid-cols-1 gap-8">
                    {{-- SECTION 1: Klasifikasi --}}
                    <div class="space-y-6">
                        <h3
                            class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="material-symbols-outlined text-primary">category</span>
                            Klasifikasi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Departemen (Only for Admin, or Auto-filled for Pengurus) --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Departemen <span class="text-red-500">*</span>
                                </label>
                                @if (Auth::user()->isAdmin())
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                            <span class="material-symbols-outlined">apartment</span>
                                        </div>
                                        <select name="department_id" required id="department_id" style="background-image: none;"
                                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                            <option value="">-- Pilih Departemen --</option>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->id }}"
                                                    {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                            <span class="material-symbols-outlined">expand_more</span>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="department_id" value="{{ Auth::user()->department_id }}">
                                    <input type="text" value="{{ Auth::user()->department->name }}" disabled
                                        class="w-full px-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-medium cursor-not-allowed">
                                @endif
                                @error('department_id')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Kategori Pelanggaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">class</span>
                                    </div>
                                    <select name="violation_category_id" required id="violation_category_id" style="background-image: none;"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($violationCategories as $category)
                                            <option value="{{ $category->id }}" data-points="{{ $category->points }} Poin"
                                                {{ old('violation_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                        <span class="material-symbols-outlined">expand_more</span>
                                    </div>
                                </div>
                                @error('violation_category_id')
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
                        <h3
                            class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="material-symbols-outlined text-primary">gavel</span>
                            Detail Pelanggaran
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Kode Pelanggaran --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Kode Pelanggaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">tag</span>
                                    </div>
                                    <input type="text" name="code" value="{{ old('code') }}"
                                        required maxlength="20"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200"
                                        placeholder="Contoh: PLG-001">
                                </div>
                                @error('code')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Nama Pelanggaran - Full Width --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Nama Pelanggaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">title</span>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200"
                                        placeholder="Nama pelanggaran lengkap">
                                </div>
                                @error('name')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Sanksi Default - Full Width --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Sanksi Default <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                     <div
                                        class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">gavel</span>
                                    </div>
                                    <textarea name="default_sanction" rows="3" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 resize-none"
                                        placeholder="Sanksi yang diberikan secara default...">{{ old('default_sanction') }}</textarea>
                                </div>
                                @error('default_sanction')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Deskripsi - Full Width --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Deskripsi Tambahan <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                                </label>
                                <div class="relative group">
                                     <div
                                        class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">description</span>
                                    </div>
                                    <textarea name="description" rows="3"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 resize-none"
                                        placeholder="Penjelasan lebih detail mengenai pelanggaran...">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div
                        class="flex flex-col sm:flex-row gap-4 mt-8 pt-8 border-t border-slate-200 dark:border-slate-800">
                        <a href="{{ route('violation-types.index') }}"
                            class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="order-1 sm:order-2 flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined">save</span>
                            Simpan Jenis Pelanggaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

            function formatCategory(category) {
                if (!category.id) {
                    return category.text;
                }
                var points = $(category.element).data('points');
                 var $category = $(
                    '<span>' + category.text + ' <span class="text-slate-400 text-xs font-normal ml-1">(' + (points || '') + ')</span></span>'
                );
                return $category;
            }
            */
            // Native select doesn't need JS formatting. We just use CSS.
            // Removing Select2 initialization for department and category completely.
        });
    </script>
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
