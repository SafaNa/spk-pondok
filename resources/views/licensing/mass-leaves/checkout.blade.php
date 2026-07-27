@extends('layouts.app')

@section('title', 'ACC Kepulangan')
@section('breadcrumb', 'ACC Kepulangan')
@section('breadcrumb_parent', 'Libur Massal')
@section('breadcrumb_parent_route', 'admin.mass-leaves.index')

@section('content')
<div class="w-full space-y-6 pb-10">
    <!-- Header & Navigasi Modul -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.mass-leaves.show', $mass_leaf->id) }}"
                class="w-11 h-11 flex items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all shrink-0">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 uppercase tracking-wider">MODUL 1 &bull; ACC KEPULANGAN</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $mass_leaf->title }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px] text-emerald-500">calendar_month</span>
                    <span>Jadwal Kepulangan: <strong class="text-slate-700 dark:text-slate-200">{{ \Carbon\Carbon::parse($mass_leaf->start_date)->locale('id')->translatedFormat('d F Y') }}</strong></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-900/60 p-1.5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shrink-0">
            <span class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-bold text-xs rounded-xl shadow-sm flex items-center gap-1.5 border border-slate-200 dark:border-slate-700">
                <span class="material-symbols-outlined text-[18px] text-emerald-500">flight_takeoff</span>
                ACC Kepulangan
            </span>
            <a href="{{ route('admin.mass-leaves.checkin', $mass_leaf->id) }}"
                class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-semibold text-xs rounded-xl transition-all flex items-center gap-1.5 hover:bg-white/50 dark:hover:bg-slate-800/50">
                <span class="material-symbols-outlined text-[18px] text-purple-500">how_to_reg</span>
                Kedatangan Santri
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
    </div>

    <!-- 1. Peta Kesiapan Pulang (Pre-Checkout) -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px] text-emerald-500">analytics</span>
                Peta Kesiapan Pulang (Pre-Checkout)
            </h3>
            <span class="text-[11px] font-semibold text-slate-400">Total Santri Aktif: {{ number_format($totalActiveStudents) }}</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 border-l-4 border-l-blue-500 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-5 dark:opacity-10 pointer-events-none">
                    <span class="material-symbols-outlined text-8xl text-blue-600">groups</span>
                </div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">TOTAL SANTRI AKTIF PONDOK</p>
                <p class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalActiveStudents) }} <span class="text-sm font-normal text-slate-500">Santri</span></p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Seluruh santri dalam status aktif di pesantren</p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 border-l-4 border-l-emerald-500 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-5 dark:opacity-10 pointer-events-none">
                    <span class="material-symbols-outlined text-8xl text-emerald-600">{{ $mass_leaf->bulk_checkout_at ? 'flight_takeoff' : 'verified' }}</span>
                </div>
                <p class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-1">
                    {{ $mass_leaf->bulk_checkout_at ? 'SUDAH PULANG' : 'SIAP PULANG (BERSIH TANGGUNGAN)' }}
                </p>
                <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($eligibleStudentsCount) }} <span class="text-sm font-normal text-slate-500">Santri</span></p>
                <p class="text-xs text-emerald-700/80 dark:text-emerald-300/80 mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">{{ $mass_leaf->bulk_checkout_at ? 'flight_takeoff' : 'check_circle' }}</span>
                    {{ $mass_leaf->bulk_checkout_at ? 'Santri sudah di-ACC dan dapat pulang' : 'Siap diproses ACC kepulangan' }}
                </p>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 border-l-4 border-l-rose-500 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-5 dark:opacity-10 pointer-events-none">
                    <span class="material-symbols-outlined text-8xl text-rose-600">gavel</span>
                </div>
                <p class="text-[11px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider mb-1">TERTAHAN (ADA TANGGUNGAN PELANGGARAN)</p>
                <p class="text-3xl font-black text-rose-600 dark:text-rose-400">{{ number_format($blockedStudentsCount) }} <span class="text-sm font-normal text-slate-500">Santri</span></p>
                <p class="text-xs text-rose-700/80 dark:text-rose-300/80 mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">warning</span>
                    Tertahan sampai masalah selesai
                </p>
            </div>
        </div>
    </div>

    <!-- 2. Box Proses ACC Kepulangan -->
    <div class="bg-slate-900 text-white rounded-3xl shadow-xl p-6 sm:p-8 border border-slate-800 relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="space-y-2 max-w-2xl">
                @if($mass_leaf->bulk_checkout_at)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/30 text-emerald-300 border border-emerald-500/50 text-xs font-bold">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                        ACC Massal Sudah Diproses
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black tracking-tight">ACC Kepulangan Telah Diproses</h2>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Tombol ACC Massal telah ditekan pada
                        <strong class="text-emerald-300">{{ $mass_leaf->bulk_checkout_at->translatedFormat('d F Y, H:i') }} WIB</strong>.
                        Total <strong>{{ number_format($eligibleStudentsCount) }} santri</strong> sudah di-ACC kepulangan.
                        @if($blockedStudentsCount > 0)
                            Masih ada <strong class="text-rose-400">{{ number_format($blockedStudentsCount) }} santri tertahan</strong> yang belum bisa di-ACC.
                        @endif
                    </p>
                @else
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        Sistem Otomatis (Bulk ACC)
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black tracking-tight">Proses ACC Kepulangan Sekarang</h2>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Sistem akan memproses ACC kepulangan untuk <strong>{{ number_format($eligibleStudentsCount) }} santri yang bersih dari tanggungan</strong> secara serentak.
                        Santri yang masih memiliki pelanggaran tertunda (<strong class="text-rose-400">{{ number_format($blockedStudentsCount) }} santri tertahan</strong>) tidak akan di-ACC sampai masalahnya diselesaikan.
                    </p>
                @endif
            </div>

            <div class="shrink-0 w-full md:w-auto">
                @if($mass_leaf->bulk_checkout_at)
                    <div class="w-full md:w-auto py-4 px-8 bg-slate-700/60 text-slate-400 font-black text-base rounded-2xl border border-slate-600/50 flex items-center justify-center gap-2.5 cursor-not-allowed select-none">
                        <span class="material-symbols-outlined text-2xl text-emerald-400">task_alt</span>
                        <span class="whitespace-nowrap">ACC Sudah Diproses</span>
                    </div>
                @else
                    <form action="{{ route('admin.mass-leaves.bulkCheckout', $mass_leaf->id) }}" method="POST">
                        @csrf
                        <button type="button" @click.prevent="$store.confirmModal.open($el.closest('form'), 'Proses ACC Kepulangan', 'Apakah Anda yakin ingin memproses ACC Pulang sekarang untuk {{ number_format($eligibleStudentsCount) }} santri yang memenuhi syarat? Santri bermasalah akan dilewati otomatis.', 'Ya, Proses Sekarang', 'Batal', 'primary')"
                            class="w-full md:w-auto py-4 px-8 bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white font-black text-base rounded-2xl shadow-lg shadow-emerald-600/30 hover:shadow-emerald-500/50 transition-all flex items-center justify-center gap-2.5 transform active:scale-[0.98]">
                            <span class="material-symbols-outlined text-2xl">done_all</span>
                            <span>Proses ACC Kepulangan</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. Daftar Tabel List Santri yang Tertahan -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden" x-data="blockedStudentsTable">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/50">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                    Daftar Santri yang Tertahan (Ada Tanggungan Pelanggaran)
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Santri di bawah ini memiliki tanggungan atau sanksi disiplin yang belum diselesaikan sehingga tidak dapat di-ACC kepulangannya.</p>
            </div>
            <span class="px-3 py-1.5 bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300 rounded-xl text-xs font-bold border border-rose-200 dark:border-rose-800/50 self-start sm:self-center">
                Total: {{ number_format($blockedStudentsCount) }} Santri Tertahan
            </span>
        </div>

        <!-- Filter & Search Bar (Standard DataTables Layout with Perfect Icon Alignment) -->
        <div class="p-4 sm:px-6 py-3.5 bg-white dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Left: Tampilkan & Filter Rayon -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 font-medium">
                    <span>Tampilkan:</span>
                    <div class="relative">
                        <select x-model.number="itemsPerPage" style="background-image:none;"
                            class="pl-3 pr-7 py-1.5 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 appearance-none transition-all">
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

                <div class="w-full sm:w-44 relative">
                    <select x-model="selectedRayon" style="background-image:none;"
                        class="w-full pl-3 pr-8 py-1.5 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 appearance-none transition-all">
                        <option value="">Semua Rayon</option>
                        <template x-for="ray in rayonCreateList" :key="ray">
                            <option :value="ray" x-text="ray"></option>
                        </template>
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
                <input type="text" x-model="searchQuery" placeholder="Cari santri, kamar, atau kasus..." 
                    class="w-full pl-9 pr-8 py-1.5 bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all">
                <button type="button" x-show="searchQuery" @click="searchQuery = ''" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" style="display: none;">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/70 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider w-1/3">Santri</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Daftar Tanggungan / Kasus Tertunda</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    <template x-for="student in paginatedStudents" :key="student.id">
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400 font-black text-sm shrink-0 border border-rose-200 dark:border-rose-800/50"
                                        x-text="student.name ? student.name.substring(0, 2).toUpperCase() : 'SA'">
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white" x-text="student.name"></p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                            <span class="font-medium text-slate-600 dark:text-slate-300" x-text="(student.rayon && student.rayon.name) ? student.rayon.name : '-' "></span> &bull; Kamar <span x-text="(student.room && student.room.name) ? student.room.name : '-' "></span>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2 max-w-lg">
                                    <template x-if="(student.pending_violations || student.pendingViolations || []).length > 0">
                                        <div class="space-y-2">
                                            <template x-for="record in (student.pending_violations || student.pendingViolations || [])" :key="record.id">
                                                <div class="flex items-start gap-2.5 bg-rose-50/90 dark:bg-rose-950/50 p-3 rounded-2xl border border-rose-200/80 dark:border-rose-800/50 shadow-sm text-xs">
                                                    <span class="material-symbols-outlined text-[18px] text-rose-500 shrink-0 mt-0.5">warning</span>
                                                    <div class="flex-1 space-y-1.5">
                                                        <!-- Header: Departemen + Kategori + Poin -->
                                                        <div class="flex items-center gap-1.5 flex-wrap">
                                                            <template x-if="((record.violation_type || record.violationType || {}).department || {}).name">
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 border border-purple-200/80 dark:border-purple-800/60 rounded-md font-extrabold text-[10px]"
                                                                      x-text="'Dept. ' + ((record.violation_type || record.violationType || {}).department).name">
                                                                </span>
                                                            </template>
                                                            <template x-if="((record.violation_type || record.violationType || {}).category || {}).name">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md font-extrabold text-[10px] border"
                                                                      :class="{
                                                                          'bg-yellow-100 dark:bg-yellow-950/60 text-yellow-800 dark:text-yellow-300 border-yellow-300 dark:border-yellow-800/80': (((record.violation_type || record.violationType || {}).category || {}).name === 'Ringan'),
                                                                          'bg-orange-100 dark:bg-orange-950/60 text-orange-800 dark:text-orange-300 border-orange-300 dark:border-orange-800/80': (((record.violation_type || record.violationType || {}).category || {}).name === 'Sedang'),
                                                                          'bg-red-100 dark:bg-red-950/60 text-red-800 dark:text-red-300 border-red-300 dark:border-red-800/80': (((record.violation_type || record.violationType || {}).category || {}).name === 'Berat'),
                                                                          'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700': !['Ringan','Sedang','Berat'].includes(((record.violation_type || record.violationType || {}).category || {}).name)
                                                                      }"
                                                                      x-text="((record.violation_type || record.violationType || {}).category).name">
                                                                </span>
                                                            </template>
                                                            <template x-if="(record.violation_type || record.violationType || {}).points">
                                                                <span class="inline-block px-1.5 py-0.5 bg-rose-200 dark:bg-rose-800/80 text-rose-800 dark:text-rose-200 rounded-md font-black text-[10px]" x-text="(record.violation_type || record.violationType).points + ' Poin'"></span>
                                                            </template>
                                                        </div>

                                                        <!-- Nama Pelanggaran -->
                                                        <span class="font-bold text-rose-950 dark:text-rose-100 text-sm leading-tight block" x-text="((record.violation_type || record.violationType || {}).name) ? (record.violation_type || record.violationType).name : 'Pelanggaran Disiplin'"></span>

                                                        <!-- Notes / Catatan Kejadian -->
                                                        <template x-if="record.notes">
                                                            <p class="text-[11px] text-rose-700 dark:text-rose-300 italic" x-text="'Catatan: ' + record.notes"></p>
                                                        </template>

                                                        <!-- Sanksi / Hukuman -->
                                                        <template x-if="record.sanction || (record.violation_type || record.violationType || {}).default_sanction">
                                                            <div class="mt-1 flex items-start gap-1.5 p-2 bg-white/90 dark:bg-slate-900/60 rounded-xl border border-rose-200/70 dark:border-rose-800/50">
                                                                <span class="material-symbols-outlined text-[15px] text-amber-600 dark:text-amber-400 shrink-0 mt-0.5">gavel</span>
                                                                <div class="text-[11px] leading-relaxed">
                                                                    <span class="font-extrabold text-slate-800 dark:text-slate-200 text-[10px] uppercase tracking-wide block">Sanksi:</span>
                                                                    <span class="text-slate-700 dark:text-slate-300 font-medium" x-text="record.sanction || (record.violation_type || record.violationType || {}).default_sanction"></span>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!(student.pending_violations || student.pendingViolations || []).length">
                                        <span class="text-xs text-slate-400 italic">Ada catatan pelanggaran aktif yang belum tuntas</span>
                                    </template>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col items-end gap-2.5">
                                    <form method="POST" action="{{ route('admin.mass-leaves.forceCheckoutWithSanction', $mass_leaf->id) }}">
                                        @csrf
                                        <input type="hidden" name="student_id" :value="student.id">
                                        <button type="button" 
                                            @click.prevent="$store.confirmModal.open($el.closest('form'), 'Selesaikan Sanksi & ACC Pulang', 'Apakah Anda yakin ingin menandai seluruh sanksi pelanggaran untuk ' + student.name + ' sebagai SELESAI dan sekaligus memproses ACC kepulangannya?', 'Ya, Selesaikan & ACC', 'Batal', 'primary')"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-sm hover:shadow-md transition-all transform active:scale-95 border border-emerald-500/30 whitespace-nowrap">
                                            <span class="material-symbols-outlined text-[16px]">verified_user</span>
                                            <span>Sanksi Selesai &amp; ACC Pulang</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="paginatedStudents.length === 0 && filteredStudents.length > 0" style="display: none;">
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400 text-xs">
                            Halaman ini kosong.
                        </td>
                    </tr>
                    <tr x-show="filteredStudents.length === 0" style="display: none;">
                        <td colspan="3" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700/60 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400">
                                <span class="material-symbols-outlined text-3xl" x-text="allStudents.length === 0 ? 'verified' : 'search_off'"></span>
                            </div>
                            <p class="font-bold text-slate-800 dark:text-slate-200 text-base" x-text="allStudents.length === 0 ? 'Alhamdulillah, Tidak Ada Santri yang Tertahan!' : 'Santri Tidak Ditemukan'"></p>
                            <p class="text-xs text-slate-400 mt-1" x-text="allStudents.length === 0 ? 'Seluruh santri aktif dalam kondisi bersih dari tanggungan pelanggaran dan siap di-ACC kepulangan.' : 'Coba gunakan kata kunci pencarian atau filter rayon yang lain.'"></p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div class="p-4 sm:p-6 bg-slate-50/80 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs" x-show="filteredStudents.length > 0">
            <div class="text-slate-500 dark:text-slate-400 font-medium">
                Menampilkan <strong class="text-slate-800 dark:text-slate-200 font-bold" x-text="Math.min((currentPage - 1) * itemsPerPage + 1, filteredStudents.length)"></strong> - 
                <strong class="text-slate-800 dark:text-slate-200 font-bold" x-text="Math.min(currentPage * itemsPerPage, filteredStudents.length)"></strong> 
                dari total <strong class="text-slate-800 dark:text-slate-200 font-bold" x-text="filteredStudents.length"></strong> santri tertahan
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
        Alpine.data('blockedStudentsTable', () => ({
            allStudents: @json($blockedStudents),
            searchQuery: '',
            selectedRayon: '',
            currentPage: 1,
            itemsPerPage: 10,

            get rayonCreateList() {
                const rayNames = new Set();
                this.allStudents.forEach(s => {
                    if (s.rayon && s.rayon.name) rayNames.add(s.rayon.name);
                });
                return Array.from(rayNames).sort();
            },

            get filteredStudents() {
                return this.allStudents.filter(s => {
                    // Search query matching name, rayon, room, or violation type/notes
                    const q = this.searchQuery.toLowerCase().trim();
                    const matchName = (s.name || '').toLowerCase().includes(q);
                    const matchRayon = s.rayon && (s.rayon.name || '').toLowerCase().includes(q);
                    const matchRoom = s.room && (s.room.name || '').toLowerCase().includes(q);
                    let matchViolation = false;
                    const violations = s.pending_violations || s.pendingViolations || [];
                    if (violations.length > 0) {
                        matchViolation = violations.some(v => {
                            const vt = v.violation_type || v.violationType || {};
                            const deptName = (vt.department || {}).name || '';
                            const categoryName = (vt.category || {}).name || '';
                            const sanction = v.sanction || vt.default_sanction || '';
                            return (vt.name || '').toLowerCase().includes(q) ||
                                (v.notes || '').toLowerCase().includes(q) ||
                                sanction.toLowerCase().includes(q) ||
                                deptName.toLowerCase().includes(q) ||
                                categoryName.toLowerCase().includes(q);
                        });
                    }
                    const matchSearch = q === '' || matchName || matchRayon || matchRoom || matchViolation;

                    // Filter by Rayon
                    const matchRayonFilter = this.selectedRayon === '' || (s.rayon && s.rayon.name === this.selectedRayon);

                    return matchSearch && matchRayonFilter;
                });
            },

            get totalPages() {
                return Math.ceil(this.filteredStudents.length / this.itemsPerPage) || 1;
            },

            get paginatedStudents() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                return this.filteredStudents.slice(start, start + this.itemsPerPage);
            },

            init() {
                this.$watch('searchQuery', () => this.currentPage = 1);
                this.$watch('selectedRayon', () => this.currentPage = 1);
                this.$watch('itemsPerPage', () => this.currentPage = 1);
            }
        }));
    });
</script>
@endpush
