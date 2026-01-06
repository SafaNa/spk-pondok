@extends('layouts.app')

@section('title', 'Tambah Departemen')
@section('breadcrumb', 'Departemen / Tambah')
@section('mobile_title', 'Tambah Departemen')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('departemen.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Daftar Departemen</span>
        </a>

        {{-- Main Card --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            {{-- Header --}}
            <div
                class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-6 py-6 sm:px-8 sm:py-8 border-b border-primary/10">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">add_business</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Tambah Departemen
                            Baru</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Lengkapi formulir di bawah ini
                            untuk menambahkan departemen baru ke dalam sistem.</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('departemen.store') }}" method="POST" class="p-6 sm:p-10">
                @csrf

                <div class="grid grid-cols-1 gap-8">
                    {{-- SECTION 1: Identitas --}}
                    <div class="space-y-6">
                        <h3
                            class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="material-symbols-outlined text-primary">badge</span>
                            Identitas Departemen
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Kode Departemen --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Kode Departemen <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">tag</span>
                                    </div>
                                    <input type="text" name="kode_departemen" value="{{ old('kode_departemen') }}" required
                                        autofocus
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                        placeholder="Kode Departemen">
                                </div>
                                @error('kode_departemen')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Singkatan --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Singkatan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">short_text</span>
                                    </div>
                                    <input type="text" name="singkatan" value="{{ old('singkatan') }}" required class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                                                text-slate-800 dark:text-white placeholder:text-slate-400 font-medium uppercase
                                                                                focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 
                                                                                transition-all duration-200"
                                        placeholder="Contoh: K3">
                                </div>
                                @error('singkatan')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Nama Departemen - Full Width --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Nama Lengkap Departemen <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">apartment</span>
                                    </div>
                                    <input type="text" name="nama_departemen" value="{{ old('nama_departemen') }}" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                                                text-slate-800 dark:text-white placeholder:text-slate-400 font-medium
                                                                                focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 
                                                                                transition-all duration-200"
                                        placeholder="Contoh: Kebersihan, Keamanan, dan Ketertiban">
                                </div>
                                @error('nama_departemen')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Informasi Tambahan --}}
                    <div class="space-y-6">
                        <h3
                            class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="material-symbols-outlined text-primary">description</span>
                            Informasi Tambahan
                        </h3>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Keterangan / Deskripsi <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">notes</span>
                                </div>
                                <textarea name="keterangan" rows="4" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                                            text-slate-800 dark:text-white placeholder:text-slate-400 font-medium resize-none
                                                                            focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 
                                                                            transition-all duration-200"
                                    placeholder="Jelaskan tugas pokok dan fungsi departemen ini...">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-10 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('departemen.index') }}"
                        class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 
                                                                text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 
                                                                hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="order-1 sm:order-2 flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 
                                                                text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 
                                                                transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        Simpan Departemen
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection