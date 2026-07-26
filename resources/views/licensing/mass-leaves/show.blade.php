@extends('layouts.app')

@section('title', 'Detail Liburan Massal')
@section('breadcrumb', 'Detail Liburan')
@section('breadcrumb_parent', 'Libur Massal')
@section('breadcrumb_parent_route', 'admin.mass-leaves.index')

@section('content')
<div class="w-full space-y-8 pb-10">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.mass-leaves.index') }}"
                class="w-11 h-11 flex items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all shrink-0">
                <span class="material-symbols-outlined text-[22px]">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white">{{ $mass_leaf->title }}</h1>
                    @if($mass_leaf->status === 'completed')
                    <span class="px-3 py-0.5 text-xs font-bold rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">task_alt</span>
                        Selesai
                    </span>
                    @else
                    <span class="px-3 py-0.5 text-xs font-bold rounded-full flex items-center gap-1 {{ $mass_leaf->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                        <span class="w-2 h-2 rounded-full {{ $mass_leaf->status === 'active' ? 'bg-emerald-500 animate-ping' : 'bg-slate-400' }}"></span>
                        {{ $mass_leaf->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    @endif
                </div>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-blue-500">flight_takeoff</span> Kepulangan: <strong>{{ \Carbon\Carbon::parse($mass_leaf->start_date)->locale('id')->translatedFormat('d F Y') }}</strong></span>
                    <span>&bull;</span>
                    <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-purple-500">flight_land</span> Kembali: <strong>{{ \Carbon\Carbon::parse($mass_leaf->end_date)->locale('id')->translatedFormat('d F Y') }}</strong></span>
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 self-start sm:self-center">
            @if($mass_leaf->canBeFinishedManually())
            <form action="{{ route('admin.mass-leaves.finish', $mass_leaf->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="button" @click.prevent="$store.confirmModal.open($el.closest('form'), 'Akhiri Event', 'Apakah Anda yakin ingin menyudahi event liburan ini dan mengubah statusnya menjadi Selesai permanen?', 'Ya, Akhiri Sekarang', 'Batal', 'warning')"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:hover:bg-rose-900/50 dark:text-rose-300 font-bold text-xs sm:text-sm rounded-xl transition-all border border-rose-200 dark:border-rose-800 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">event_busy</span>
                    Akhiri Event Liburan
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200 rounded-2xl border border-emerald-200 dark:border-emerald-800 flex items-start gap-3 shadow-sm">
        <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 shrink-0">check_circle</span>
        <div class="text-sm font-medium">{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-rose-50 dark:bg-rose-900/30 text-rose-800 dark:text-rose-200 rounded-2xl border border-rose-200 dark:border-rose-800 flex items-start gap-3 shadow-sm">
        <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 shrink-0">error</span>
        <div class="text-sm font-medium">{{ session('error') }}</div>
    </div>
    @endif

    <!-- Pilih Modul Operasional -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px] text-indigo-500">apps</span>
                Pilih Modul Operasional Liburan Serentak
            </h2>
            <span class="text-xs text-slate-400 font-medium">Halaman operasional dipisahkan untuk kemudahan pengurus</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- MODUL 1: ACC KEPULANGAN -->
            <div class="bg-gradient-to-br from-white via-slate-50/50 to-white dark:from-slate-800 dark:via-slate-800/80 dark:to-slate-800 rounded-3xl p-6 sm:p-8 border-2 border-emerald-500/30 dark:border-emerald-500/20 shadow-lg hover:shadow-xl hover:border-emerald-500 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none group-hover:bg-emerald-500/10 transition-all"></div>
                
                <div>
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-sm border border-emerald-200 dark:border-emerald-800 shrink-0 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">flight_takeoff</span>
                        </div>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 rounded-full text-xs font-black border border-emerald-200 dark:border-emerald-800 tracking-wide uppercase">
                            Modul 1 &bull; ACC Kepulangan
                        </span>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                        ACC Kepulangan Libur Massal
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                        Kelola kesiapan kepulangan seluruh santri. Sistem memvalidasi santri aktif secara serentak, menahan santri dengan tanggungan pelanggaran, dan meneruskan informasi langsung ke dasbor wali santri.
                    </p>

                    <!-- Mini Stats Grid -->
                    <div class="grid grid-cols-3 gap-2.5 mb-8 bg-slate-100/70 dark:bg-slate-900/60 p-3.5 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 text-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Total Aktif</p>
                            <p class="text-base font-black text-slate-800 dark:text-slate-200">{{ number_format($totalActiveStudents) }}</p>
                        </div>
                        <div class="border-x border-slate-200 dark:border-slate-700 px-1">
                            <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase">
                                {{ $mass_leaf->bulk_checkout_at ? 'Sudah Pulang' : 'Siap Pulang' }}
                            </p>
                            <p class="text-base font-black text-emerald-600 dark:text-emerald-400">{{ number_format($eligibleStudentsCount) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase">Tertahan</p>
                            <p class="text-base font-black text-rose-600 dark:text-rose-400">{{ number_format($blockedStudentsCount) }}</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.mass-leaves.checkout', $mass_leaf->id) }}"
                    class="w-full py-4 px-6 bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white font-black text-sm sm:text-base rounded-2xl shadow-lg shadow-emerald-600/25 hover:shadow-emerald-500/40 transition-all flex items-center justify-center gap-2 group-hover:translate-x-0.5">
                    <span>Buka Halaman ACC Kepulangan</span>
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </a>
            </div>

            <!-- MODUL 2: KEDATANGAN SANTRI -->
            <div class="bg-gradient-to-br from-white via-slate-50/50 to-white dark:from-slate-800 dark:via-slate-800/80 dark:to-slate-800 rounded-3xl p-6 sm:p-8 border-2 border-purple-500/30 dark:border-purple-500/20 shadow-lg hover:shadow-xl hover:border-purple-500 transition-all duration-300 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-purple-500/5 rounded-full blur-3xl pointer-events-none group-hover:bg-purple-500/10 transition-all"></div>
                
                <div>
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center text-purple-600 dark:text-purple-400 shadow-sm border border-purple-200 dark:border-purple-800 shrink-0 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">how_to_reg</span>
                        </div>
                        <span class="px-3 py-1 bg-purple-50 text-purple-700 dark:bg-purple-950 dark:text-purple-300 rounded-full text-xs font-black border border-purple-200 dark:border-purple-800 tracking-wide uppercase">
                            Modul 2 &bull; Kedatangan Santri
                        </span>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                        Kedatangan Santri Libur Massal
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                        Pantau realisasi kepulangan dan kedatangan santri kembali ke pondok. Dilengkapi Petugas Kedatangan untuk mencatat waktu tiba secara real-time dan akurat.
                    </p>

                    <!-- Mini Stats Grid -->
                    <div class="grid grid-cols-3 gap-2.5 mb-8 bg-slate-100/70 dark:bg-slate-900/60 p-3.5 rounded-2xl border border-slate-200/60 dark:border-slate-700/60 text-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Sudah Pulang</p>
                            <p class="text-base font-black text-slate-800 dark:text-slate-200">{{ number_format($totalCheckedOut) }}</p>
                        </div>
                        <div class="border-x border-slate-200 dark:border-slate-700 px-1">
                            <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase">Sudah Kembali</p>
                            <p class="text-base font-black text-emerald-600 dark:text-emerald-400">{{ number_format($returnedCount) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-amber-500 uppercase">Belum Kembali</p>
                            <p class="text-base font-black text-amber-500">{{ number_format($notReturnedCount) }}</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.mass-leaves.checkin', $mass_leaf->id) }}"
                    class="w-full py-4 px-6 bg-purple-600 hover:bg-purple-500 active:bg-purple-700 text-white font-black text-sm sm:text-base rounded-2xl shadow-lg shadow-purple-600/25 hover:shadow-purple-500/40 transition-all flex items-center justify-center gap-2 group-hover:translate-x-0.5">
                    <span>Buka Halaman Kedatangan Santri</span>
                    <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Info Catatan Sistem -->
    <div class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-3xl border border-slate-200 dark:border-slate-700/80 flex items-start gap-4">
        <div class="w-10 h-10 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0 mt-0.5">
            <span class="material-symbols-outlined">info</span>
        </div>
        <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 space-y-1">
            <p class="font-bold text-slate-900 dark:text-white">Alur Kerja Pengurus Liburan Serentak:</p>
            <p>1. <strong>Sebelum Hari Libur:</strong> Masuk ke <a href="{{ route('admin.mass-leaves.checkout', $mass_leaf->id) }}" class="text-emerald-600 dark:text-emerald-400 font-bold underline">Modul 1 (ACC Kepulangan)</a> untuk memeriksa daftar santri tertahan karena masalah disiplin, dan lakukan <strong>Proses ACC Massal</strong>.</p>
            <p>2. <strong>Saat Hari Kedatangan:</strong> Masuk ke <a href="{{ route('admin.mass-leaves.checkin', $mass_leaf->id) }}" class="text-purple-600 dark:text-purple-400 font-bold underline">Modul 2 (Kedatangan Santri)</a> menggunakan tablet/laptop di pos gerbang untuk mencatat kedatangan santri dengan cepat menggunakan scanner atau pencarian nama.</p>
        </div>
    </div>
</div>
@endsection
