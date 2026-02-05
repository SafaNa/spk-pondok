@extends('layouts.app')

@section('title', 'Tambah Santri Baru')
@section('breadcrumb', 'Tambah Baru')
@section('breadcrumb_parent', 'Santri')
@section('breadcrumb_parent_route', 'students.index')
@section('mobile_title', 'Tambah Santri Baru')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('students.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
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
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">person_add</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Daftarkan Santri
                            Baru
                        </h1>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Lengkapi biodata santri di bawah
                            ini untuk mendaftarkan mereka ke dalam sistem.</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6 sm:p-10 flex flex-col gap-10">
                @csrf



                {{-- SECTION 1: Identity --}}
                <div class="space-y-6">
                    <h3
                        class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">badge</span>
                        Identitas Santri
                    </h3>

                    {{-- Photo --}}
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-6">
                            <div class="shrink-0 relative group">
                                <div
                                    class="w-32 h-32 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-300 dark:border-slate-600 group-hover:border-purple-500 transition-all duration-300">
                                    <img id="photo-preview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                                    <div id="photo-placeholder" class="text-center p-4">
                                        <span
                                            class="material-symbols-outlined text-3xl text-slate-400 group-hover:text-purple-500 transition-colors">add_a_photo</span>
                                        <p class="text-xs text-slate-500 mt-2">Upload Foto</p>
                                    </div>
                                </div>
                                <input type="file" name="photo" id="photo" accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    onchange="previewImage(this)">
                            </div>
                            <div class="flex-1 space-y-1">
                                <h4 class="font-bold text-slate-700 dark:text-slate-300">Foto Profil Santri</h4>
                                <p class="text-sm text-slate-500">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                                @error('photo')
                                    <p class="text-sm text-red-500 flex items-center gap-1"><span
                                            class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- NIS --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                NIS <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">id_card</span>
                                </div>
                                <input type="text" name="nis" value="{{ old('nis') }}" required autofocus
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Masukkan Nomor Induk Santri">
                            </div>
                            @error('nis')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- NIK --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                NIK <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">badge</span>
                                </div>
                                <input type="text" name="nik" value="{{ old('nik') }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Masukkan NIK">
                            </div>
                            @error('nik')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

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
                                <input type="text" name="name" value="{{ old('name') }}" required
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

                        {{-- Gender --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="gender" value="male" class="peer sr-only" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                    <div
                                        class="flex items-center justify-center gap-3 p-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-medium transition-all duration-200 peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary group-hover:border-primary/50">
                                        <span class="material-symbols-outlined">male</span>
                                        Laki-laki
                                    </div>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="gender" value="female" class="peer sr-only" {{ old('gender') == 'female' ? 'checked' : '' }} required>
                                    <div
                                        class="flex items-center justify-center gap-3 p-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-medium transition-all duration-200 peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary group-hover:border-primary/50">
                                        <span class="material-symbols-outlined">female</span>
                                        Perempuan
                                    </div>
                                </label>
                            </div>
                            @error('gender')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">verified</span>
                                </div>
                                <select name="status" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium appearance-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak
                                        Aktif</option>
                                    <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>Lulus
                                    </option>
                                    <option value="dropped_out" {{ old('status') == 'dropped_out' ? 'selected' : '' }}>
                                        Keluar</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Academic & Room --}}
                <div class="space-y-6">
                    <h3
                        class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">school</span>
                        Akademik & Asrama
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Rayon --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Rayon <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">layers</span>
                                </div>
                                <select name="rayon_id" id="rayon_id" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium appearance-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                    <option value="" disabled selected>Pilih Rayon</option>
                                    @foreach($rayons as $rayon)
                                        <option value="{{ $rayon->id }}" {{ old('rayon_id') == $rayon->id ? 'selected' : '' }}>
                                            {{ $rayon->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('rayon_id')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Room --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Kamar / Asrama <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">bed</span>
                                </div>
                                <select name="room_id" id="room_id" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium appearance-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    disabled>
                                    <option value="" disabled selected>Pilih Kamar</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" data-rayon-id="{{ $room->rayon_id }}"
                                            class="room-option hidden" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('room_id')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Formal Education --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Pendidikan Formal (Kelas Umum)
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">school</span>
                                </div>
                                <select name="formal_education_level_id" style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium appearance-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                    <option value="" selected>Tidak Ada</option>
                                    @foreach($formalLevels as $level)
                                        <option value="{{ $level->id }}" {{ old('formal_education_level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('formal_education_level_id')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Religious Education --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Pendidikan Diniyah (Kelas Diniyah)
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">mosque</span>
                                </div>
                                <select name="religious_education_level_id" style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium appearance-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                    <option value="" selected>Tidak Ada</option>
                                    @foreach($religiousLevels as $level)
                                        <option value="{{ $level->id }}" {{ old('religious_education_level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('religious_education_level_id')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>


                {{-- SECTION 3: Birth Info --}}
                <div class="space-y-6">
                    <h3
                        class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">cake</span>
                        Informasi Kelahiran
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Birth Place --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">location_on</span>
                                </div>
                                <input type="text" name="birth_place" value="{{ old('birth_place') }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Kota Lahir">
                            </div>
                            @error('birth_place')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Birth Date --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">calendar_today</span>
                                </div>
                                <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                            </div>
                            @error('birth_date')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 4 (Renumbered): Alamat & Domisili --}}
                <div class="space-y-6">
                    <h3
                        class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">home</span>
                        Alamat & Domisili
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Province --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Provinsi <span
                                    class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">map</span>
                                </div>
                                <select name="province_code" id="province_code" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium appearance-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                    @foreach($provinces as $code => $name)
                                        <option value="{{ $code }}" {{ old('province_code') == $code ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('province_code') <p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span
                                class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                            @enderror
                        </div>



                        {{-- City --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Kabupaten/Kota <span
                                    class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">location_city</span>
                                </div>
                                <select name="city_code" id="city_code" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium appearance-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                    <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('city_code') <p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span
                                class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- District --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Kecamatan <span
                                    class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">holiday_village</span>
                                </div>
                                <select name="district_code" id="district_code" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium appearance-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                    <option value="" disabled selected>Pilih Kecamatan</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('district_code') <p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span
                                class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Village --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Desa/Kelurahan <span
                                    class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">home_work</span>
                                </div>
                                <select name="village_code" id="village_code" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium appearance-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                    <option value="" disabled selected>Pilih Desa/Kelurahan</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('village_code') <p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span
                                class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Full Address --}}
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Alamat Lengkap (Jalan,
                            Dusun, RT/RW)</label>
                        <div class="relative group">
                            <div
                                class="absolute top-3 left-4 text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">home_pin</span>
                            </div>
                            <textarea name="address" rows="3"
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                placeholder="Masukkan alamat lengkap (Jalan, Dusun, RT/RW)">{{ old('address') }}</textarea>
                        </div>
                        @error('address') <p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span
                        class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- SECTION 5: Family & Contact --}}
                <div class="space-y-6">
                    <h3
                        class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">family_restroom</span>
                        Keluarga & Kontak
                    </h3>

                    {{-- Father name & Mother name --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Nama Ayah</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">man</span>
                                </div>
                                <input type="text" name="father_name" value="{{ old('father_name') }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Nama Ayah">
                            </div>
                            @error('father_name') <p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span
                                class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Nama Ibu</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">woman</span>
                                </div>
                                <input type="text" name="mother_name" value="{{ old('mother_name') }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Nama Ibu">
                            </div>
                            @error('mother_name') <p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span
                                class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Father Education & Mother Education --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Pendidikan Ayah</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">school</span>
                                </div>
                                <input type="text" name="father_education" value="{{ old('father_education') }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Contoh: S1, SMA">
                            </div>
                            @error('father_education') <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Pendidikan Ibu</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">school</span>
                                </div>
                                <input type="text" name="mother_education" value="{{ old('mother_education') }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Contoh: S1, SMA">
                            </div>
                            @error('mother_education') <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Father Occupation & Mother Occupation --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Pekerjaan Ayah</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">work</span>
                                </div>
                                <input type="text" name="father_occupation" value="{{ old('father_occupation') }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Contoh: PNS, Wiraswasta">
                            </div>
                            @error('father_occupation') <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Pekerjaan Ibu</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">work</span>
                                </div>
                                <input type="text" name="mother_occupation" value="{{ old('mother_occupation') }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Contoh: Guru, Ibu Rumah Tangga">
                            </div>
                            @error('mother_occupation') <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Contact & Entry --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Tanggal Masuk</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">event_available</span>
                                </div>
                                <input type="date" name="entry_date" value="{{ old('entry_date') }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                            </div>
                            @error('entry_date') <p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span
                                class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Nomor Telepon
                                (Whatsapp)</label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">phone</span>
                                </div>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="08xxxxxxxxxx">
                            </div>
                            @error('phone') <p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span
                                class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-10 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('students.index') }}"
                        class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="order-1 sm:order-2 flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        Simpan Data Santri
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const provinceSelect = document.getElementById('province_code');
            const citySelect = document.getElementById('city_code');
            const districtSelect = document.getElementById('district_code');
            const villageSelect = document.getElementById('village_code');

            const oldCity = "{{ old('city_code') }}";
            const oldDistrict = "{{ old('district_code') }}";
            const oldVillage = "{{ old('village_code') }}";


            const fetchOptions = async (url, targetSelect, placeholder, selectedValue = null) => {
                targetSelect.disabled = true;
                targetSelect.innerHTML = `<option value="" disabled selected>Loading...</option>`;

                try {
                    console.log('Fetching from:', url);
                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log('Received data:', data);

                    targetSelect.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;

                    if (Object.keys(data).length === 0) {
                        console.warn('No data received from:', url);
                        targetSelect.innerHTML = `<option value="" disabled selected>Tidak ada data</option>`;
                        return;
                    }

                    Object.entries(data).forEach(([code, name]) => {
                        const option = document.createElement('option');
                        option.value = code;
                        option.textContent = name;
                        if (selectedValue && String(code) === String(selectedValue)) {
                            option.selected = true;
                        }
                        targetSelect.appendChild(option);
                    });

                    targetSelect.disabled = false;

                    // Trigger change event if value was pre-selected to propagate to next level
                    if (selectedValue) {
                        targetSelect.dispatchEvent(new Event('change'));
                    }
                } catch (error) {
                    console.error('Error fetching data:', error);
                    targetSelect.innerHTML = `<option value="" disabled selected>Error loading data</option>`;
                }
            };

            // Event Listeners
            provinceSelect.addEventListener('change', function () {
                if (this.value) {
                    fetchOptions(`{{ route('regions.cities') }}?province_code=${this.value}`, citySelect, 'Pilih Kabupaten/Kota');
                    districtSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
                    districtSelect.disabled = true;
                    villageSelect.innerHTML = '<option value="" disabled selected>Pilih Desa/Kelurahan</option>';
                    villageSelect.disabled = true;
                }
            });

            citySelect.addEventListener('change', function () {
                if (this.value) {
                    fetchOptions(`{{ route('regions.districts') }}?city_code=${this.value}`, districtSelect, 'Pilih Kecamatan');
                    villageSelect.innerHTML = '<option value="" disabled selected>Pilih Desa/Kelurahan</option>';
                    villageSelect.disabled = true;
                }
            });

            districtSelect.addEventListener('change', function () {
                if (this.value) {
                    fetchOptions(`{{ route('regions.villages') }}?district_code=${this.value}`, villageSelect, 'Pilih Desa/Kelurahan');
                }
            });

            // Rayon & Room Logic
            const rayonSelect = document.getElementById('rayon_id');
            const roomSelect = document.getElementById('room_id');
            const roomOptions = Array.from(roomSelect.querySelectorAll('.room-option'));

            function filterRooms() {
                const selectedRayonId = rayonSelect.value;
                let hasRooms = false;
                const currentRoomId = roomSelect.value;
                let currentRoomValid = false;

                roomOptions.forEach(option => {
                    // Check if option matches the selected rayon
                    // Note: dataset.rayonId might be string, selectedRayonId is string
                    if (selectedRayonId && option.dataset.rayonId == selectedRayonId) {
                        option.classList.remove('hidden');
                        hasRooms = true;
                        if (option.value == currentRoomId) currentRoomValid = true;
                    } else {
                        option.classList.add('hidden');
                    }
                });

                if (selectedRayonId) {
                    roomSelect.disabled = false;
                    // If the previously selected room is no longer valid (hidden), reset selection
                    if (!currentRoomValid) {
                        roomSelect.value = "";
                    }
                } else {
                    roomSelect.disabled = true;
                    roomSelect.value = "";
                }
            }

            if (rayonSelect) {
                rayonSelect.addEventListener('change', filterRooms);
                // Initial run
                if (rayonSelect.value) {
                    filterRooms();
                }
            }

            // Initialize OLD values
            if (provinceSelect.value) {
                // If province has value (from old input or user selection before reload?), trigger fetch
                fetchOptions(`{{ route('regions.cities') }}?province_code=${provinceSelect.value}`, citySelect, 'Pilih Kabupaten/Kota', oldCity)
                    .then(() => {
                        if (oldCity) {
                            return fetchOptions(`{{ route('regions.districts') }}?city_code=${oldCity}`, districtSelect, 'Pilih Kecamatan', oldDistrict);
                        }
                    })
                    .then(() => {
                        if (oldDistrict) {
                            return fetchOptions(`{{ route('regions.villages') }}?district_code=${oldDistrict}`, villageSelect, 'Pilih Desa/Kelurahan', oldVillage);
                        }
                    });
            }
        });
    </script>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('photo-preview');
            const placeholder = document.getElementById('photo-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush