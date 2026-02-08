@extends('layouts.app')

@section('title', 'Tambah User Khusus')
@section('breadcrumb', 'Tambah Baru')
@section('breadcrumb_parent', 'User Khusus')
@section('breadcrumb_parent_route', 'users.index')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('users.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Daftar User</span>
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
                        <span class="material-symbols-outlined text-[32px]">person_add</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Tambah User Khusus
                        </h1>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Tambahkan Petugas Perizinan atau
                            Petugas Keuangan baru ke dalam sistem.</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('users.store') }}" method="POST" class="p-6 sm:p-10 flex flex-col gap-10">
                @csrf

                {{-- Account Info --}}
                <div class="space-y-6">
                    <h3
                        class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">badge</span>
                        Informasi Akun
                    </h3>

                    {{-- Name --}}
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                placeholder="Masukkan Nama Lengkap">
                        </div>
                        @error('name')
                            <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[16px]">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Email --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">email</span>
                                </div>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="alamat@email.com">
                            </div>
                            @error('email')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Role / Jabatan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">admin_panel_settings</span>
                                </div>
                                <select name="role" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium appearance-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                    <option value="" disabled selected>Pilih Role</option>
                                    <option value="licensing_officer" {{ old('role') == 'licensing_officer' ? 'selected' : '' }}>Petugas Perizinan</option>
                                    <option value="finance_officer" {{ old('role') == 'finance_officer' ? 'selected' : '' }}>
                                        Petugas Keuangan</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('role')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Password Section --}}
                <div class="space-y-6">
                    <h3
                        class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">lock</span>
                        Keamanan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Password --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">key</span>
                                </div>
                                <input type="password" name="password" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="********">
                            </div>
                            @error('password')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">lock_reset</span>
                                </div>
                                <input type="password" name="password_confirmation" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Ulangi Password">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-4 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('users.index') }}"
                        class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="order-1 sm:order-2 flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        Simpan User Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection