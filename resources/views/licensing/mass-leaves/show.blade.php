@extends('layouts.app')

@section('title', 'Detail Liburan Massal')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.mass-leaves.index') }}"
                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $mass_leaf->title }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    {{ \Carbon\Carbon::parse($mass_leaf->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($mass_leaf->end_date)->format('d M Y') }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($mass_leaf->is_active)
            <a href="{{ route('admin.mass-leaves.checkout', $mass_leaf->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                <span class="material-symbols-outlined text-[20px]">qr_code_scanner</span>
                Buka Kasir Checkout
            </a>
            @endif
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Pulang</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $students->count() }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Sudah Kembali</p>
            <p class="text-2xl font-black text-green-600 dark:text-green-400">{{ $students->whereNotNull('actual_return_date')->count() }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Belum Kembali</p>
            <p class="text-2xl font-black text-orange-500">{{ $students->whereNull('actual_return_date')->count() }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white">Santri</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white">Waktu Keluar</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white">Waktu Kembali</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($students as $ms)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm">
                                    {{ substr($ms->student->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $ms->student->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $ms->student->rayon->name ?? '-' }} - Kamar {{ $ms->student->room->name ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $ms->checked_out_at->format('d M Y') }}</p>
                            <p class="text-xs text-slate-500">{{ $ms->checked_out_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($ms->actual_return_date)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                    {{ $ms->actual_return_date->format('d M Y H:i') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                                    Belum Kembali
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            Belum ada santri yang di-checkout pada liburan ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
