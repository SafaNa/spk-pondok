@extends('layouts.app')

@section('title', 'Catat Pelanggaran')
@section('breadcrumb', 'Pelanggaran / Catat')
@section('mobile_title', 'Catat Pelanggaran')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('pelanggaran.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Daftar Pelanggaran</span>
        </a>

        {{-- Main Card --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800">
            {{-- Header --}}
            <div
                class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-6 py-6 sm:px-8 sm:py-8 border-b border-primary/10 rounded-t-3xl">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">warning</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Catat Pelanggaran
                            Santri</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Silakan isi formulir di bawah ini
                            untuk mencatat pelanggaran yang dilakukan oleh santri.</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('pelanggaran.store') }}" method="POST" class="p-6 sm:p-10">
                @csrf

                <div class="grid grid-cols-1 gap-8">
                    {{-- SECTION 1: Informasi Dasar --}}
                    <div class="space-y-6">
                        <h3
                            class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="material-symbols-outlined text-primary">info</span>
                            Informasi Dasar
                        </h3>

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
                                    <select name="santri_id" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200 appearance-none bg-none">

                                        @foreach ($santri as $s)
                                            <option value="{{ $s->id }}" {{ old('santri_id') == $s->id ? 'selected' : '' }}>
                                                {{ $s->nama }} ({{ $s->nis }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                        <span class="material-symbols-outlined">expand_more</span>
                                    </div>
                                </div>
                                @error('santri_id')
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
                                    <input type="date" name="tanggal_kejadian" value="{{ old('tanggal_kejadian', date('Y-m-d')) }}"
                                        required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                </div>
                                @error('tanggal_kejadian')
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

                        <div class="space-y-6">
                            {{-- Jenis Pelanggaran --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Jenis Pelanggaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">category</span>
                                    </div>
                                    <select name="jenis_pelanggaran_id" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200 appearance-none bg-none">

                                        @foreach ($jenisPelanggaran as $jp)
                                            <option value="{{ $jp->id }}"
                                                {{ old('jenis_pelanggaran_id') == $jp->id ? 'selected' : '' }}>
                                                [{{ $jp->kategoriPelanggaran->kode_kategori }}] {{ $jp->nama_pelanggaran }}
                                                ({{ $jp->kategoriPelanggaran->bobot_poin }} Poin)
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                        <span class="material-symbols-outlined">expand_more</span>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 ml-1">
                                    *Pilih jenis pelanggaran yang sesuai.
                                </p>
                                @error('jenis_pelanggaran_id')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                             {{-- Sanksi (Editable) --}}
                             <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Sanksi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">gavel</span>
                                    </div>
                                    <textarea name="sanksi" id="sanksi" rows="3" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium resize-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                        placeholder="Sanksi akan muncul otomatis saat jenis pelanggaran dipilih...">{{ old('sanksi') }}</textarea>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 ml-1">
                                    *Anda dapat mengubah sanksi default jika diperlukan.
                                </p>
                                @error('sanksi')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Catatan Tambahan --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Catatan Tambahan <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">notes</span>
                                    </div>
                                    <textarea name="catatan" rows="4"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium resize-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                        placeholder="Tambahkan detail kejadian jika perlu...">{{ old('catatan') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-10 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('pelanggaran.index') }}"
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

    {{-- Pass PHP data to JS safely --}}
    <script>
        const jenisPelanggaranData = @json($jenisPelanggaran->mapWithKeys(function ($item) {
            return [$item->id => $item->sanksi_default];
        }));
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Init Choices.js for Santri
            const santriSelect = document.querySelector('select[name="santri_id"]');
            if(santriSelect) {
                new Choices(santriSelect, {
                    searchEnabled: true,
                    placeholder: true,
                    placeholderValue: 'Pilih Nama Santri',
                    searchPlaceholderValue: 'Cari nama santri...',
                    itemSelectText: '',
                });
            }

            // Init Choices.js for Jenis Pelanggaran & Handle Sanksi Change
            const jenisSelect = document.querySelector('select[name="jenis_pelanggaran_id"]');
            const sanksiTextarea = document.getElementById('sanksi');

            if(jenisSelect) {
                const choices = new Choices(jenisSelect, {
                    searchEnabled: true,
                    placeholder: true,
                    placeholderValue: 'Pilih Jenis Pelanggaran',
                    searchPlaceholderValue: 'Cari jenis pelanggaran...',
                    itemSelectText: '',
                });

                // Listen to change event
                jenisSelect.addEventListener('change', function(event) {
                    const selectedId = event.target.value;
                    if(selectedId && jenisPelanggaranData[selectedId]) {
                        sanksiTextarea.value = jenisPelanggaranData[selectedId];
                    } else {
                        sanksiTextarea.value = '';
                    }
                });
                
                // Trigger change on load if value exists (e.g. invalid form return)
                if(jenisSelect.value && jenisPelanggaranData[jenisSelect.value] && !sanksiTextarea.value) {
                     sanksiTextarea.value = jenisPelanggaranData[jenisSelect.value];
                }
            }
        });
    </script>
@endsection
