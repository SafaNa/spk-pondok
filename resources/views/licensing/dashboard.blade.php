@extends('layouts.app')

@section('title', 'Dashboard Perizinan')
@section('mobile_title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="rounded-2xl p-5 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <h1 class="text-[#0d141b] dark:text-white text-xl sm:text-2xl font-black tracking-tight mb-1">Dashboard Pengurus Perizinan</h1>
        <p class="text-[#4c739a] text-sm font-normal max-w-2xl">
            Kelola seluruh sistem validasi izin dan kepulangan santri secara terpusat, monitor proses persetujuan, serta pantau status perizinan santri.
        </p>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">

        {{-- Jumlah Santri --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 flex flex-col items-start gap-2">
            <div class="flex items-center gap-2 w-full">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-500 shrink-0">
                    <span class="material-symbols-outlined text-[20px]">groups</span>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-[#0d141b] dark:text-white leading-tight">{{ number_format($totalStudents) }}</p>
                <p class="text-xs font-semibold text-[#0d141b] dark:text-white">Jumlah Santri</p>
                <p class="text-[10px] text-[#4c739a]">Total Santri Aktif</p>
            </div>
        </div>

        {{-- Kepulangan Santri --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 flex flex-col items-start gap-2">
            <div class="flex items-center gap-2 w-full">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-500 shrink-0">
                    <span class="material-symbols-outlined text-[20px]">home</span>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-[#0d141b] dark:text-white leading-tight">{{ number_format($kepulangan) }}</p>
                <p class="text-xs font-semibold text-[#0d141b] dark:text-white">Kepulangan Santri</p>
                <p class="text-[10px] text-[#4c739a]">Jumlah santri pulang</p>
            </div>
        </div>

        {{-- Izin Disetujui --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 flex flex-col items-start gap-2">
            <div class="flex items-center gap-2 w-full">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/20 text-green-500 shrink-0">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-[#0d141b] dark:text-white leading-tight">{{ number_format($izinDisetujui) }}</p>
                <p class="text-xs font-semibold text-[#0d141b] dark:text-white">Izin Disetujui</p>
                <p class="text-[10px] text-[#4c739a]">Telah disetujui</p>
            </div>
        </div>

        {{-- Izin Dipending --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 flex flex-col items-start gap-2">
            <div class="flex items-center gap-2 w-full">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-500 shrink-0">
                    <span class="material-symbols-outlined text-[20px]">schedule</span>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-[#0d141b] dark:text-white leading-tight">{{ number_format($izinPending) }}</p>
                <p class="text-xs font-semibold text-[#0d141b] dark:text-white">Izin Dipending</p>
                <p class="text-[10px] text-[#4c739a]">Menunggu Validasi</p>
            </div>
        </div>

        {{-- Izin Ditolak --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 flex flex-col items-start gap-2">
            <div class="flex items-center gap-2 w-full">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 shrink-0">
                    <span class="material-symbols-outlined text-[20px]">cancel</span>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-[#0d141b] dark:text-white leading-tight">{{ number_format($izinDitolak) }}</p>
                <p class="text-xs font-semibold text-[#0d141b] dark:text-white">Izin Ditolak</p>
                <p class="text-[10px] text-[#4c739a]">Izin ditolak</p>
            </div>
        </div>

        {{-- Kasus Darurat --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 flex flex-col items-start gap-2">
            <div class="flex items-center gap-2 w-full">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-500 shrink-0">
                    <span class="material-symbols-outlined text-[20px]">emergency</span>
                </div>
            </div>
            <div>
                <p class="text-2xl font-black text-[#0d141b] dark:text-white leading-tight">{{ number_format($kasusDarurat) }}</p>
                <p class="text-xs font-semibold text-[#0d141b] dark:text-white">Kasus Darurat</p>
                <p class="text-[10px] text-[#4c739a]">Pengajuan Darurat</p>
            </div>
        </div>

    </div>

    {{-- Bottom Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Pengajuan Izin Terbaru --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#e7edf3] dark:border-slate-700">
                <div>
                    <h3 class="text-sm font-bold text-[#0d141b] dark:text-white">Pengajuan Izin Terbaru</h3>
                    <p class="text-xs text-[#4c739a] mt-0.5">Daftar pengajuan izin yang masuk baru-baru ini</p>
                </div>
                <a href="{{ route('admin.licenses.index') }}"
                    class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                    Lihat Semua
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                            <th class="px-5 py-3 text-xs font-semibold tracking-wide text-[#4c739a] uppercase">Nama Santri</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wide text-[#4c739a] uppercase">Alasan</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">Tanggal Pengajuan</th>
                            <th class="px-5 py-3 text-xs font-semibold tracking-wide text-[#4c739a] uppercase text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                        @forelse($recentLicenses as $license)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-5 py-3 text-sm font-medium text-[#0d141b] dark:text-white whitespace-nowrap">
                                    {{ $license->student?->name ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-sm text-[#4c739a] whitespace-nowrap">
                                    {{ $license->description ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-sm text-[#4c739a] whitespace-nowrap">
                                    {{ $license->created_at->format('d M Y H.i') }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($license->type === 'individual' && $license->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">Darurat</span>
                                    @elseif($license->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">Dipending</span>
                                    @elseif($license->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Disetujui</span>
                                    @elseif($license->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-[#4c739a]">
                                    <span class="material-symbols-outlined text-3xl block mb-2 text-slate-300">assignment</span>
                                    Belum ada pengajuan izin.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Peringatan & Notifikasi --}}
        <div class="lg:col-span-1 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7edf3] dark:border-slate-700">
                <h3 class="text-sm font-bold text-[#0d141b] dark:text-white">Peringatan & Notifikasi</h3>
                <p class="text-xs text-[#4c739a] mt-0.5">Santri yang perlu perhatian</p>
            </div>
            <ul class="divide-y divide-[#e7edf3] dark:divide-slate-700">

                @if($izinPending > 0)
                    <li class="flex items-start gap-3 px-5 py-3.5">
                        <span class="material-symbols-outlined text-[20px] text-amber-500 mt-0.5 shrink-0">warning</span>
                        <span class="text-sm text-[#0d141b] dark:text-white">
                            <span class="font-semibold">{{ $izinPending }}</span> Pengajuan Izin Menunggu Validasi
                        </span>
                    </li>
                @endif

                @if($kasusDarurat > 0)
                    <li class="flex items-start gap-3 px-5 py-3.5">
                        <span class="material-symbols-outlined text-[20px] text-purple-500 mt-0.5 shrink-0">emergency</span>
                        <span class="text-sm text-[#0d141b] dark:text-white">
                            <span class="font-semibold">{{ $kasusDarurat }}</span> Kasus Darurat Perlu Ditangani
                        </span>
                    </li>
                @endif

                @forelse($violationNotifs as $student)
                    <li class="flex items-start gap-3 px-5 py-3.5">
                        <span class="material-symbols-outlined text-[20px] text-red-500 mt-0.5 shrink-0">report</span>
                        <span class="text-sm text-[#0d141b] dark:text-white">
                            {{ $student->name }} - Memiliki Pelanggaran Aktif
                        </span>
                    </li>
                @empty
                @endforelse

                @if($izinPending === 0 && $kasusDarurat === 0 && $violationNotifs->isEmpty())
                    <li class="flex flex-col items-center justify-center px-5 py-10 text-center">
                        <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-2">notifications_off</span>
                        <p class="text-sm text-[#4c739a]">Tidak ada peringatan saat ini.</p>
                    </li>
                @endif

            </ul>

            @if($izinPending > 0 || $kasusDarurat > 0)
                <div class="px-5 py-3 border-t border-[#e7edf3] dark:border-slate-700">
                    <a href="{{ route('admin.licenses.index') }}"
                        class="flex items-center justify-center gap-2 w-full py-2 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-sm font-semibold transition-colors">
                        <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                        Proses Validasi Sekarang
                    </a>
                </div>
            @endif
        </div>

    </div>

@endsection
