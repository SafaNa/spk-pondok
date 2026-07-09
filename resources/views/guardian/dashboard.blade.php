@extends('layouts.guardian')

@section('title', 'Dashboard')
@section('mobile_title', 'Dashboard')

@section('content')

    <div class="rounded-2xl p-5 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <h1 class="text-xl font-black text-[#0d141b] dark:text-white mb-0.5">Selamat Datang, {{ $guardian->name }}</h1>
        <p class="text-sm text-[#4c739a]">Pantau status izin dan kelola pengajuan untuk santri Anda.</p>
    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">assignment</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $totalLicenses }}</p>
            <p class="text-xs text-[#4c739a]">Total Pengajuan</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 text-green-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $approvedCount }}</p>
            <p class="text-xs text-[#4c739a]">Disetujui</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">schedule</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $pendingCount }}</p>
            <p class="text-xs text-[#4c739a]">Menunggu</p>
        </div>
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-500 mb-2">
                <span class="material-symbols-outlined text-[20px]">cancel</span>
            </div>
            <p class="text-2xl font-black text-[#0d141b] dark:text-white">{{ $rejectedCount }}</p>
            <p class="text-xs text-[#4c739a]">Ditolak</p>
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
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-sm shrink-0">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-[#0d141b] dark:text-white text-sm truncate">{{ $student->name }}</p>
                                <p class="text-xs text-[#4c739a] mb-3">NIS: {{ $student->nis ?? '-' }}</p>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <p class="text-[#4c739a]">Rayon</p>
                                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->rayon?->name ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[#4c739a]">Kamar</p>
                                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->room?->name ?? '-' }}</p>
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
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                @forelse($recentLicenses as $license)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-5 py-3 text-sm font-medium text-[#0d141b] dark:text-white whitespace-nowrap">{{ $license->student?->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-sm text-[#0d141b] dark:text-white max-w-xs truncate">{{ $license->description ?? '-' }}</td>
                        <td class="px-5 py-3 text-sm text-[#4c739a] whitespace-nowrap">
                            {{ $license->start_date->format('d M Y') }} – {{ $license->end_date->format('d M Y') }}
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
