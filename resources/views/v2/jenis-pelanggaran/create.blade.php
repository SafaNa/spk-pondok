@extends('layouts.app')

@section('title', 'Tambah Jenis Pelanggaran')
@section('breadcrumb', 'Jenis Pelanggaran / Tambah')
@section('mobile_title', 'Tambah Jenis Pelanggaran')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('jenis-pelanggaran.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Daftar Jenis Pelanggaran</span>
        </a>

        {{-- Main Card --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800">
            {{-- Header --}}
            <div
                class="bg-gradient-to-br from-primary/10 via-blue-500/5 to-indigo-500/5 px-6 py-6 sm:px-8 sm:py-8 border-b border-primary/10 rounded-t-3xl">
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
            <form action="{{ route('jenis-pelanggaran.store') }}" method="POST" class="p-6 sm:p-10">
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
                                        <select name="departemen_id" required
                                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200 appearance-none bg-none">
    
                                            @foreach ($departemen as $dept)
                                                <option value="{{ $dept->id }}"
                                                    {{ old('departemen_id') == $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->nama_departemen }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                            <span class="material-symbols-outlined">expand_more</span>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="departemen_id" value="{{ Auth::user()->departemen_id }}">
                                    <input type="text" value="{{ Auth::user()->departemen->nama_departemen }}" disabled
                                        class="w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-medium">
                                @endif
                                @error('departemen_id')
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
                                    <select name="kategori_pelanggaran_id" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200 appearance-none bg-none">

                                        @foreach ($kategori as $kat)
                                            <option value="{{ $kat->id }}"
                                                {{ old('kategori_pelanggaran_id') == $kat->id ? 'selected' : '' }}>
                                                {{ $kat->nama_kategori }} ({{ $kat->kode_kategori }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                        <span class="material-symbols-outlined">expand_more</span>
                                    </div>
                                </div>
                                @error('kategori_pelanggaran_id')
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
                                    <input type="text" name="kode_pelanggaran" value="{{ old('kode_pelanggaran') }}"
                                        required maxlength="20"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                        placeholder="Contoh: PLG-001">
                                </div>
                                @error('kode_pelanggaran')
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
                                    <input type="text" name="nama_pelanggaran" value="{{ old('nama_pelanggaran') }}"
                                        required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                        placeholder="Nama pelanggaran lengkap">
                                </div>
                                @error('nama_pelanggaran')
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
                                    <textarea name="sanksi_default" rows="3" required
                                        class="w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200 resize-none"
                                        placeholder="Sanksi yang diberikan secara default...">{{ old('sanksi_default') }}</textarea>
                                </div>
                                @error('sanksi_default')
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
                                    <textarea name="deskripsi" rows="3"
                                        class="w-full px-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200 resize-none"
                                        placeholder="Penjelasan lebih detail mengenai pelanggaran...">{{ old('deskripsi') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div
                        class="flex flex-col sm:flex-row gap-4 mt-8 pt-8 border-t border-slate-200 dark:border-slate-800">
                        <a href="{{ route('jenis-pelanggaran.index') }}"
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Init Choices.js for Departemen (if exists)
            const deptSelect = document.querySelector('select[name="departemen_id"]');
            if (deptSelect) {
                new Choices(deptSelect, {
                    searchEnabled: true,
                    placeholder: true,
                    placeholderValue: 'Pilih Departemen',
                    searchPlaceholderValue: 'Cari departemen...',
                    itemSelectText: '',
                });
            }

            // Init Choices.js for Kategori
            const katSelect = document.querySelector('select[name="kategori_pelanggaran_id"]');
            if (katSelect) {
                new Choices(katSelect, {
                    searchEnabled: true,
                    placeholder: true,
                    placeholderValue: 'Pilih Kategori',
                    searchPlaceholderValue: 'Cari kategori...',
                    itemSelectText: '',
                });
            }
        });
    </script>
@endsection
