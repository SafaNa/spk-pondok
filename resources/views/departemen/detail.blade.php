@extends('layouts.app')

@section('title', 'Detail Departemen')
@section('breadcrumb', 'Detail')
@section('breadcrumb_parent', 'Departemen')
@section('breadcrumb_parent_route', 'departments.index')
@section('mobile_title', 'Detail Departemen')

@section('content')
    <div class="flex flex-col gap-6">
        {{-- Back Button --}}
        <a href="{{ route('departments.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Daftar Departemen</span>
        </a>

        {{-- Info Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-primary/20 overflow-hidden">
            <div class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 p-6 border-b border-primary/10">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                            <span class="material-symbols-outlined text-[32px] fill-1">apartment</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $departemen->nama_departemen }}
                            </h1>
                            <div class="flex items-center gap-2 mt-1">
                                <span
                                    class="px-2.5 py-0.5 rounded text-xs font-semibold bg-primary/10 text-primary border border-primary/20">
                                    {{ $departemen->kode_departemen }}
                                </span>
                                <span
                                    class="px-2.5 py-0.5 rounded text-xs font-semibold bg-blue-500/10 text-blue-600 border border-blue-500/20">
                                    {{ $departemen->singkatan }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($departemen->keterangan)
                <div class="p-6">
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ $departemen->keterangan }}</p>
                </div>
            @endif
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 gap-4">
            <div class="bg-white dark:bg-slate-900 rounded-xl p-6 border border-[#e7edf3] dark:border-slate-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#4c739a] text-sm">Jenis Pelanggaran</p>
                        <p class="text-3xl font-bold text-[#0d141b] dark:text-white mt-1">
                            {{ $departemen->jenisPelanggaran->count() }}
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-500/10 text-blue-500">
                        <span class="material-symbols-outlined text-[28px]">rule</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Jenis Pelanggaran Section --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800">
            <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-800 flex justify-between items-center">
                <h2 class="text-lg font-bold text-[#0d141b] dark:text-white">Jenis Pelanggaran</h2>
                @if($departemen->jenisPelanggaran->count() > 0)
                    <span class="text-sm text-[#4c739a]">{{ $departemen->jenisPelanggaran->count() }} Jenis</span>
                @endif
            </div>
            <div class="p-6">
                @forelse($departemen->jenisPelanggaran as $jp)
                    <div class="mb-4 pb-4 border-b border-[#e7edf3] dark:border-slate-800 last:border-0 last:mb-0 last:pb-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="font-mono text-xs text-[#4c739a] bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">{{ $jp->kode_pelanggaran }}</span>
                                    @php
                                        $kategori = $jp->kategoriPelanggaran;
                                        $badgeColor = match ($kategori->kode_kategori) {
                                            'R' => 'bg-yellow-500/10 text-yellow-600',
                                            'S' => 'bg-orange-500/10 text-orange-600',
                                            'B' => 'bg-red-500/10 text-red-600',
                                            default => 'bg-gray-500/10 text-gray-600'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $badgeColor }}">
                                        {{ $kategori->nama_kategori }}
                                    </span>
                                </div>
                                <p class="font-medium text-[#0d141b] dark:text-white">{{ $jp->nama_pelanggaran }}</p>
                                @if($jp->deskripsi)
                                    <p class="text-sm text-[#4c739a] mt-1">{{ $jp->deskripsi }}</p>
                                @endif
                                <p class="text-sm text-[#4c739a] mt-2">
                                    <span class="font-medium">Sanksi:</span> {{ Str::limit($jp->sanksi_default, 100) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-[#4c739a]">
                        <span class="material-symbols-outlined text-[48px] opacity-30">folder_open</span>
                        <p class="mt-2">Belum ada jenis pelanggaran</p>
                    </div>
                @endforelse
            </div>
        </div>


    </div>
@endsection