@extends('layouts.app')

@section('title', 'Kategori Kepulangan')
@section('breadcrumb', 'Kategori Kepulangan')

@section('content')
    <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Kategori Kepulangan</h1>
                <p class="text-[#4c739a] text-sm sm:text-base font-normal">Kelola kategori dan rincian alasan kepulangan santri.</p>
            </div>
            <a href="{{ route('admin.leave-categories.create') }}"
                class="group flex items-center gap-2 h-10 px-5 rounded-lg bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md transition-all transform hover:-translate-y-0.5 w-fit">
                <span class="material-symbols-outlined text-[18px] group-hover:rotate-90 transition-transform duration-300">add</span>
                <span class="whitespace-nowrap">Tambah Kategori</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col gap-4">
        @forelse($categories as $category)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="flex items-start justify-between gap-4 px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary/10 text-primary font-bold text-sm flex-shrink-0">
                            {{ $category->order }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-slate-800 dark:text-white text-base">{{ $category->name }}</h3>
                            </div>
                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                @if($category->is_fixed_duration)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                        <span class="material-symbols-outlined text-[12px]">lock_clock</span>
                                        Sudah Ditentukan
                                    </span>
                                    @if($category->duration_days)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-600 text-white">
                                            {{ $category->duration_days }} hari
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        <span class="material-symbols-outlined text-[12px]">tune</span>
                                        Menyesuaikan
                                    </span>
                                @endif
                                @if($category->max_duration)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[13px]">schedule</span>
                                        {{ $category->max_duration }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <a href="{{ route('admin.leave-categories.edit', $category) }}"
                            class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-400 hover:text-primary transition-colors" title="Edit">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </a>
                        <form action="{{ route('admin.leave-categories.destroy', $category) }}" method="POST"
                            onsubmit="return confirm('Hapus kategori {{ $category->name }}? Semua rincian alasan juga akan dihapus.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 text-slate-400 hover:text-red-600 transition-colors" title="Hapus">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Rincian Alasan</p>
                            @if($category->reasons->count())
                                <ul class="space-y-1">
                                    @foreach($category->reasons as $reason)
                                        <li class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-400">
                                            <span class="material-symbols-outlined text-[14px] text-primary mt-0.5 flex-shrink-0">arrow_right</span>
                                            <span>
                                                {{ $reason->reason }}
                                                @if($reason->can_skip_validation)
                                                    <span class="ml-1.5 inline-flex items-center gap-0.5 px-1.5 py-0 rounded text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                                        <span class="material-symbols-outlined text-[10px]">priority_high</span>
                                                        Darurat
                                                    </span>
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-slate-400 italic">Belum ada rincian alasan.</p>
                            @endif
                        </div>
                        @if($category->notes)
                            <div class="sm:w-72 bg-slate-50 dark:bg-slate-800/50 rounded-xl p-3">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Keterangan</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $category->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600 block mb-3">category</span>
                <p class="text-slate-500 dark:text-slate-400 font-medium">Belum ada kategori kepulangan.</p>
                <a href="{{ route('admin.leave-categories.create') }}" class="mt-4 inline-flex items-center gap-2 text-primary hover:underline text-sm font-medium">
                    <span class="material-symbols-outlined text-[16px]">add</span> Tambah sekarang
                </a>
            </div>
        @endforelse
    </div>
@endsection
