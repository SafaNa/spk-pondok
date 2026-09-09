@extends('layouts.guardian')

@section('title', 'Detail Pelanggaran')
@section('mobile_title', 'Detail Pelanggaran')

@section('content')

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('guardian.violations.index') }}"
            class="flex h-9 w-9 items-center justify-center rounded-xl bg-white dark:bg-slate-900 border border-[#e7edf3] dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-[20px] text-slate-500">arrow_back</span>
        </a>
        <div>
            <h1 class="text-lg font-black text-[#0d141b] dark:text-white leading-tight">Detail Pelanggaran</h1>
            <p class="text-xs text-[#4c739a]">Informasi lengkap catatan pelanggaran.</p>
        </div>
    </div>

    {{-- Status Banner --}}
    @if($violation->sanction_status === 'completed')
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl p-5 flex items-center gap-4 mb-5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400">
                <span class="material-symbols-outlined text-[28px]">check_circle</span>
            </div>
            <div>
                <p class="font-bold text-green-800 dark:text-green-400">Sanksi Telah Diselesaikan</p>
                @if($violation->verified_at)
                    <p class="text-xs text-green-700 dark:text-green-500 mt-0.5">
                        Diverifikasi pada {{ $violation->verified_at->locale('id')->translatedFormat('d F Y, H:i') }}
                    </p>
                @endif
            </div>
        </div>
    @else
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-5 flex items-center gap-4 mb-5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400">
                <span class="material-symbols-outlined text-[28px]">warning</span>
            </div>
            <div>
                <p class="font-bold text-red-800 dark:text-red-400">Sanksi Belum Diselesaikan</p>
                <p class="text-xs text-red-700 dark:text-red-500 mt-0.5">Harap santri segera menyelesaikan sanksi yang diberikan pengurus.</p>
            </div>
        </div>
    @endif

    {{-- Informasi Santri --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm mb-4">
        <div class="px-5 py-3.5 border-b border-[#e7edf3] dark:border-slate-700">
            <h2 class="text-sm font-bold text-[#0d141b] dark:text-white">Informasi Santri</h2>
        </div>
        <div class="p-5">
            <div class="flex items-center gap-3 mb-4">
                @if($violation->student?->photo)
                    <img src="{{ asset('storage/' . $violation->student->photo) }}" alt="{{ $violation->student->name }}"
                        class="h-12 w-12 rounded-xl object-cover shrink-0">
                @else
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary font-bold text-lg">
                        {{ strtoupper(substr($violation->student?->name ?? '?', 0, 2)) }}
                    </div>
                @endif
                <div>
                    <p class="font-bold text-[#0d141b] dark:text-white">{{ $violation->student?->name ?? '-' }}</p>
                    <p class="text-xs text-[#4c739a]">NIS: {{ $violation->student?->nis ?? '-' }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs text-[#4c739a] mb-0.5">Rayon</p>
                    <p class="font-medium text-[#0d141b] dark:text-white">{{ $violation->student?->rayon?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-[#4c739a] mb-0.5">Kamar</p>
                    <p class="font-medium text-[#0d141b] dark:text-white">{{ $violation->student?->room?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-[#4c739a] mb-0.5">Pend. Formal</p>
                    <p class="font-medium text-[#0d141b] dark:text-white text-xs">{{ $violation->student?->formalEducation?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-[#4c739a] mb-0.5">Pend. Diniyah</p>
                    <p class="font-medium text-[#0d141b] dark:text-white text-xs">{{ $violation->student?->religiousEducation?->name ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Pelanggaran --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm">
        <div class="px-5 py-3.5 border-b border-[#e7edf3] dark:border-slate-700">
            <h2 class="text-sm font-bold text-[#0d141b] dark:text-white">Detail Pelanggaran</h2>
        </div>
        <div class="p-5 space-y-4">

            {{-- Jenis --}}
            <div>
                <p class="text-xs text-[#4c739a] mb-1">Jenis Pelanggaran</p>
                <p class="font-bold text-[#0d141b] dark:text-white">{{ $violation->violationType?->name ?? '-' }}</p>
                @if($violation->violationType?->description)
                    <p class="text-xs text-slate-400 mt-0.5">{{ $violation->violationType->description }}</p>
                @endif
            </div>

            {{-- Kategori & Departemen --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-[#4c739a] mb-1">Kategori</p>
                    @php
                        $catName = $violation->violationType?->category?->name ?? '-';
                        $catColor = match($catName) {
                            'Berat'  => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                            'Sedang' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
                            default  => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                        };
                        $catPoints = $violation->violationType?->category?->points ?? 0;
                    @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $catColor }}">
                        {{ $catName }}
                        @if($catPoints) <span class="opacity-70">({{ $catPoints }} poin)</span> @endif
                    </span>
                </div>
                <div>
                    <p class="text-xs text-[#4c739a] mb-1">Departemen</p>
                    <p class="text-sm font-medium text-[#0d141b] dark:text-white">
                        {{ $violation->violationType?->department?->acronym ?? '-' }}
                        @if($violation->violationType?->department?->name)
                            <span class="text-slate-400 font-normal">· {{ $violation->violationType->department->name }}</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Tanggal & Tahun Ajaran --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-[#4c739a] mb-1">Tanggal Kejadian</p>
                    <p class="text-sm font-medium text-[#0d141b] dark:text-white">
                        {{ $violation->date?->locale('id')->translatedFormat('d F Y') ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-[#4c739a] mb-1">Tahun Ajaran</p>
                    <p class="text-sm font-medium text-[#0d141b] dark:text-white">{{ $violation->academicYear?->name ?? '-' }}</p>
                </div>
            </div>

            {{-- Sanksi --}}
            <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4">
                <p class="text-xs font-semibold text-[#4c739a] mb-1.5 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">gavel</span> Sanksi
                </p>
                <p class="text-sm text-[#0d141b] dark:text-white leading-relaxed">{{ $violation->sanction ?? $violation->violationType?->default_sanction ?? '-' }}</p>
            </div>

            {{-- Catatan --}}
            @if($violation->notes)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-4">
                    <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-1.5 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">notes</span> Catatan
                    </p>
                    <p class="text-sm text-[#0d141b] dark:text-white leading-relaxed">{{ $violation->notes }}</p>
                </div>
            @endif

            {{-- Dicatat oleh --}}
            <div>
                <p class="text-xs text-[#4c739a] mb-1">Dicatat Oleh</p>
                <p class="text-sm font-medium text-[#0d141b] dark:text-white">{{ $violation->creator?->name ?? '-' }}</p>
                <p class="text-xs text-slate-400">{{ $violation->created_at?->locale('id')->translatedFormat('d F Y, H:i') }}</p>
            </div>
        </div>
    </div>

@endsection
