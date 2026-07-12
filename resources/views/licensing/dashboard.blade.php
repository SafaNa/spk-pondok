@extends('layouts.app')

@section('title', 'Dashboard')
@section('mobile_title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="rounded-2xl p-5 sm:p-6 mb-3" style="background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 60%, #ede9fe 100%); border: 1px solid #bfdbfe;">
        <h1 class="text-[#1e3a5f] text-lg sm:text-xl font-black tracking-tight mb-1">Dashboard Pengurus Perizinan</h1>
        <p class="text-[#3b5f8a] text-sm font-normal max-w-2xl">
            Kelola seluruh sistem validasi izin dan kepulangan santri secara terpusat, monitor proses persetujuan lintas departemen, serta atur hak akses pengguna.
        </p>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-3 lg:grid-cols-6 gap-3 mb-6">

        {{-- Jumlah Santri --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <span class="material-symbols-outlined text-[20px]">groups</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($totalStudents) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Jumlah Santri</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Total Santri Aktif</p>
            </div>
        </div>

        {{-- Kepulangan --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <span class="material-symbols-outlined text-[20px]">home</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($kepulangan) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Kepulangan</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Santri pulang</p>
            </div>
        </div>

        {{-- Disetujui --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($izinDisetujui) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Izin Disetujui</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Telah disetujui</p>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <span class="material-symbols-outlined text-[20px]">schedule</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($izinPending) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Izin Dipending</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Menunggu Validasi</p>
            </div>
        </div>

        {{-- Ditolak --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                <span class="material-symbols-outlined text-[20px]">cancel</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($izinDitolak) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Izin Ditolak</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Izin ditolak</p>
            </div>
        </div>

        {{-- Kasus Darurat --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                <span class="material-symbols-outlined text-[20px]">emergency</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($kasusDarurat) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Kasus Darurat</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Pengajuan Darurat</p>
            </div>
        </div>

    </div>

    {{-- Bottom Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Pengajuan Izin Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-[#e7edf3] overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#e7edf3]">
                <h3 class="text-sm font-bold text-[#0d141b]">Pengajuan Izin Terbaru</h3>
                <a href="{{ route('admin.licenses.index') }}"
                    class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                    Lihat Semua
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[#f8fafc] border-b border-[#e7edf3]">
                            <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Nama Santri</th>
                            <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Alasan</th>
                            <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase whitespace-nowrap">Tanggal Pengajuan</th>
                            <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e7edf3]">
                        @forelse($recentLicenses as $license)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3 text-sm font-semibold text-[#0d141b] whitespace-nowrap">{{ $license->student?->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm text-[#4c739a] max-w-[160px] truncate">{{ $license->description ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm text-[#4c739a] whitespace-nowrap">{{ $license->created_at->format('d M Y H.i') }}</td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    @if($license->is_emergency && $license->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-violet-100 text-violet-700 border border-violet-200">Darurat</span>
                                    @elseif($license->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">Dipending</span>
                                    @elseif($license->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Disetujui</span>
                                    @elseif($license->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-pink-100 text-pink-700 border border-pink-200">Ditolak</span>
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
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-[#e7edf3] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7edf3]">
                <h3 class="text-sm font-bold text-[#0d141b]">Peringatan & Notifikasi</h3>
            </div>
            <ul class="divide-y divide-[#e7edf3]">

                {{-- Poin kepulangan hampir habis --}}
                @foreach($quotaWarnings as $warn)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="material-symbols-outlined text-[20px] text-amber-500 shrink-0">notifications</span>
                        <span class="text-sm text-[#0d141b]">
                            <span class="font-semibold">{{ $warn->name }}</span>
                            <span class="text-[#4c739a]"> - Poin Kepulangan Hampir Habis ({{ $warn->used_count }}/{{ $warn->max_leaves }})</span>
                        </span>
                    </li>
                @endforeach

                {{-- Kasus darurat per santri --}}
                @foreach($recentLicenses->where('is_emergency', true)->where('status', 'pending')->take(3) as $darurat)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="material-symbols-outlined text-[20px] text-red-500 shrink-0">emergency</span>
                        <span class="text-sm text-[#0d141b]">
                            <span class="font-semibold">{{ $darurat->student?->name ?? '-' }}</span>
                            <span class="text-[#4c739a]"> - Pengajuan Darurat ({{ $darurat->description ?? 'Darurat' }})</span>
                        </span>
                    </li>
                @endforeach

                {{-- Santri dengan pelanggaran aktif --}}
                @foreach($violationNotifs as $student)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="material-symbols-outlined text-[20px] text-red-500 shrink-0">report</span>
                        <span class="text-sm text-[#0d141b]">
                            <span class="font-semibold">{{ $student->name }}</span>
                            <span class="text-[#4c739a]"> - Memiliki Pelanggaran Aktif</span>
                        </span>
                    </li>
                @endforeach

                {{-- Total izin pending --}}
                @if($izinPending > 0)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="material-symbols-outlined text-[20px] text-amber-500 shrink-0">warning</span>
                        <span class="text-sm text-[#0d141b]">
                            <span class="font-semibold">{{ $izinPending }}</span>
                            <span class="text-[#4c739a]"> Pengajuan Izin Menunggu Validasi</span>
                        </span>
                    </li>
                @endif

                @if($quotaWarnings->isEmpty() && $recentLicenses->where('is_emergency', true)->where('status', 'pending')->isEmpty() && $violationNotifs->isEmpty() && $izinPending === 0)
                    <li class="flex flex-col items-center justify-center px-5 py-10 text-center">
                        <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">notifications_off</span>
                        <p class="text-sm text-[#4c739a]">Tidak ada peringatan saat ini.</p>
                    </li>
                @endif

            </ul>

            @if($izinPending > 0 || $kasusDarurat > 0)
                <div class="px-5 py-3 border-t border-[#e7edf3]">
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
