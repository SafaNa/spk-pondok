@extends('layouts.guardian')

@section('title', 'Dashboard')
@section('mobile_title', 'Dashboard')

@section('content')

    <div class="rounded-2xl p-5 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <h1 class="text-xl font-black text-[#0d141b] dark:text-white mb-0.5">Selamat Datang, {{ $guardian->name }}</h1>
        <p class="text-sm text-[#4c739a]">Pantau status izin dan kelola pengajuan untuk santri Anda.</p>
    </div>

    @if(isset($activeMassLeave) && $activeMassLeave)
        {{-- 1. Alert Merah Tanggungan Pelanggaran (Jika Anak Tertahan) --}}
        @if(isset($blockedMassLeaveStudents) && $blockedMassLeaveStudents->isNotEmpty())
            <div class="mb-6 space-y-3">
                @foreach($blockedMassLeaveStudents as $blockedStudent)
                <div class="bg-red-50 dark:bg-red-900/30 border-2 border-red-500 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-md animate-[pulse_3s_infinite]">
                    <div class="flex items-start sm:items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-800 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-300 text-[28px]">gavel</span>
                        </div>
                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-red-200 dark:bg-red-800 text-red-900 dark:text-red-200 text-[11px] font-black uppercase mb-1">Peringatan Kepulangan</span>
                            <p class="text-red-900 dark:text-red-200 font-bold text-sm sm:text-base leading-snug">
                                Mohon Maaf, Ananda <strong class="underline">{{ $blockedStudent->name }}</strong> belum bisa mengikuti kepulangan libur massal karena masih memiliki tanggungan pelanggaran yang belum diselesaikan di pengurus.
                            </p>
                            <p class="text-red-700 dark:text-red-300 text-xs mt-1 font-medium">
                                Silakan hubungi pengurus pondok atau pantau menu Pelanggaran untuk menyelesaikan tanggungan sanksi/denda.
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        {{-- 2. Banner Informasi Liburan Serentak (Libur Massal) --}}
        <div class="rounded-2xl p-4 sm:p-5 mb-5 text-white shadow-lg relative overflow-hidden bg-[#162032] bg-gradient-to-br from-[#1a263b] to-[#111827] border border-slate-700/80">
            {{-- 1. Pola Geometris Islami / Pesantren Accent (Digeser ke kanan & di-masking agar kiri bersih) --}}
            <div class="absolute top-0 bottom-0 right-0 w-full sm:w-1/2 opacity-[0.035] pointer-events-none flex items-center justify-end overflow-hidden [mask-image:linear-gradient(to_left,white_40%,transparent_100%)]">
                <svg class="w-full h-full object-cover" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="currentColor">
                    <pattern id="islamic-pattern-guardian" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M20 0 L25 15 L40 20 L25 25 L20 40 L15 25 L0 20 L15 15 Z" fill="none" stroke="currentColor" stroke-width="1.2"/>
                        <rect x="10" y="10" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1" transform="rotate(45 20 20)"/>
                        <circle cx="20" cy="20" r="3" fill="none" stroke="currentColor" stroke-width="0.8"/>
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#islamic-pattern-guardian)" />
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
                <div class="flex items-start gap-3.5">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-emerald-500/15 backdrop-blur-md flex items-center justify-center shrink-0 border border-emerald-500/30 shadow-inner">
                        <span class="material-symbols-outlined text-[26px] sm:text-[28px] text-emerald-400">flight_takeoff</span>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 text-[11px] font-bold tracking-wide mb-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Pengumuman Liburan Pondok
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-white tracking-tight leading-tight">{{ $activeMassLeave->title }}</h2>
                        <div class="inline-flex flex-wrap items-center gap-1.5 text-slate-300 font-medium text-xs mt-2 bg-slate-800/80 px-3 py-1 rounded-md border border-slate-700/60 shadow-sm">
                            <span class="material-symbols-outlined text-[16px] text-emerald-400">calendar_month</span>
                            <span>Kepulangan: <strong class="text-white">{{ \Carbon\Carbon::parse($activeMassLeave->start_date)->locale('id')->translatedFormat('d F Y') }}</strong> &bull; Kembali: <strong class="text-white">{{ \Carbon\Carbon::parse($activeMassLeave->end_date)->locale('id')->translatedFormat('d F Y') }}</strong></span>
                        </div>
                    </div>
                </div>
                
                {{-- 1. Info Sisa Hari di Sisi Kanan Atas --}}
                @if($daysRemaining >= 0)
                <div class="bg-white/[0.07] hover:bg-white/[0.12] backdrop-blur-md px-4 py-2 rounded-xl border border-white/[0.12] text-center sm:text-right shrink-0 shadow-sm transition-all w-full sm:w-auto">
                    <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Sisa Waktu Liburan</span>
                    <span class="text-sm font-black text-emerald-400 mt-0.5 flex items-center justify-center sm:justify-end gap-1">
                        <span class="material-symbols-outlined text-[18px]">timer</span>
                        {{ $daysRemaining > 0 ? $daysRemaining . ' Hari Lagi' : 'Hari Ini Kembali' }}
                    </span>
                </div>
                @endif
            </div>

            {{-- 2. Grid Responsif Status Kepulangan Anak (Mendukung Banyak Anak / Kakak Beradik) --}}
            <div class="mt-4 pt-3.5 border-t border-slate-700/80 relative z-10">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-emerald-400">family_restroom</span>
                        Status Kepulangan Putra/Putri Anda:
                    </span>
                    <span class="text-[11px] font-semibold text-slate-300 bg-slate-800/80 px-2 py-0.5 rounded border border-slate-700/80">Total: {{ $students->count() }} Santri</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($students as $child)
                        @php
                            $isBlocked = isset($blockedMassLeaveStudents) && $blockedMassLeaveStudents->contains('id', $child->id);
                            $isCheckedOut = isset($checkedOutMassLeaveStudents) && $checkedOutMassLeaveStudents->contains('id', $child->id);
                        @endphp
                        <div class="bg-white/[0.07] hover:bg-white/[0.12] backdrop-blur-md p-3.5 rounded-xl border {{ $isBlocked ? 'border-rose-500/50 hover:border-rose-400 bg-rose-500/10' : ($isCheckedOut ? 'border-blue-500/50 hover:border-blue-400 bg-blue-500/10' : 'border-emerald-500/50 hover:border-emerald-400 bg-emerald-500/10') }} transition-all flex items-center justify-between gap-3 shadow-sm group">
                            <div class="text-left w-full">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="text-xs font-bold text-white truncate group-hover:text-slate-200 transition-colors">{{ $child->name }}</span>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $isBlocked ? 'bg-rose-500/20 text-rose-300' : ($isCheckedOut ? 'bg-blue-500/20 text-blue-300' : 'bg-emerald-500/20 text-emerald-300') }} shrink-0">
                                        {{ $isBlocked ? 'Tertahan' : ($isCheckedOut ? 'Sudah Pulang' : 'Siap Pulang') }}
                                    </span>
                                </div>
                                <span class="text-[11px] font-medium {{ $isBlocked ? 'text-rose-300' : ($isCheckedOut ? 'text-blue-300' : 'text-emerald-300') }} flex items-center gap-1 mt-0.5">
                                    <span class="material-symbols-outlined text-[15px] shrink-0">
                                        {{ $isBlocked ? 'gavel' : ($isCheckedOut ? 'check_circle' : 'verified') }}
                                    </span>
                                    <span class="truncate">{{ $isBlocked ? 'Ada tanggungan pelanggaran' : ($isCheckedOut ? 'Sudah Di-ACC Kepulangan' : 'Bersih dari pelanggaran') }}</span>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Smart Alert: Izin Hampir Habis / Terlambat --}}
    @php
        $expiringLicenses = collect();
        if(isset($recentLicenses)) {
            $expiringLicenses = $recentLicenses->filter(function($license) {
                if ($license->status === 'approved' && !$license->actual_return_date) {
                    $daysRemaining = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($license->end_date)->startOfDay(), false);
                    return $daysRemaining <= 1; // Besok, hari ini, atau telat
                }
                return false;
            });
        }
    @endphp

    @if($expiringLicenses->isNotEmpty())
        <div class="mb-6 space-y-3">
            @foreach($expiringLicenses as $expLicense)
                @php
                    $daysRemaining = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($expLicense->end_date)->startOfDay(), false);
                    $isLate = $daysRemaining < 0;
                    $statusText = $isLate ? "telah Habis (Terlambat " . abs($daysRemaining) . " Hari)" : ($daysRemaining === 0 ? "Habis Hari Ini" : "akan Habis Besok");
                    $bgClass = $isLate ? "bg-red-50 border-red-200" : "bg-amber-50 border-amber-200";
                    $textClass = $isLate ? "text-red-800" : "text-amber-800";
                    $iconClass = $isLate ? "text-red-500" : "text-amber-500";
                @endphp
                <div class="{{ $bgClass }} border rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined {{ $iconClass }} text-[24px]">warning</span>
                        <div>
                            <p class="{{ $textClass }} font-bold text-sm">Peringatan: Masa Izin {{ $statusText }}</p>
                            <p class="{{ $textClass }} text-xs opacity-90">Izin kepulangan untuk <strong>{{ $expLicense->student->name }}</strong> jatuh tempo pada {{ \Carbon\Carbon::parse($expLicense->end_date)->locale('id')->translatedFormat('d F Y') }}.</p>
                        </div>
                    </div>
                    <a href="{{ route('guardian.licenses.extend', $expLicense) }}" class="shrink-0 flex items-center justify-center gap-1.5 px-4 py-2 bg-white hover:bg-slate-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-200 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">more_time</span>
                        Ajukan Perpanjangan
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    {{-- KPI --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 border-l-4 border-l-blue-500">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">assignment</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $totalLicenses }}</p>
            <p class="text-xs text-[#4c739a]">Total Pengajuan Izin</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 border-l-4 border-l-green-500">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 text-green-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $approvedCount }}</p>
            <p class="text-xs text-[#4c739a]">Izin Disetujui</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 border-l-4 border-l-amber-500">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">schedule</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $pendingCount }}</p>
            <p class="text-xs text-[#4c739a]">Izin Pending</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 border-l-4 border-l-red-500">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">cancel</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $rejectedCount }}</p>
            <p class="text-xs text-[#4c739a]">Izin Ditolak</p>
        </div>
    </div>

    {{-- Perpanjangan Izin KPI --}}
    <h2 class="text-sm font-bold text-[#0d141b] dark:text-white mb-3 mt-2 flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px] text-purple-500">more_time</span>
        Statistik Perpanjangan Izin
    </h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 border-l-4 border-l-blue-500">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">assignment</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $extTotal }}</p>
            <p class="text-xs text-[#4c739a]">Total Perpanjangan</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 border-l-4 border-l-green-500">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 text-green-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $extApproved }}</p>
            <p class="text-xs text-[#4c739a]">Perpanjangan Disetujui</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 border-l-4 border-l-amber-500">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">schedule</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $extPending }}</p>
            <p class="text-xs text-[#4c739a]">Perpanjangan Pending</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4 border-l-4 border-l-red-500">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">cancel</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $extRejected }}</p>
            <p class="text-xs text-[#4c739a]">Perpanjangan Ditolak</p>
        </div>
    </div>

    {{-- Students --}}
    <div class="mb-6">
        <h2 class="text-sm font-bold text-[#0d141b] dark:text-white mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-primary">group</span>
            Data Santri ({{ $students->count() }})
        </h2>
        @if($students->isEmpty())
            <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">info</span>
                Belum ada santri yang terdaftar untuk akun ini. Hubungi admin pondok.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($students as $student)
                    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-5">
                        @php
                            $colors   = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                            $color    = $colors[crc32($student->id) % count($colors)];
                            $nameParts = explode(' ', trim($student->name));
                            $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : (strlen($nameParts[0]) > 1 ? substr($nameParts[0], 1, 1) : '')));
                        @endphp
                        <div class="flex items-start gap-3">
                            @if($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}"
                                     alt="{{ $student->name }}"
                                     class="h-10 w-10 rounded-full object-cover shrink-0">
                            @else
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 text-sm font-bold">
                                    {{ $initials }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-[#0d141b] dark:text-white text-sm truncate">{{ $student->name }}</p>
                                <p class="text-xs text-[#4c739a] mb-3">{{ $student->identifier_label }}: {{ $student->nis ?? '-' }}</p>
                                <div class="grid grid-cols-2 gap-y-3 gap-x-2 text-xs">
                                    <div>
                                        <p class="text-[#4c739a]">Rayon</p>
                                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->rayon?->name ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[#4c739a]">Kamar</p>
                                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->room?->name ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[#4c739a]">Pend. Diniyah</p>
                                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->religiousEducation?->name ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[#4c739a]">Pend. Formal</p>
                                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->formalEducation?->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold shrink-0
                                {{ $student->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $student->status === 'active' ? 'Aktif' : ucfirst($student->status ?? '-') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Recent Licenses --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-[#e7edf3] dark:border-slate-700">
            <h3 class="text-sm font-bold text-[#0d141b] dark:text-white">Pengajuan Terbaru</h3>
            <a href="{{ route('guardian.licenses.index') }}" class="text-xs font-semibold text-primary hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                    <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Santri</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Alasan</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Tanggal</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase text-center">Status</th>
                    <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                @forelse($recentLicenses as $license)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium text-[#0d141b] dark:text-white whitespace-nowrap">{{ $license->student?->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-sm text-[#0d141b] dark:text-white max-w-xs truncate">{{ $license->leaveReason?->reason ?? '-' }}</td>
                        <td class="px-5 py-3 text-sm text-[#4c739a] whitespace-nowrap">
                            {{ $license->start_date->locale('id')->translatedFormat('d M Y') }} – {{ $license->end_date->locale('id')->translatedFormat('d M Y') }}
                            @if($license->status === 'approved' && !$license->actual_return_date)
                                @php $ext = $license->extensions->where('status','pending')->first(); @endphp
                                @if($ext)
                                    <span class="ml-1 inline-flex px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700">+Perpanjangan</span>
                                @endif
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            @if($license->status === 'pending')
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Menunggu</span>
                            @elseif($license->status === 'approved')
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Disetujui</span>
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Tombol Detail (Muncul untuk semua status) --}}
                                <a href="{{ route('guardian.licenses.show', $license) }}" title="Lihat Detail"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>

                                @if($license->status === 'pending')
                                    {{-- Tombol Edit (Hanya jika Menunggu) --}}
                                    <a href="{{ route('guardian.licenses.edit', $license) }}" title="Edit Pengajuan"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    
                                    {{-- Tombol Hapus (Hanya jika Menunggu) --}}
                                    <form action="{{ route('guardian.licenses.destroy', $license) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan izin ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Batalkan Pengajuan"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                @endif

                                @if($license->status === 'approved' && !$license->actual_return_date)
                                    <a href="{{ route('guardian.licenses.extend', $license) }}" title="Tambah Perpanjangan"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">more_time</span>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-sm text-[#4c739a]">
                            <span class="material-symbols-outlined text-3xl block mb-2 text-slate-300">assignment</span>
                            Belum ada pengajuan izin.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        @if($recentLicenses->isEmpty())
        <div class="px-5 py-4 border-t border-[#e7edf3] dark:border-slate-700">
            <a href="{{ route('guardian.licenses.create') }}"
                class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-primary/10 hover:bg-primary/20 text-primary text-sm font-semibold transition-colors">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Ajukan Izin Sekarang
            </a>
        </div>
        @endif
    </div>

@endsection
