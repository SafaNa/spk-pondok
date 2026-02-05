@extends('layouts.app')

@section('title', 'Dashboard - Santri Admin')
@section('mobile_title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    {{-- Page Heading with Gradient --}}
    <div
        class="mb-8 rounded-2xl bg-gradient-to-br from-blue-50 via-indigo-50/50 to-purple-50/30 p-8 dark:from-slate-800 dark:via-slate-800/80 dark:to-slate-800/50">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div>
                <div
                    class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-primary backdrop-blur-sm dark:bg-slate-700/80">
                    <span class="material-symbols-outlined text-[16px]">dashboard</span>
                    Overview
                </div>
                <h1 class="font-outfit text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Pantau progres penilaian santri dan kelola kriteria sistem.
                    @if($activePeriod)
                        <span
                            class="ml-2 inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 ring-1 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                            <span class="h-2 w-2 animate-pulse rounded-full bg-green-500"></span>
                            {{ $activePeriod->nama }}
                        </span>
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="#"
                    class="group inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-md hover:ring-gray-300 dark:bg-slate-800 dark:text-gray-200 dark:ring-slate-700 dark:hover:ring-slate-600">
                    <span
                        class="material-symbols-outlined text-[22px] transition-transform group-hover:rotate-90">settings</span>
                    Konfigurasi
                </a>
                <a href="#"
                    class="inline-flex items-center gap-2 rounded-xl bg-gray-300 px-5 py-3 text-sm font-bold text-white opacity-50 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[22px]">add</span>
                    Penilaian Baru
                </a>
            </div>
        </div>
    </div>

    {{-- KPI Cards Grid with Vibrant Gradients --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        {{-- Card 1: Total Santri --}}
        <div class="group relative overflow-hidden rounded-2xl p-6 shadow-lg transition-all hover:shadow-xl"
            style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full" style="background: rgba(255, 255, 255, 0.1);">
            </div>
            <div class="relative">
                <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-xl backdrop-blur-sm"
                    style="background: rgba(255, 255, 255, 0.2);">
                    <span class="material-symbols-outlined text-[28px]">groups</span>
                </div>
                <div class="text-3xl font-bold">{{ number_format($totalStudents) }}</div>
                <div class="text-sm" style="color: rgba(255, 255, 255, 0.9);">Total Santri</div>
            </div>
        </div>

        {{-- Card 2: Evaluation Criteria --}}
        <div class="group relative overflow-hidden rounded-2xl p-6 shadow-lg transition-all hover:shadow-xl"
            style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); color: white; box-shadow: 0 10px 15px -3px rgba(168, 85, 247, 0.3);">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full" style="background: rgba(255, 255, 255, 0.1);">
            </div>
            <div class="relative">
                <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-xl backdrop-blur-sm"
                    style="background: rgba(255, 255, 255, 0.2);">
                    <span class="material-symbols-outlined text-[28px]">tune</span>
                </div>
                <div class="text-3xl font-bold">{{ $totalCriteria }}</div>
                <div class="text-sm" style="color: rgba(255, 255, 255, 0.9);">Kriteria Aktif</div>
            </div>
        </div>

        {{-- Card 3: Assessed Santri --}}
        <div class="group relative overflow-hidden rounded-2xl p-6 shadow-lg transition-all hover:shadow-xl"
            style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full" style="background: rgba(255, 255, 255, 0.1);">
            </div>
            <div class="relative">
                <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-xl backdrop-blur-sm"
                    style="background: rgba(255, 255, 255, 0.2);">
                    <span class="material-symbols-outlined text-[28px]">fact_check</span>
                </div>
                <div class="text-3xl font-bold">{{ number_format($assessedStudents) }}</div>
                <div class="text-sm" style="color: rgba(255, 255, 255, 0.9);">Santri Dinilai</div>
            </div>
        </div>

        {{-- Card 4: Pending Review --}}
        <div class="group relative overflow-hidden rounded-2xl p-6 shadow-lg transition-all hover:shadow-xl"
            style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); color: white; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);">
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full" style="background: rgba(255, 255, 255, 0.1);">
            </div>
            <div class="relative">
                <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-xl backdrop-blur-sm"
                    style="background: rgba(255, 255, 255, 0.2);">
                    <span class="material-symbols-outlined text-[28px]">pending</span>
                </div>
                <div class="text-3xl font-bold">{{ number_format($pendingCount) }}</div>
                <div class="text-sm" style="color: rgba(255, 255, 255, 0.9);">Menunggu Tinjauan</div>
            </div>
        </div>
    </div>

    {{-- Completion Rate Bar --}}
    <div class="mb-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Tingkat Penyelesaian</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Progress penilaian seluruh santri</p>
            </div>
            <span class="text-2xl font-bold text-primary">{{ $completionRate }}%</span>
        </div>
        <div class="h-3 w-full rounded-full bg-gray-200 dark:bg-slate-700 overflow-hidden">
            <div class="h-3 rounded-full bg-gradient-to-r from-primary to-blue-600 transition-all duration-500"
                style="width: {{ $completionRate }}%"></div>
        </div>
    </div>

    {{-- Charts & Analytics Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Left Column: Top 5 Santri (Placeholder) --}}
        <div
            class="lg:col-span-2 rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700 p-6">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Santri Berprestasi</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Nilai akhir tertinggi (Segera Hadir)</p>
                </div>
            </div>

            <div class="h-64 flex items-center justify-center">
                <div class="text-center">
                    <div
                        class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mx-auto dark:from-slate-700 dark:to-slate-600">
                        <span class="material-symbols-outlined text-[40px] text-gray-400">bar_chart</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">Modul perhitungan sedang dalam pembangunan</p>
                </div>
            </div>
        </div>

        {{-- Right Column: Recommendation Status (Donut Chart) --}}
        <div
            class="lg:col-span-1 rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700 p-6 flex flex-col">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Status Rekomendasi</h3>
            <div class="flex-1 flex flex-col items-center justify-center">
                {{-- CSS Pie Chart --}}
                @php
                    $recEnd = $recommendedPercent;
                    $pendEnd = $recEnd + $pendingPercent;
                @endphp
                <div class="relative w-48 h-48 rounded-full mb-6"
                    style="background: conic-gradient(#137fec 0% {{ $recEnd }}%, #fbbf24 {{ $recEnd }}% {{ $pendEnd }}%, #9ca3af {{ $pendEnd }}% 100%);">
                    <div
                        class="absolute inset-0 m-auto w-32 h-32 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center flex-col shadow-sm">
                        <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $assessedStudents }}</span>
                        <span class="text-xs text-gray-500">Dinilai</span>
                    </div>
                </div>
                {{-- Legend --}}
                <div class="w-full space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-primary"></span>
                            <span class="text-gray-600 dark:text-gray-400">Direkomendasikan</span>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $recommendedPercent }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                            <span class="text-gray-600 dark:text-gray-400">Menunggu</span>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $pendingPercent }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                            <span class="text-gray-600 dark:text-gray-400">Tidak Direkomendasikan</span>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $notRecommendedPercent }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Section: Quick Actions & Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Quick Action Grid --}}
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a class="group relative overflow-hidden rounded-2xl p-6 shadow-lg transition-all hover:shadow-xl"
                style="background: linear-gradient(135deg, #14b8a6 0%, #0891b2 100%);" href="{{ route('students.index') }}">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full" style="background: rgba(255, 255, 255, 0.1);">
                </div>
                <div class="relative">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl transition-transform group-hover:scale-110"
                        style="background: rgba(255, 255, 255, 0.2);">
                        <span class="material-symbols-outlined text-[28px] text-white">person_add</span>
                    </div>
                    <h4 class="font-semibold text-white">Tambah Santri Baru</h4>
                    <p class="mt-1 text-sm" style="color: rgba(255, 255, 255, 0.9);">Daftarkan santri baru ke dalam periode
                        aktif.</p>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-white">
                        Ke manajemen santri
                        <span
                            class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </span>
                </div>
            </a>

            <a href="#" class="group relative overflow-hidden rounded-2xl p-6 shadow-lg transition-all hover:shadow-xl"
                style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full" style="background: rgba(255, 255, 255, 0.1);">
                </div>
                <div class="relative">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl transition-transform group-hover:scale-110"
                        style="background: rgba(255, 255, 255, 0.2);">
                        <span class="material-symbols-outlined text-[28px] text-white">checklist</span>
                    </div>
                    <h4 class="font-semibold text-white">Kelola Kriteria</h4>
                    <p class="mt-1 text-sm" style="color: rgba(255, 255, 255, 0.9);">Sesuaikan bobot dan kriteria sistem.
                    </p>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-white">
                        Ubah pengaturan
                        <span
                            class="material-symbols-outlined text-[18px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </span>
                </div>
            </a>
        </div>

        {{-- Recent Activity List --}}
        <div
            class="lg:col-span-2 rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700 overflow-hidden">
            <div
                class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50 px-6 py-4 dark:border-slate-700 dark:from-slate-700/50 dark:to-slate-700/30">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Penilaian Terbaru</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Aktivitas terakhir sistem</p>
            </div>
            <ul class="divide-y divide-gray-100 dark:divide-slate-700" role="list">
                @if(isset($recentAssessments) && count($recentAssessments) > 0)
                    @foreach($recentAssessments as $assessment)
                        <li
                            class="group flex items-center gap-x-4 px-6 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-slate-700/50 cursor-pointer">
                            <div
                                class="h-12 w-12 flex-none rounded-full {{ ($assessment->total_score ?? 0) >= 0.6 ? 'bg-gradient-to-br from-primary to-blue-600 text-white' : 'bg-gray-200 dark:bg-slate-700 text-gray-600' }} flex items-center justify-center text-sm font-bold shadow-sm">
                                {{ strtoupper(substr($assessment->student->name ?? 'NA', 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-auto">
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $assessment->student->name ?? 'N/A' }}
                                </p>
                                <p class="truncate text-xs text-gray-500">{{ $assessment->period->name ?? 'N/A' }}</p>
                            </div>
                            <div class="flex flex-col items-end">
                                <span
                                    class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30">
                                    Dinilai
                                </span>
                                <time class="mt-1 text-xs text-gray-400">{{ $assessment->created_at->diffForHumans() }}</time>
                            </div>
                        </li>
                    @endforeach
                @else
                    <li class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div
                                class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-slate-700">
                                <span class="material-symbols-outlined text-[32px] text-gray-400">history</span>
                            </div>
                            <p class="font-medium text-gray-500 dark:text-gray-400">Belum ada penilaian terbaru</p>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
@endsection