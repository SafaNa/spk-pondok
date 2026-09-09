@extends('layouts.guardian')

@section('title', 'Riwayat Notifikasi')
@section('mobile_title', 'Notifikasi')

@section('content')

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('guardian.dashboard') }}"
            class="flex h-9 w-9 items-center justify-center rounded-xl bg-white dark:bg-slate-900 border border-[#e7edf3] dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-[20px] text-slate-500">arrow_back</span>
        </a>
        <div>
            <h1 class="text-lg font-black text-[#0d141b] dark:text-white leading-tight">Riwayat Notifikasi</h1>
            <p class="text-xs text-[#4c739a]">Semua notifikasi yang pernah dikirimkan ke WhatsApp Anda.</p>
        </div>
    </div>

    @if($notifications->isEmpty())
        <div class="bg-white dark:bg-slate-900 border border-[#e7edf3] dark:border-slate-700 rounded-2xl px-5 py-16 text-center shadow-sm">
            <span class="material-symbols-outlined text-5xl text-slate-300 block mb-3">notifications_off</span>
            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Belum ada notifikasi.</p>
            <p class="text-xs text-slate-400 mt-1">Notifikasi akan muncul saat ada update izin, pelanggaran, atau pembayaran SPP.</p>
        </div>
    @else
        <p class="text-xs text-slate-400 mb-4">{{ $notifications->count() }} notifikasi ditemukan</p>

        <div class="space-y-2.5">
            @foreach($notifications as $notif)
                @php
                    $borderColor = match($notif['color']) {
                        'green' => 'border-l-green-500',
                        'red'   => 'border-l-red-500',
                        'amber' => 'border-l-amber-500',
                        default => 'border-l-blue-500',
                    };
                    $iconBg = match($notif['color']) {
                        'green' => 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
                        'red'   => 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400',
                        'amber' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400',
                        default => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
                    };
                @endphp
                <div class="bg-white dark:bg-slate-900 border border-[#e7edf3] dark:border-slate-700 border-l-4 {{ $borderColor }} rounded-xl px-4 py-3.5 flex items-start gap-3 shadow-sm">
                    <div class="shrink-0 w-9 h-9 rounded-lg {{ $iconBg }} flex items-center justify-center mt-0.5">
                        <span class="material-symbols-outlined text-[18px]">{{ $notif['icon'] }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-semibold text-[#0d141b] dark:text-white leading-snug">{{ $notif['title'] }}</p>
                            <span class="shrink-0 text-[11px] text-slate-400 whitespace-nowrap mt-0.5">
                                {{ $notif['timestamp'] ? \Carbon\Carbon::parse($notif['timestamp'])->locale('id')->diffForHumans() : '-' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">{{ $notif['message'] }}</p>
                        <div class="flex items-center justify-between mt-2">
                            @if($notif['timestamp'])
                                <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                    {{ \Carbon\Carbon::parse($notif['timestamp'])->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                                </p>
                            @else
                                <span></span>
                            @endif
                            @if(!empty($notif['link']))
                                <a href="{{ $notif['link'] }}"
                                    class="flex items-center gap-0.5 text-xs font-semibold text-primary hover:underline">
                                    <span class="material-symbols-outlined text-[15px]">visibility</span>
                                    Lihat Detail
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
