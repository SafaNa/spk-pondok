@extends('layouts.app')

@section('title', 'Izin Massal / Liburan Serentak')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800 mb-8">
        <div class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-6 py-8 border-b border-primary/10">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div class="flex items-center gap-6">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">groups</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Liburan Serentak</h1>
                        <p class="text-slate-500 dark:text-slate-400 mt-1 max-w-xl">Kelola event kepulangan massal santri</p>
                    </div>
                </div>
                <a href="{{ route('admin.mass-leaves.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all group">
                    <span class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform">add</span>
                    Buat Event Liburan
                </a>
            </div>
        </div>
    </div>

    <!-- Stats/Info -->
    @if(session('success'))
    <div class="p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-start gap-3">
        <span class="material-symbols-outlined text-green-500">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-start gap-3">
        <span class="material-symbols-outlined text-red-500">error</span>
        {{ session('error') }}
    </div>
    @endif

    <!-- List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($leaves as $leave)
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 flex flex-col">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $leave->title }}</h3>
                    <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-1">
                        <span class="material-symbols-outlined text-[14px]">calendar_month</span>
                        {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                    </div>
                </div>
                <div>
                    @if($leave->is_active)
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                        Aktif
                    </span>
                    @else
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-400">
                        Selesai
                    </span>
                    @endif
                </div>
            </div>

            <div class="flex-1 flex flex-col justify-center items-center py-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl mb-4">
                <span class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ $leave->students_count }}</span>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Santri Pulang</span>
            </div>

            <div class="flex gap-2 mt-auto">
                <a href="{{ route('admin.mass-leaves.show', $leave->id) }}"
                    class="flex-1 inline-flex justify-center items-center gap-2 px-4 py-2 border-2 border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                    Data
                </a>
                @if($leave->is_active)
                <a href="{{ route('admin.mass-leaves.checkout', $leave->id) }}"
                    class="flex-1 inline-flex justify-center items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                    <span class="material-symbols-outlined text-[20px]">qr_code_scanner</span>
                    Checkout
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-slate-400 text-3xl">event_busy</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Belum ada event liburan</h3>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Buat event pertama untuk mulai mencatat kepulangan santri.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
