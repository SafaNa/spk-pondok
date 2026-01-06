@extends('layouts.app')

@section('title', 'Edit Santri')
@section('breadcrumb', 'Santri / Edit')
@section('mobile_title', 'Edit Santri')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('santri.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-purple-600 transition-colors w-fit group mb-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-purple-600/10 transition-colors">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Data Santri</span>
        </a>

        {{-- Main Card --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            {{-- Header --}}
            <div
                class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-6 py-6 sm:px-8 sm:py-8 border-b border-primary/10">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-purple-600">
                        <span class="material-symbols-outlined text-[32px]">edit_square</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Edit Data Santri
                        </h1>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Perbarui informasi biodata santri
                            yang sudah terdaftar dalam sistem.</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('santri.update', $santri->id) }}" method="POST" class="p-6 sm:p-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-8">
                    {{-- SECTION 1: Identitas --}}
                    <div class="space-y-6">
                        <h3
                            class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="material-symbols-outlined text-purple-600">badge</span>
                            Identitas Santri
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- NIS --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    NIS <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-purple-600 transition-colors">
                                        <span class="material-symbols-outlined">id_card</span>
                                    </div>
                                    <input type="text" name="nis" value="{{ old('nis', $santri->nis) }}" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                    text-slate-800 dark:text-white placeholder:text-slate-400 font-medium
                                                    focus:outline-none focus:border-purple-600 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-600/10 
                                                    transition-all duration-200"
                                        placeholder="Nomor Induk Santri">
                                </div>
                                @error('nis')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Nama Santri --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-purple-600 transition-colors">
                                        <span class="material-symbols-outlined">person</span>
                                    </div>
                                    <input type="text" name="nama" value="{{ old('nama', $santri->nama) }}" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                    text-slate-800 dark:text-white placeholder:text-slate-400 font-medium
                                                    focus:outline-none focus:border-purple-600 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-600/10 
                                                    transition-all duration-200"
                                        placeholder="Nama Lengkap Santri">
                                </div>
                                @error('nama')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="jenis_kelamin" value="L" class="peer sr-only"
                                            {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'L' ? 'checked' : '' }}
                                            required>
                                        <div
                                            class="flex items-center justify-center gap-3 p-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                    text-slate-600 dark:text-slate-400 font-medium transition-all duration-200
                                                    peer-checked:border-purple-600 peer-checked:bg-purple-600/5 peer-checked:text-purple-600
                                                    group-hover:border-purple-600/50">
                                            <span class="material-symbols-outlined">male</span>
                                            Laki-laki
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="jenis_kelamin" value="P" class="peer sr-only"
                                            {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'P' ? 'checked' : '' }}
                                            required>
                                        <div
                                            class="flex items-center justify-center gap-3 p-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                    text-slate-600 dark:text-slate-400 font-medium transition-all duration-200
                                                    peer-checked:border-purple-600 peer-checked:bg-purple-600/5 peer-checked:text-purple-600
                                                    group-hover:border-purple-600/50">
                                            <span class="material-symbols-outlined">female</span>
                                            Perempuan
                                        </div>
                                    </label>
                                </div>
                                @error('jenis_kelamin')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Status - Full Width on Mobile --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Status Santri <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-purple-600 transition-colors">
                                        <span class="material-symbols-outlined">verified</span>
                                    </div>
                                    <select name="status" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                    text-slate-800 dark:text-white font-medium appearance-none
                                                    focus:outline-none focus:border-purple-600 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-600/10 
                                                    transition-all duration-200">
                                        <option value="aktif"
                                            {{ old('status', $santri->status) == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="non-aktif"
                                            {{ old('status', $santri->status) == 'non-aktif' ? 'selected' : '' }}>Non-Aktif
                                        </option>
                                        <option value="lulus"
                                            {{ old('status', $santri->status) == 'lulus' ? 'selected' : '' }}>Lulus
                                        </option>
                                        <option value="drop-out"
                                            {{ old('status', $santri->status) == 'drop-out' ? 'selected' : '' }}>Drop Out
                                        </option>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                        <span class="material-symbols-outlined">expand_more</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Kelahiran --}}
                    <div class="space-y-6">
                        <h3
                            class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="material-symbols-outlined text-purple-600">cake</span>
                            Data Kelahiran
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Tempat Lahir --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Tempat Lahir <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-purple-600 transition-colors">
                                        <span class="material-symbols-outlined">location_on</span>
                                    </div>
                                    <input type="text" name="tempat_lahir"
                                        value="{{ old('tempat_lahir', $santri->tempat_lahir) }}" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                    text-slate-800 dark:text-white placeholder:text-slate-400 font-medium
                                                    focus:outline-none focus:border-purple-600 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-600/10 
                                                    transition-all duration-200"
                                        placeholder="Kota Kelahiran">
                                </div>
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-purple-600 transition-colors">
                                        <span class="material-symbols-outlined">calendar_today</span>
                                    </div>
                                    <input type="date" name="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', $santri->tanggal_lahir && $santri->tanggal_lahir instanceof \DateTime ? $santri->tanggal_lahir->format('Y-m-d') : $santri->tanggal_lahir) }}"
                                        required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                    text-slate-800 dark:text-white placeholder:text-slate-400 font-medium
                                                    focus:outline-none focus:border-purple-600 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-600/10 
                                                    transition-all duration-200">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: Kontak & Alamat --}}
                    <div class="space-y-6">
                        <h3
                            class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="material-symbols-outlined text-purple-600">contact_mail</span>
                            Kontak & Alamat
                        </h3>

                        {{-- Nama Ortu --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Nama Orang Tua / Wali <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-purple-600 transition-colors">
                                        <span class="material-symbols-outlined">supervisor_account</span>
                                    </div>
                                    <input type="text" name="nama_ortu" value="{{ old('nama_ortu', $santri->nama_ortu) }}"
                                        required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                    text-slate-800 dark:text-white placeholder:text-slate-400 font-medium
                                                    focus:outline-none focus:border-purple-600 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-600/10 
                                                    transition-all duration-200"
                                        placeholder="Nama Orang Tua">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    No. Handphone Orang Tua <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-purple-600 transition-colors">
                                        <span class="material-symbols-outlined">phone_iphone</span>
                                    </div>
                                    <input type="text" name="no_hp_ortu"
                                        value="{{ old('no_hp_ortu', $santri->no_hp_ortu) }}" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                    text-slate-800 dark:text-white placeholder:text-slate-400 font-medium
                                                    focus:outline-none focus:border-purple-600 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-600/10 
                                                    transition-all duration-200"
                                        placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-purple-600 transition-colors">
                                    <span class="material-symbols-outlined">home</span>
                                </div>
                                <textarea name="alamat" rows="3" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 
                                                text-slate-800 dark:text-white placeholder:text-slate-400 font-medium resize-none
                                                focus:outline-none focus:border-purple-600 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-600/10 
                                                transition-all duration-200"
                                    placeholder="Jalan, RT/RW, Kelurahan, Kecamatan...">{{ old('alamat', $santri->alamat) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-10 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('santri.index') }}"
                        class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 
                                    text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 
                                    hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="order-1 sm:order-2 flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 
                                    text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 
                                    transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">update</span>
                        Update Data Santri
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
