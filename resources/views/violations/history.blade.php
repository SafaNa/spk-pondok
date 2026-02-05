@extends('layouts.app')

@section('title', 'Riwayat Pelanggaran')
@section('breadcrumb', 'Riwayat')
@section('breadcrumb_parent', 'Pelanggaran')
@section('breadcrumb_parent_route', 'violations.index')
@section('mobile_title', 'Riwayat Pelanggaran')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('violations.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Daftar Pelanggaran</span>
        </a>

        {{-- Student Info Card --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-800 p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div
                    class="flex h-20 w-20 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-3xl">
                    {{ strtoupper(substr($student->name, 0, 2)) }}
                </div>
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl font-bold text-[#0d141b] dark:text-white mb-2">{{ $student->name }}</h1>
                    <div class="flex flex-wrap justify-center sm:justify-start gap-4 text-sm text-[#4c739a]">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">badge</span>
                            {{ $student->nis }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">school</span>
                            {{ $student->classroom->name ?? 'Kelas -' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600">
                        <span class="material-symbols-outlined">warning</span>
                    </div>
                    <div>
                        <p class="text-sm text-[#4c739a] font-medium">Total Pelanggaran</p>
                        <p class="text-2xl font-bold text-[#0d141b] dark:text-white">{{ $violations->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-50 text-yellow-600">
                        <span class="material-symbols-outlined">gavel</span>
                    </div>
                    <div>
                        <p class="text-sm text-[#4c739a] font-medium">Poin Pelanggaran</p>
                        <p class="text-2xl font-bold text-[#0d141b] dark:text-white">{{ $violations->sum(function($v) { return $v->violationType->category->points ?? 0; }) }}</p>
                    </div>
                </div>
            </div>
             <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                         <span class="material-symbols-outlined">pending_actions</span>
                    </div>
                    <div>
                        <p class="text-sm text-[#4c739a] font-medium">Belum Selesai</p>
                        <p class="text-2xl font-bold text-[#0d141b] dark:text-white">{{ $violations->where('sanction_status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Violations by Period --}}
        @forelse($violationsByPeriod as $periodName => $periodViolations)
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
                <div class="bg-slate-50/50 dark:bg-slate-800/50 px-6 py-4 border-b border-slate-100 dark:border-slate-700">
                     <h3 class="font-bold text-[#0d141b] dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">calendar_month</span>
                        {{ $periodName }}
                    </h3>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($periodViolations as $violation)
                        <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                            <div class="flex flex-col md:flex-row gap-6 justify-between items-start md:items-center">
                                <div class="flex items-start gap-4">
                                     <div class="flex-shrink-0 mt-1">
                                        @php
                                            $category = $violation->violationType->category;
                                            $iconColor = match ($category->name) {
                                                'Ringan' => 'text-yellow-500',
                                                'Sedang' => 'text-orange-500',
                                                'Berat' => 'text-red-500',
                                                default => 'text-gray-500'
                                            };
                                        @endphp
                                        <span class="material-symbols-outlined {{ $iconColor }}">warning</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-[#0d141b] dark:text-white mb-1">
                                            {{ $violation->violationType->name }}
                                        </h4>
                                        <div class="flex items-center gap-2 text-sm text-[#4c739a] mb-2">
                                             <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-xs font-semibold">
                                                {{ $category->name }}
                                            </span>
                                            <span>•</span>
                                            <span>{{ $violation->date->format('d F Y') }}</span>
                                        </div>
                                         <p class="text-sm text-slate-600 dark:text-slate-400">
                                            <span class="font-medium">Sanksi:</span> {{ $violation->sanction }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 ml-10 md:ml-0">
                                     @if($violation->sanction_status === 'completed')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-50 text-green-700 text-sm font-medium border border-green-100">
                                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 text-red-700 text-sm font-medium border border-red-100">
                                            <span class="material-symbols-outlined text-[16px]">pending</span>
                                            Belum Selesai
                                        </span>
                                    @endif
                                    
                                     <a href="{{ route('violations.show', $violation->id) }}" 
                                        class="p-2 text-slate-400 hover:text-primary transition-colors rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800"
                                        title="Lihat Detail">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-12 text-center border border-dashed border-slate-300 dark:border-slate-700">
                <div class="flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-3xl opacity-50 text-slate-400">check_circle</span>
                    </div>
                    <h3 class="font-bold text-lg text-[#0d141b] dark:text-white mb-1">Tidak Ada Riwayat Pelanggaran</h3>
                    <p class="text-slate-500 dark:text-slate-400">Santri ini belum memiliki catatan pelanggaran.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
