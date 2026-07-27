@extends('layouts.app')

@section('title', 'Dashboard')
@section('mobile_title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="rounded-2xl p-5 sm:p-6 mb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-4" style="background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 60%, #ede9fe 100%); border: 1px solid #bfdbfe;">
        <div>
            <h1 class="text-[#1e3a5f] text-lg sm:text-xl font-black tracking-tight mb-1">Dashboard Pengurus</h1>
            <p class="text-[#3b5f8a] text-sm font-normal max-w-2xl">
                Kelola seluruh sistem validasi izin dan kepulangan santri secara terpusat, monitor proses persetujuan lintas departemen, serta atur hak akses pengguna.
            </p>
        </div>
        <form method="GET" action="{{ route('admin.dashboard') }}" class="shrink-0">
            <select name="academic_year_id" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-sm font-semibold text-[#0d141b] bg-white border border-[#bfdbfe] rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm">
                @foreach($allAcademicYears as $year)
                    <option value="{{ $year->id }}" {{ $activeYear->id === $year->id ? 'selected' : '' }}>
                        Tahun Ajaran {{ $year->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if(isset($activeMassLeave) && $activeMassLeave && isset($massLeaveStats))
    {{-- Widget Liburan Serentak (Libur Massal Aktif) --}}
    <div class="bg-[#162032] bg-gradient-to-br from-[#1a263b] to-[#111827] rounded-2xl p-4 sm:p-5 mb-5 text-white shadow-lg relative overflow-hidden border border-slate-700/80">
        {{-- 1. Pola Geometris Islami / Pesantren Accent (Digeser ke kanan & di-masking agar kiri bersih) --}}
        <div class="absolute top-0 bottom-0 right-0 w-full sm:w-1/2 opacity-[0.035] pointer-events-none flex items-center justify-end overflow-hidden [mask-image:linear-gradient(to_left,white_40%,transparent_100%)]">
            <svg class="w-full h-full object-cover" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="currentColor">
                <pattern id="islamic-pattern-licensing" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M20 0 L25 15 L40 20 L25 25 L20 40 L15 25 L0 20 L15 15 Z" fill="none" stroke="currentColor" stroke-width="1.2"/>
                    <rect x="10" y="10" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1" transform="rotate(45 20 20)"/>
                    <circle cx="20" cy="20" r="3" fill="none" stroke="currentColor" stroke-width="0.8"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#islamic-pattern-licensing)" />
            </svg>
        </div>
        {{-- 2. Siluet Kubah Masjid Halus di Pojok Kanan Bawah (Opacity 4%) --}}
        <div class="absolute -right-4 -bottom-6 w-60 h-60 opacity-[0.04] pointer-events-none text-white overflow-hidden">
            <svg viewBox="0 0 200 200" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 20 C100 20 135 60 145 90 C155 120 160 130 160 160 L40 160 C40 130 45 120 55 90 C65 60 100 20 100 20 Z"/>
                <path d="M100 0 L100 20 M95 10 L105 10"/>
                <circle cx="100" cy="70" r="12" fill="none" stroke="currentColor" stroke-width="4"/>
            </svg>
        </div>
        
        @php
            $nowDate = \Carbon\Carbon::now()->startOfDay();
            $endDate = \Carbon\Carbon::parse($activeMassLeave->end_date)->startOfDay();
            $daysRemaining = $nowDate->diffInDays($endDate, false);
        @endphp
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 text-[11px] font-bold tracking-wide mb-2 shadow-inner">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Event Aktif
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight leading-tight">{{ $activeMassLeave->title }}</h2>
                <div class="inline-flex items-center gap-2 text-slate-300 font-medium text-xs mt-2 bg-slate-800/80 px-3 py-1 rounded-md border border-slate-700/60 shadow-sm">
                    <span class="material-symbols-outlined text-[16px] text-emerald-400">calendar_month</span>
                    <span>Periode: <strong class="text-white">{{ \Carbon\Carbon::parse($activeMassLeave->start_date)->locale('id')->translatedFormat('d F Y') }}</strong> s/d <strong class="text-white">{{ \Carbon\Carbon::parse($activeMassLeave->end_date)->locale('id')->translatedFormat('d F Y') }}</strong></span>
                </div>
            </div>
            <div class="flex items-center gap-4 shrink-0 w-full sm:w-auto justify-between sm:justify-end pt-2 sm:pt-0 border-t sm:border-t-0 border-white/10 sm:border-transparent">
                <div class="flex items-center gap-3 pr-2 sm:border-r sm:border-white/10 sm:py-1">
                    <div>
                        <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Status</span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Aktif
                        </span>
                    </div>
                    @if($daysRemaining >= 0)
                    <div class="pl-3 border-l border-white/10">
                        <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Sisa Waktu</span>
                        <span class="text-xs font-bold text-white mt-0.5 block">{{ $daysRemaining > 0 ? $daysRemaining . ' Hari Lagi' : 'Hari Ini' }}</span>
                    </div>
                    @endif
                </div>
                <a href="{{ route('admin.mass-leaves.show', $activeMassLeave->id) }}" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs rounded-lg transition-all shadow-sm hover:shadow shadow-emerald-600/20 hover:shadow-emerald-600/30 border border-emerald-400/30 flex items-center justify-center gap-1.5 shrink-0">
                    <span class="material-symbols-outlined text-[16px]">dashboard</span>
                    Detail Event
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-4 pt-4 border-t border-slate-700/70 relative z-10">
            <div class="bg-white/[0.07] hover:bg-white/[0.12] backdrop-blur-md rounded-xl p-3.5 border border-white/[0.12] hover:border-emerald-500/50 shadow-lg transition-all duration-200 group">
                <div class="flex items-center justify-between gap-2 mb-1.5">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-slate-300 transition-colors">Siap Pulang (Bersih)</span>
                    <div class="w-6 h-6 rounded-md bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                        <span class="material-symbols-outlined text-[14px]">verified</span>
                    </div>
                </div>
                <p class="text-xl sm:text-2xl font-black text-white leading-none">{{ number_format($massLeaveStats->eligibleCount) }} <span class="text-[11px] font-semibold text-emerald-400 ml-0.5">Santri</span></p>
            </div>
            <div class="bg-white/[0.07] hover:bg-white/[0.12] backdrop-blur-md rounded-xl p-3.5 border border-white/[0.12] hover:border-rose-500/50 shadow-lg transition-all duration-200 group">
                <div class="flex items-center justify-between gap-2 mb-1.5">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-slate-300 transition-colors">Tertahan (Kasus)</span>
                    <div class="w-6 h-6 rounded-md bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400 shrink-0">
                        <span class="material-symbols-outlined text-[14px]">gavel</span>
                    </div>
                </div>
                <p class="text-xl sm:text-2xl font-black text-white leading-none">{{ number_format($massLeaveStats->blockedCount) }} <span class="text-[11px] font-semibold text-rose-400 ml-0.5">Santri</span></p>
            </div>
            <div class="bg-white/[0.07] hover:bg-white/[0.12] backdrop-blur-md rounded-xl p-3.5 border border-white/[0.12] hover:border-blue-500/50 shadow-lg transition-all duration-200 group">
                <div class="flex items-center justify-between gap-2 mb-1.5">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-slate-300 transition-colors">Sudah ACC Pulang</span>
                    <div class="w-6 h-6 rounded-md bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 shrink-0">
                        <span class="material-symbols-outlined text-[14px]">logout</span>
                    </div>
                </div>
                <p class="text-xl sm:text-2xl font-black text-white leading-none">{{ number_format($massLeaveStats->checkedOutCount) }} <span class="text-[11px] font-semibold text-blue-400 ml-0.5">Santri</span></p>
            </div>
            <div class="bg-white/[0.07] hover:bg-white/[0.12] backdrop-blur-md rounded-xl p-3.5 border border-white/[0.12] hover:border-amber-500/50 shadow-lg transition-all duration-200 group">
                <div class="flex items-center justify-between gap-2 mb-1.5">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-slate-300 transition-colors">Sudah Kembali</span>
                    <div class="w-6 h-6 rounded-md bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0">
                        <span class="material-symbols-outlined text-[14px]">home</span>
                    </div>
                </div>
                <p class="text-xl sm:text-2xl font-black text-white leading-none">{{ number_format($massLeaveStats->returnedCount) }} <span class="text-[11px] font-semibold text-amber-400 ml-0.5">Santri</span></p>
            </div>
        </div>
    </div>
    @endif

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 mb-6">

        {{-- Jumlah Santri --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-blue-500 shadow-sm p-3.5 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between gap-2 mb-2">
                <p class="text-xl sm:text-2xl font-black text-[#0d141b] leading-none">{{ number_format($totalStudents) }}</p>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <span class="material-symbols-outlined text-[18px]">groups</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-[#0d141b] leading-tight truncate">Jumlah Santri</p>
                <p class="text-[10px] text-[#4c739a] leading-tight mt-0.5 truncate">Total Santri Aktif</p>
            </div>
        </div>

        {{-- Kepulangan --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-indigo-500 shadow-sm p-3.5 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between gap-2 mb-2">
                <p class="text-xl sm:text-2xl font-black text-[#0d141b] leading-none">{{ number_format($kepulangan) }}</p>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <span class="material-symbols-outlined text-[18px]">home</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-[#0d141b] leading-tight truncate">Izin Berjalan</p>
                <p class="text-[10px] text-[#4c739a] leading-tight mt-0.5 truncate">Hari ini</p>
            </div>
        </div>

        {{-- Disetujui --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-emerald-500 shadow-sm p-3.5 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between gap-2 mb-2">
                <p class="text-xl sm:text-2xl font-black text-[#0d141b] leading-none">{{ number_format($izinDisetujui) }}</p>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-[#0d141b] leading-tight truncate">Izin Disetujui</p>
                <p class="text-[10px] text-[#4c739a] leading-tight mt-0.5 truncate">Telah disetujui</p>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-amber-500 shadow-sm p-3.5 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between gap-2 mb-2">
                <p class="text-xl sm:text-2xl font-black text-[#0d141b] leading-none">{{ number_format($izinPending) }}</p>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <span class="material-symbols-outlined text-[18px]">schedule</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-[#0d141b] leading-tight truncate">Izin Dipending</p>
                <p class="text-[10px] text-[#4c739a] leading-tight mt-0.5 truncate">Menunggu Validasi</p>
            </div>
        </div>

        {{-- Ditolak --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-red-500 shadow-sm p-3.5 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between gap-2 mb-2">
                <p class="text-xl sm:text-2xl font-black text-[#0d141b] leading-none">{{ number_format($izinDitolak) }}</p>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
                    <span class="material-symbols-outlined text-[18px]">cancel</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-[#0d141b] leading-tight truncate">Izin Ditolak</p>
                <p class="text-[10px] text-[#4c739a] leading-tight mt-0.5 truncate">Telah ditolak</p>
            </div>
        </div>

        {{-- Kasus Darurat --}}
        <div class="bg-red-50 rounded-xl border border-red-200 border-l-4 border-l-red-600 shadow-sm p-3.5 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between gap-2 mb-2">
                <p class="text-xl sm:text-2xl font-black text-red-900 leading-none">{{ number_format($kasusDarurat) }}</p>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-red-900 leading-tight truncate">Kasus Darurat</p>
                <p class="text-[10px] text-red-700 leading-tight mt-0.5 truncate">Butuh respon cepat</p>
            </div>
        </div>
    </div>

    {{-- Perpanjangan Izin KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-blue-500 shadow-sm p-3.5 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between gap-2 mb-2">
                <p class="text-xl sm:text-2xl font-black text-[#0d141b] leading-none">{{ number_format($extTotal) }}</p>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <span class="material-symbols-outlined text-[18px]">assignment</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-[#0d141b] leading-tight truncate">Total Perpanjangan</p>
                <p class="text-[10px] text-[#4c739a] leading-tight mt-0.5 truncate">Semua Pengajuan</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-emerald-500 shadow-sm p-3.5 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between gap-2 mb-2">
                <p class="text-xl sm:text-2xl font-black text-[#0d141b] leading-none">{{ number_format($extApproved) }}</p>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-[#0d141b] leading-tight truncate">Perpanjangan Disetujui</p>
                <p class="text-[10px] text-[#4c739a] leading-tight mt-0.5 truncate">Telah disetujui</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-amber-500 shadow-sm p-3.5 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between gap-2 mb-2">
                <p class="text-xl sm:text-2xl font-black text-[#0d141b] leading-none">{{ number_format($extPending) }}</p>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <span class="material-symbols-outlined text-[18px]">schedule</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-[#0d141b] leading-tight truncate">Perpanjangan Pending</p>
                <p class="text-[10px] text-[#4c739a] leading-tight mt-0.5 truncate">Menunggu Validasi</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-red-500 shadow-sm p-3.5 flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between gap-2 mb-2">
                <p class="text-xl sm:text-2xl font-black text-[#0d141b] leading-none">{{ number_format($extRejected) }}</p>
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
                    <span class="material-symbols-outlined text-[18px]">cancel</span>
                </div>
            </div>
            <div>
                <p class="text-xs font-bold text-[#0d141b] leading-tight truncate">Perpanjangan Ditolak</p>
                <p class="text-[10px] text-[#4c739a] leading-tight mt-0.5 truncate">Telah ditolak</p>
            </div>
        </div>
    </div>

    {{-- Chart Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Top 10 Santri Paling Banyak Izin</h3>
            <div id="chartTopLicenses" class="min-h-[300px]"></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Top 10 Santri Paling Banyak Melanggar</h3>
            <div id="chartTopStudentViolations" class="min-h-[300px]"></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Tren Pengajuan Izin ({{ $activeYear->name }})</h3>
            <div id="chartLicenseTrend" class="min-h-[300px]"></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Tren Pelanggaran ({{ $activeYear->name }})</h3>
            <div id="chartViolationTrend" class="min-h-[300px]"></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Kategori Pelanggaran</h3>
            <div id="chartViolationCat" class="min-h-[300px]"></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Top 5 Rayon Pelanggaran Terbanyak</h3>
            <div id="chartTopRayons" class="min-h-[300px]"></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5 lg:col-span-2">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Sebaran Santri per Rayon</h3>
            <div id="chartDemographics" class="min-h-[300px]"></div>
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



@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartData = @json($chartData ?? []);
        if (Object.keys(chartData).length === 0) return;
        
        // Common Options
        const commonOptions = {
            chart: {
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f97316', '#64748b'],
            dataLabels: { enabled: false },
            tooltip: { theme: 'light' }
        };



        // 2. Tren Pengajuan Izin (Area)
        if (document.querySelector("#chartLicenseTrend")) {
            new ApexCharts(document.querySelector("#chartLicenseTrend"), {
                ...commonOptions,
                chart: { type: 'area', height: 320 },
                series: [{ name: 'Jumlah Izin', data: chartData.licenseTrend.series }],
                xaxis: { categories: chartData.licenseTrend.labels },
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#0ea5e9'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } }
            }).render();
        }

        // 3. Top Santri Izin (Bar)
        if (document.querySelector("#chartTopLicenses")) {
            new ApexCharts(document.querySelector("#chartTopLicenses"), {
                ...commonOptions,
                chart: { type: 'bar', height: 320 },
                series: [{ name: 'Total Izin', data: chartData.topStudentLicenses.series }],
                xaxis: { categories: chartData.topStudentLicenses.labels },
                colors: ['#0ea5e9'],
                plotOptions: { bar: { borderRadius: 4, horizontal: true } }
            }).render();
        }
        
        // Top Santri Melanggar (Bar)
        if (document.querySelector("#chartTopStudentViolations")) {
            new ApexCharts(document.querySelector("#chartTopStudentViolations"), {
                ...commonOptions,
                chart: { type: 'bar', height: 320 },
                series: [{ name: 'Total Pelanggaran', data: chartData.topStudentViolations.series }],
                xaxis: { categories: chartData.topStudentViolations.labels },
                colors: ['#ef4444'],
                plotOptions: { bar: { borderRadius: 4, horizontal: true } }
            }).render();
        }

        // 4. Kategori Pelanggaran (Doughnut)
        if (document.querySelector("#chartViolationCat")) {
            new ApexCharts(document.querySelector("#chartViolationCat"), {
                ...commonOptions,
                chart: { type: 'donut', height: 320 },
                series: chartData.violationCat.series,
                labels: chartData.violationCat.labels,
                colors: ['#3b82f6', '#f59e0b', '#ef4444'],
            }).render();
        }

        // 5. Tren Pelanggaran (Area)
        if (document.querySelector("#chartViolationTrend")) {
            new ApexCharts(document.querySelector("#chartViolationTrend"), {
                ...commonOptions,
                chart: { type: 'area', height: 320 },
                series: [{ name: 'Pelanggaran', data: chartData.violationTrend.series }],
                xaxis: { categories: chartData.violationTrend.labels },
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#ef4444'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } }
            }).render();
        }

        // 6. Top 5 Rayon Pelanggaran (Horizontal Bar)
        if (document.querySelector("#chartTopRayons")) {
            new ApexCharts(document.querySelector("#chartTopRayons"), {
                ...commonOptions,
                chart: { type: 'bar', height: 320 },
                series: [{ name: 'Pelanggaran', data: chartData.topRayons.series }],
                xaxis: { categories: chartData.topRayons.labels },
                colors: ['#f43f5e'],
                plotOptions: { bar: { borderRadius: 4, horizontal: true } }
            }).render();
        }



        // 8. Sebaran Santri per Rayon (Bar)
        if (document.querySelector("#chartDemographics")) {
            new ApexCharts(document.querySelector("#chartDemographics"), {
                ...commonOptions,
                chart: { type: 'bar', height: 320 },
                series: [{ name: 'Santri Aktif', data: chartData.demographics.series }],
                xaxis: { categories: chartData.demographics.labels },
                colors: ['#0ea5e9'],
                plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } }
            }).render();
        }
    });
</script>
@endpush
