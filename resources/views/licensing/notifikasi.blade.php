@extends('layouts.app')

@section('title', 'Notifikasi')
@section('breadcrumb', 'Notifikasi')

@section('content')
    <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col gap-1">
            <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Notifikasi</h1>
            <p class="text-[#4c739a] text-sm font-normal">Pemberitahuan dan informasi terkini seputar perizinan.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-12 flex flex-col items-center justify-center text-center">
        <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600 mb-4">notifications</span>
        <h3 class="text-base font-semibold text-slate-500 dark:text-slate-400">Fitur Notifikasi</h3>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Halaman ini sedang dalam tahap pengembangan.</p>
    </div>
@endsection
