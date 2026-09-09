@extends('layouts.guardian')

@section('title', 'Riwayat Pelanggaran')
@section('mobile_title', 'Pelanggaran')

@section('content')

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('guardian.dashboard') }}"
            class="flex h-9 w-9 items-center justify-center rounded-xl bg-white dark:bg-slate-900 border border-[#e7edf3] dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-[20px] text-slate-500">arrow_back</span>
        </a>
        <div>
            <h1 class="text-lg font-black text-[#0d141b] dark:text-white leading-tight">Riwayat Pelanggaran</h1>
            <p class="text-xs text-[#4c739a]">Catatan pelanggaran tata tertib santri Anda.</p>
        </div>
    </div>

    @if($violations->isEmpty())
        <div class="bg-white dark:bg-slate-900 border border-[#e7edf3] dark:border-slate-700 rounded-2xl px-5 py-16 text-center shadow-sm">
            <span class="material-symbols-outlined text-5xl text-slate-300 block mb-3">shield</span>
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Tidak ada catatan pelanggaran.</p>
            <p class="text-xs text-slate-400 mt-1">Alhamdulillah, santri Anda belum tercatat melakukan pelanggaran.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($violations as $violation)
                @php
                    $catName = $violation->violationType?->category?->name ?? '';
                    $badgeColor = match($catName) {
                        'Berat'  => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                        'Sedang' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
                        default  => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                    };
                    $borderColor = match($catName) {
                        'Berat'  => 'border-l-red-500',
                        'Sedang' => 'border-l-orange-500',
                        default  => 'border-l-yellow-500',
                    };
                    $sanctionDone = $violation->sanction_status === 'completed';
                @endphp
                <div class="bg-white dark:bg-slate-900 border border-[#e7edf3] dark:border-slate-700 border-l-4 {{ $borderColor }} rounded-xl px-4 py-3.5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-sm font-bold text-[#0d141b] dark:text-white">{{ $violation->student?->name ?? '-' }}</span>
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $badgeColor }}">{{ $catName ?: '-' }}</span>
                                @if($sanctionDone)
                                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                        <span class="material-symbols-outlined text-[12px]">check_circle</span> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                        <span class="material-symbols-outlined text-[12px]">pending</span> Belum Selesai
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ $violation->violationType?->name ?? '-' }}</p>
                            <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs text-slate-400">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                    {{ $violation->date?->locale('id')->translatedFormat('d F Y') ?? '-' }}
                                </span>
                                @if($violation->academicYear)
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">school</span>
                                        {{ $violation->academicYear->name }}
                                    </span>
                                @endif
                                @if($violation->violationType?->department)
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">apartment</span>
                                        {{ $violation->violationType->department->acronym }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('guardian.violations.show', $violation->id) }}"
                            class="shrink-0 flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold transition-colors">
                            <span class="material-symbols-outlined text-[15px]">visibility</span>
                            Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $violations->links() }}
        </div>
    @endif

@endsection
