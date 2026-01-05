@extends('layouts.app-v2-sidebar')

@section('title', 'Input Penilaian - Santri Admin')
@section('mobile_title', 'Input Penilaian')
@section('breadcrumb', 'Input Penilaian')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col gap-1">
        <h1 class="text-3xl font-black tracking-tight text-[#0d141b] dark:text-white">Input Penilaian Santri</h1>
        <p class="text-[#4c739a] text-base">Isi formulir penilaian objektif untuk rekomendasi kepulangan santri periode ini.
        </p>
    </div>

    <!-- Main Form Card -->
    <form
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
        <!-- Section 1: Data Identitas -->
        <div class="p-6 md:p-8 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-lg font-bold text-[#0d141b] dark:text-white mb-6 flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <span class="material-symbols-outlined text-[20px]">person_search</span>
                </span>
                Identitas & Periode
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Santri Searchable Select -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-[#0d141b] dark:text-white" for="santri-select">
                        Nama Santri <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span
                                class="material-symbols-outlined text-[#4c739a] group-focus-within:text-primary transition-colors">search</span>
                        </div>
                        <input autocomplete="off"
                            class="block w-full rounded-lg border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 py-3 pl-10 pr-10 text-sm text-[#0d141b] dark:text-white placeholder:[#4c739a] focus:border-primary focus:ring-1 focus:ring-primary shadow-sm"
                            id="santri-select" placeholder="Ketik untuk mencari nama santri..." type="text" />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-[#4c739a] text-[20px]">unfold_more</span>
                        </div>
                    </div>
                    <p class="text-xs text-[#4c739a]">Pilih santri dari daftar database aktif.</p>
                </div>
                <!-- Periode Select -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-semibold text-[#0d141b] dark:text-white" for="periode">
                        Periode Penilaian <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select
                            class="block w-full appearance-none rounded-lg border-[#e7edf3] dark:border-slate-700 bg-[#f6f7f8] dark:bg-slate-800 py-3 pl-3 pr-10 text-sm text-[#0d141b] dark:text-white focus:border-primary focus:ring-1 focus:ring-primary shadow-sm cursor-pointer"
                            id="periode">
                            <option selected value="active">Semester Genap 2023/2024 (Aktif)</option>
                            <option value="prev1">Semester Ganjil 2023/2024</option>
                            <option value="prev2">Semester Genap 2022/2023</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-[#4c739a]">
                            <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Kriteria Penilaian -->
        <div class="p-6 md:p-8 bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-lg font-bold text-[#0d141b] dark:text-white mb-6 flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <span class="material-symbols-outlined text-[20px]">fact_check</span>
                </span>
                Kriteria Penilaian
            </h3>
            <div class="space-y-6">
                <!-- Criterion 1: Kedisiplinan -->
                <div
                    class="rounded-xl border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-900 p-5 transition-all hover:shadow-md">
                    <div class="mb-4 flex items-center justify-between">
                        <label class="text-base font-semibold text-[#0d141b] dark:text-white flex items-center gap-2">
                            1. Kedisiplinan Ibadah
                            <span
                                class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-700/30">Bobot:
                                30%</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-[#4c739a] mb-1">Pilih Subkriteria</label>
                            <select
                                class="block w-full rounded-lg border-[#e7edf3] dark:border-slate-700 py-2.5 text-sm text-[#0d141b] dark:text-white dark:bg-slate-800 focus:border-primary focus:ring-1 focus:ring-primary">
                                <option disabled selected value="">Pilih kondisi kehadiran sholat...</option>
                                <option value="100">Sangat Baik (Tidak pernah absen tanpa udzur)</option>
                                <option value="80">Baik (Absen 1-3 kali)</option>
                                <option value="60">Cukup (Absen 4-6 kali)</option>
                                <option value="40">Kurang (Absen > 7 kali)</option>
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-[#4c739a] mb-1">Nilai (Otomatis)</label>
                            <input
                                class="block w-full rounded-lg border-[#e7edf3] dark:border-slate-700 bg-slate-100 dark:bg-slate-950 py-2.5 text-center text-sm font-bold text-[#4c739a] cursor-not-allowed"
                                readonly type="text" value="-" />
                        </div>
                    </div>
                </div>

                <!-- Criterion 2: Tahfidz -->
                <div
                    class="rounded-xl border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-900 p-5 transition-all hover:shadow-md">
                    <div class="mb-4 flex items-center justify-between">
                        <label class="text-base font-semibold text-[#0d141b] dark:text-white flex items-center gap-2">
                            2. Perkembangan Tahfidz
                            <span
                                class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-700/30">Bobot:
                                40%</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-[#4c739a] mb-1">Pilih Subkriteria</label>
                            <select
                                class="block w-full rounded-lg border-[#e7edf3] dark:border-slate-700 py-2.5 text-sm text-[#0d141b] dark:text-white dark:bg-slate-800 focus:border-primary focus:ring-1 focus:ring-primary">
                                <option disabled value="">Pilih pencapaian hafalan...</option>
                                <option selected value="100">Target Tercapai Sempurna (> 1 Juz Baru)</option>
                                <option value="80">Target Tercapai Baik (1 Juz Baru)</option>
                                <option value="60">Target Cukup (0.5 Juz Baru)</option>
                                <option value="40">Target Tidak Tercapai</option>
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-[#4c739a] mb-1">Nilai (Otomatis)</label>
                            <input
                                class="block w-full rounded-lg border-[#e7edf3] dark:border-slate-700 bg-slate-100 dark:bg-slate-950 py-2.5 text-center text-sm font-bold text-primary cursor-not-allowed"
                                readonly type="text" value="100" />
                        </div>
                    </div>
                </div>

                <!-- Criterion 3: Akhlak & Perilaku -->
                <div
                    class="rounded-xl border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-900 p-5 transition-all hover:shadow-md">
                    <div class="mb-4 flex items-center justify-between">
                        <label class="text-base font-semibold text-[#0d141b] dark:text-white flex items-center gap-2">
                            3. Akhlak & Perilaku
                            <span
                                class="inline-flex items-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-300 ring-1 ring-inset ring-blue-700/10 dark:ring-blue-700/30">Bobot:
                                30%</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-[#4c739a] mb-1">Pilih Subkriteria</label>
                            <select
                                class="block w-full rounded-lg border-[#e7edf3] dark:border-slate-700 py-2.5 text-sm text-[#0d141b] dark:text-white dark:bg-slate-800 focus:border-primary focus:ring-1 focus:ring-primary">
                                <option disabled selected value="">Pilih catatan perilaku...</option>
                                <option value="100">Sangat Baik (Tidak ada catatan pelanggaran)</option>
                                <option value="75">Cukup (Ada pelanggaran ringan)</option>
                                <option value="50">Kurang (Ada pelanggaran sedang)</option>
                                <option value="25">Buruk (Ada pelanggaran berat/SP)</option>
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-[#4c739a] mb-1">Nilai (Otomatis)</label>
                            <input
                                class="block w-full rounded-lg border-[#e7edf3] dark:border-slate-700 bg-slate-100 dark:bg-slate-950 py-2.5 text-center text-sm font-bold text-[#4c739a] cursor-not-allowed"
                                readonly type="text" value="-" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Footer -->
        <div
            class="flex items-center justify-end gap-3 p-6 md:px-8 border-t border-[#e7edf3] dark:border-slate-800 bg-white dark:bg-slate-900">
            <a href="{{ route('penilaian-v2') }}"
                class="rounded-lg border border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-transparent px-5 py-2.5 text-sm font-semibold text-[#0d141b] dark:text-white shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                Batal
            </a>
            <button
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 transition-all"
                type="button">
                <span class="material-symbols-outlined text-[20px]">save</span>
                Simpan Data
            </button>
        </div>
    </form>
@endsection