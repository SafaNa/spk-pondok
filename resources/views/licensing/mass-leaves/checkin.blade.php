@extends('layouts.app')

@section('title', 'Kedatangan Santri')
@section('breadcrumb', 'Kedatangan Santri')
@section('breadcrumb_parent', 'Libur Massal')
@section('breadcrumb_parent_route', 'admin.mass-leaves.index')

@push('styles')
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 3rem;
        border-radius: 0.75rem;
        border-color: #e2e8f0;
        display: flex;
        align-items: center;
    }
    .dark .select2-container--default .select2-selection--single {
        background-color: #0f172a;
        border-color: #334155;
    }
    .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #f8fafc;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
    }
</style>
@endpush

@section('content')
<div class="w-full space-y-6 pb-10" x-data="checkinApp()">
    <!-- Header & Navigasi Modul -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.mass-leaves.show', $mass_leaf->id) }}"
                class="w-11 h-11 flex items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all shrink-0">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300 border border-purple-200 dark:border-purple-800 uppercase tracking-wider">MODUL 2 &bull; KEDATANGAN SANTRI</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $mass_leaf->title }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px] text-purple-500">calendar_month</span>
                    <span>Jadwal Kembali: <strong class="text-slate-700 dark:text-slate-200">{{ \Carbon\Carbon::parse($mass_leaf->end_date)->locale('id')->translatedFormat('d F Y') }}</strong></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-900/60 p-1.5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shrink-0">
            <a href="{{ route('admin.mass-leaves.checkout', $mass_leaf->id) }}"
                class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold text-xs rounded-xl transition-all flex items-center gap-1.5 hover:bg-white/50 dark:hover:bg-slate-800/50">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span class="material-symbols-outlined text-[18px] text-emerald-500">flight_takeoff</span>
                ACC Kepulangan
            </a>
            <span class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-bold text-xs rounded-xl shadow-sm flex items-center gap-1.5 border border-slate-200 dark:border-slate-700">
                <span class="material-symbols-outlined text-[18px] text-purple-500">how_to_reg</span>
                Kedatangan Santri
            </span>
        </div>
    </div>

    <!-- 1. Status Realisasi Kepulangan & Kedatangan -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px] text-purple-500">monitoring</span>
                Status Realisasi Kepulangan & Kedatangan
            </h3>
            <span class="text-[11px] font-semibold text-slate-400"></span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 border-l-4 border-l-blue-500 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-5 dark:opacity-10 pointer-events-none">
                    <span class="material-symbols-outlined text-8xl text-blue-600">done_all</span>
                </div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">TOTAL SUDAH DI-ACC PULANG</p>
                <p class="text-3xl font-black text-slate-900 dark:text-white"><span x-text="allRecords.length"></span> <span class="text-sm font-normal text-slate-500">Santri</span></p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px] text-blue-500">info</span>
                    Sudah check-out keluar pondok
                </p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 border-l-4 border-l-emerald-500 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-5 dark:opacity-10 pointer-events-none">
                    <span class="material-symbols-outlined text-8xl text-emerald-600">home</span>
                </div>
                <p class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-1">SUDAH KEMBALI KE PONDOK</p>
                <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400"><span x-text="allRecords.filter(r => r.actual_return_date).length"></span> <span class="text-sm font-normal text-slate-500">Santri</span></p>
                <p class="text-xs text-emerald-700/80 dark:text-emerald-300/80 mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                    Telah melapor di pos gerbang
                </p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 border-l-4 border-l-amber-500 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-5 dark:opacity-10 pointer-events-none">
                    <span class="material-symbols-outlined text-8xl text-amber-500">pending</span>
                </div>
                <p class="text-[11px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">BELUM KEMBALI (MASIH DI LUAR)</p>
                <p class="text-3xl font-black text-amber-500"><span x-text="allRecords.filter(r => !r.actual_return_date).length"></span> <span class="text-sm font-normal text-slate-500">Santri</span></p>
                <p class="text-xs text-amber-700/80 dark:text-amber-300/80 mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                    Masih dalam masa izin/libur
                </p>
            </div>
        </div>
    </div>


    <!-- 3. Daftar Tabel List Santri yang Sudah Pulang dan Belum Kembali -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/50">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                    Daftar Santri yang Sudah Pulang dan Belum Kembali
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Menampilkan status kedatangan seluruh santri yang telah melakukan check-out kepulangan pada liburan ini.</p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-center">
                <span class="px-3 py-1.5 bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 rounded-xl text-xs font-bold border border-amber-200 dark:border-amber-800/50">
                    Belum Kembali: <span x-text="allRecords.filter(r => !r.actual_return_date).length"></span> Santri
                </span>
                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 rounded-xl text-xs font-bold border border-emerald-200 dark:border-emerald-800/50">
                    Sudah Kembali: <span x-text="allRecords.filter(r => r.actual_return_date).length"></span> Santri
                </span>
            </div>
        </div>

        <!-- Filter & Search Bar (Standard DataTables Layout with Perfect Icon Alignment) -->
        <div class="p-4 sm:px-6 py-3.5 bg-white dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Left: Tampilkan & Filters -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 font-medium">
                    <span>Tampilkan:</span>
                    <div class="relative">
                        <select x-model.number="itemsPerPage" style="background-image:none;"
                            class="pl-3 pr-7 py-1.5 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/50 appearance-none transition-all">
                            <option :value="5">5</option>
                            <option :value="10">10</option>
                            <option :value="25">25</option>
                            <option :value="50">50</option>
                            <option :value="100">100</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-1.5">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">expand_more</span>
                        </div>
                    </div>
                    <span>data</span>
                </div>

                <div class="h-4 w-px bg-slate-200 dark:bg-slate-700 hidden sm:block"></div>

                <div class="w-full sm:w-40 relative">
                    <select x-model="selectedRayon" style="background-image:none;"
                        class="w-full pl-3 pr-8 py-1.5 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500/50 appearance-none transition-all">
                        <option value="">Semua Rayon</option>
                        <template x-for="ray in rayonCreateList" :key="ray">
                            <option :value="ray" x-text="ray"></option>
                        </template>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">expand_more</span>
                    </div>
                </div>

                <div class="w-full sm:w-40 relative">
                    <select x-model="selectedStatus" style="background-image:none;"
                        class="w-full pl-3 pr-8 py-1.5 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500/50 appearance-none transition-all">
                        <option value="">Semua Status</option>
                        <option value="not_returned">Belum Kembali</option>
                        <option value="returned">Sudah Kembali</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">expand_more</span>
                    </div>
                </div>
            </div>

            <!-- Right: Search Input -->
            <div class="relative w-full sm:w-72 shrink-0">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <span class="material-symbols-outlined text-slate-400 text-[18px]">search</span>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari santri, rayon, atau kamar..." 
                    class="w-full pl-9 pr-8 py-1.5 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all">
                <button type="button" x-show="searchQuery" @click="searchQuery = ''" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" style="display: none;">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/70 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Santri</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Waktu Keluar (Di-ACC Pulang)</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Status & Waktu Kembali</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    <template x-for="ms in paginatedRecords" :key="ms.id">
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors" :class="ms.actual_return_date ? 'opacity-70' : ''">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-sm shrink-0 border dark:border-slate-700"
                                        :class="ms.actual_return_date ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border-emerald-200' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200'"
                                        x-text="ms.student && ms.student.name ? ms.student.name.substring(0, 2).toUpperCase() : 'SA'">
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white" x-text="ms.student ? ms.student.name : '-'"></p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                            <span class="font-medium text-slate-600 dark:text-slate-300" x-text="(ms.student && ms.student.rayon && ms.student.rayon.name) ? ms.student.rayon.name : '-'"></span> &bull; Kamar <span x-text="(ms.student && ms.student.room && ms.student.room.name) ? ms.student.room.name : '-'"></span>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-xs text-slate-700 dark:text-slate-300 font-medium">
                                    <span class="material-symbols-outlined text-[16px] text-blue-500">flight_takeoff</span>
                                    <span>
                                        <span x-text="formatDate(ms.checked_out_at)"></span> 
                                        <strong class="text-slate-400 font-normal">(<span x-text="formatTime(ms.checked_out_at)"></span>)</strong>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <template x-if="ms.actual_return_date">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                        <span>Sudah Kembali (<span x-text="formatDateTime(ms.actual_return_date)"></span>)</span>
                                    </span>
                                </template>
                                <template x-if="!ms.actual_return_date">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-200 dark:border-amber-800 animate-pulse">
                                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                                        Belum Kembali (Masih Di Luar)
                                    </span>
                                </template>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <template x-if="!ms.actual_return_date">
                                    <button type="button" 
                                        @click="quickCheckin(ms.student.id, ms.student.name)"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all hover:shadow">
                                        <span class="material-symbols-outlined text-[16px]">how_to_reg</span>
                                        Catat Kembali
                                    </button>
                                </template>
                                <template x-if="ms.actual_return_date">
                                    <span class="text-xs text-slate-400 italic">Selesai</span>
                                </template>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="paginatedRecords.length === 0 && filteredRecords.length > 0" style="display: none;">
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400 text-xs">
                            Halaman ini kosong.
                        </td>
                    </tr>
                    <tr x-show="filteredRecords.length === 0" style="display: none;">
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700/60 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400">
                                <span class="material-symbols-outlined text-3xl" x-text="allRecords.length === 0 ? 'inbox' : 'search_off'"></span>
                            </div>
                            <p class="font-bold text-slate-800 dark:text-slate-200 text-base" x-text="allRecords.length === 0 ? 'Belum Ada Santri yang Di-ACC Pulang' : 'Santri Tidak Ditemukan'"></p>
                            <p class="text-xs text-slate-400 mt-1" x-text="allRecords.length === 0 ? 'Daftar santri akan muncul di sini setelah Anda melakukan proses ACC Kepulangan di Modul 1.' : 'Coba gunakan kata kunci pencarian atau filter status/rayon yang lain.'"></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div class="p-4 sm:p-6 bg-slate-50/80 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs" x-show="filteredRecords.length > 0">
            <div class="text-slate-500 dark:text-slate-400 font-medium">
                Menampilkan <strong class="text-slate-800 dark:text-slate-200 font-bold" x-text="Math.min((currentPage - 1) * itemsPerPage + 1, filteredRecords.length)"></strong> - 
                <strong class="text-slate-800 dark:text-slate-200 font-bold" x-text="Math.min(currentPage * itemsPerPage, filteredRecords.length)"></strong> 
                dari total <strong class="text-slate-800 dark:text-slate-200 font-bold" x-text="filteredRecords.length"></strong> santri
            </div>

            <!-- Page Buttons -->
            <div class="flex items-center gap-1 self-end sm:self-center">
                <button type="button" @click="currentPage = 1" :disabled="currentPage === 1" 
                    class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 disabled:opacity-40 disabled:pointer-events-none transition-all flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">keyboard_double_arrow_left</span>
                </button>
                <button type="button" @click="currentPage > 1 ? currentPage-- : null" :disabled="currentPage === 1" 
                    class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 disabled:opacity-40 disabled:pointer-events-none transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                    <span>Prev</span>
                </button>
                
                <div class="px-3 py-1.5 font-bold text-slate-700 dark:text-slate-200">
                    Hal <span x-text="currentPage"></span> / <span x-text="totalPages"></span>
                </div>

                <button type="button" @click="currentPage < totalPages ? currentPage++ : null" :disabled="currentPage === totalPages" 
                    class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 disabled:opacity-40 disabled:pointer-events-none transition-all flex items-center gap-1">
                    <span>Next</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </button>
                <button type="button" @click="currentPage = totalPages" :disabled="currentPage === totalPages" 
                    class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 disabled:opacity-40 disabled:pointer-events-none transition-all flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px]">keyboard_double_arrow_right</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('checkinApp', () => ({
            selectedStudent: '',
            isLoading: false,
            allRecords: @json($notReturnedStudents),
            searchQuery: '',
            selectedRayon: '',
            selectedStatus: '',
            currentPage: 1,
            itemsPerPage: 10,

            get rayonCreateList() {
                const rayNames = new Set();
                this.allRecords.forEach(r => {
                    if (r.student && r.student.rayon && r.student.rayon.name) {
                        rayNames.add(r.student.rayon.name);
                    }
                });
                return Array.from(rayNames).sort();
            },

            get filteredRecords() {
                return this.allRecords.filter(r => {
                    const s = r.student || {};
                    const q = this.searchQuery.toLowerCase().trim();
                    const matchName = (s.name || '').toLowerCase().includes(q);
                    const matchRayon = s.rayon && (s.rayon.name || '').toLowerCase().includes(q);
                    const matchRoom = s.room && (s.room.name || '').toLowerCase().includes(q);
                    const matchSearch = q === '' || matchName || matchRayon || matchRoom;

                    const matchRayonFilter = this.selectedRayon === '' || (s.rayon && s.rayon.name === this.selectedRayon);

                    const isReturned = !!r.actual_return_date;
                    let matchStatus = true;
                    if (this.selectedStatus === 'returned') matchStatus = isReturned;
                    if (this.selectedStatus === 'not_returned') matchStatus = !isReturned;

                    return matchSearch && matchRayonFilter && matchStatus;
                });
            },

            get totalPages() {
                return Math.ceil(this.filteredRecords.length / this.itemsPerPage) || 1;
            },

            get paginatedRecords() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                return this.filteredRecords.slice(start, start + this.itemsPerPage);
            },

            formatDate(dateStr) {
                if (!dateStr) return '-';
                const d = new Date(dateStr);
                return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            },
            
            formatTime(dateStr) {
                if (!dateStr) return '-';
                const d = new Date(dateStr);
                return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':') + ' WIB';
            },

            formatDateTime(dateStr) {
                if (!dateStr) return '-';
                const d = new Date(dateStr);
                return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' + 
                       d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':') + ' WIB';
            },
            
            init() {
                this.$watch('searchQuery', () => this.currentPage = 1);
                this.$watch('selectedRayon', () => this.currentPage = 1);
                this.$watch('selectedStatus', () => this.currentPage = 1);
                this.$watch('itemsPerPage', () => this.currentPage = 1);
            },

            quickCheckin(studentId, name) {
                Alpine.store('confirmModal').open(
                    () => this.doCheckin(studentId, name),
                    'Catat Kedatangan?',
                    `Tandai ananda "${name}" sudah kembali ke pondok pesantren sekarang?`,
                    'Ya, Tandai Sudah Kembali',
                    'Batal',
                    'primary'
                );
            },

            async doCheckin(studentId, name) {
                try {
                    const response = await fetch("{{ route('admin.mass-leaves.processCheckin', $mass_leaf->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ student_id: studentId })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        const rec = this.allRecords.find(r => r.student && r.student.id === studentId);
                        if (rec) rec.actual_return_date = new Date().toISOString();
                    } else {
                        alert(data.message || 'Terjadi kesalahan.');
                    }
                } catch (error) {
                    alert('Gagal terhubung ke server.');
                }
            }
        }));
    });
</script>
@endpush
