@extends('layouts.app')

@section('title', 'Detail Pelanggaran')
@section('breadcrumb', 'Detail')
@section('breadcrumb_parent', 'Pelanggaran')
@section('breadcrumb_parent_route', 'violations.index')
@section('mobile_title', 'Detail Pelanggaran')

@section('content')
    <div class="flex flex-col gap-6" x-data="{ showVerifyModal: false }">
        {{-- Back Button --}}
        <a href="{{ route('violations.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Daftar</span>
        </a>

        {{-- Status Banner --}}
        @if($violation->sanction_status === 'completed')
            <div class="bg-gradient-to-r from-green-500/10 to-emerald-500/10 rounded-2xl p-6 border border-green-500/20">
                <div class="flex items-center gap-5">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border border-green-500/20 text-green-600 shadow-sm">
                        <span class="material-symbols-outlined text-[36px] fill-1">check_circle</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-green-700 dark:text-green-500">Sanksi Selesai</h3>
                        <p class="text-green-600/80 dark:text-green-400 mt-1 text-sm">
                            Diverifikasi oleh <span class="font-semibold">{{ $violation->verifier->name }}</span>
                            pada {{ $violation->verified_at->format('d F Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-red-500/10 to-orange-500/10 rounded-2xl p-6 border border-red-500/20">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border border-red-500/20 text-red-600 shadow-sm">
                            <span class="material-symbols-outlined text-[36px] fill-1">warning</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-red-700 dark:text-red-500">Sanksi Belum Diselesaikan</h3>
                            <p class="text-red-600/80 dark:text-red-400 mt-1 text-sm">Santri harus menyelesaikan sanksi terlebih
                                dahulu</p>
                        </div>
                    </div>
                    @if(Auth::user()->isAdmin() || (Auth::user()->isDepartmentOfficer() && $violation->violationType->department_id == Auth::user()->department_id))
                        <button type="button" @click="showVerifyModal = true"
                            class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all shadow-lg hover:shadow-green-500/30 font-bold flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                            <span class="material-symbols-outlined">check_circle</span>
                            <span>Tandai Selesai</span>
                        </button>
                    @endif
                </div>
            </div>
        @endif

        {{-- Santri Info Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800">
            <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-800">
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Informasi Santri</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-xl">
                        {{ strtoupper(substr($violation->student->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-xl font-bold text-[#0d141b] dark:text-white">{{ $violation->student->name }}</p>
                        <p class="text-sm text-[#4c739a] mt-1">NIS: {{ $violation->student->nis }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Violation Details --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800">
            <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-800">
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Detail Pelanggaran</h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-[#4c739a] mb-1">Tanggal Kejadian</p>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px] text-primary">calendar_today</span>
                            <p class="font-medium text-[#0d141b] dark:text-white">
                                {{ $violation->date->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-[#4c739a] mb-1">Tanggal Input</p>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px] text-primary">schedule</span>
                            <p class="font-medium text-[#0d141b] dark:text-white">
                                {{ $violation->created_at->format('d F Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-[#4c739a] mb-2">Periode</p>
                    <span class="px-3 py-2 bg-blue-500/10 text-blue-600 rounded font-medium inline-block">
                        {{ $violation->period->name }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-[#4c739a] mb-2">Departemen</p>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-primary">apartment</span>
                        <span class="px-3 py-1 bg-primary/10 text-primary rounded font-medium">
                            {{ $violation->violationType->department->acronym }}
                        </span>
                        <span class="text-[#0d141b] dark:text-white font-medium">
                            {{ $violation->violationType->department->name }}
                        </span>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-[#4c739a] mb-2">Jenis Pelanggaran</p>
                    <p class="text-lg font-bold text-[#0d141b] dark:text-white">
                        {{ $violation->violationType->name }}
                    </p>
                    @if($violation->violationType->description)
                        <p class="text-sm text-[#4c739a] mt-1">{{ $violation->violationType->description }}</p>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-[#4c739a] mb-2">Kategori</p>
                    @php
                        $category = $violation->violationType->category;
                        $badgeColor = match ($category->name) {
                            'Ringan' => 'bg-yellow-500/20 text-yellow-700 border-yellow-500/30',
                            'Sedang' => 'bg-orange-500/20 text-orange-700 border-orange-500/30',
                            'Berat' => 'bg-red-500/20 text-red-700 border-red-500/30',
                            default => 'bg-gray-500/20 text-gray-700 border-gray-500/30'
                        };
                    @endphp
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border {{ $badgeColor }}">
                        <span class="font-bold text-lg">{{ $category->name }}</span>
                        <span class="text-sm">({{ $category->points }} poin)</span>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                    <p class="text-sm font-medium text-[#4c739a] mb-2">Sanksi</p>
                    <p class="text-[#0d141b] dark:text-white leading-relaxed">{{ $violation->sanction }}</p>
                </div>

                @if($violation->notes)
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-400 mb-2">Catatan</p>
                        <p class="text-[#0d141b] dark:text-white">{{ $violation->notes }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-sm text-[#4c739a] mb-2">Dicatat Oleh</p>
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-[#4c739a] font-medium">
                            {{ strtoupper(substr($violation->creator->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-medium text-[#0d141b] dark:text-white">{{ $violation->creator->name }}</p>
                            <p class="text-xs text-[#4c739a]">{{ $violation->creator->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Verification Modal --}}
        <div x-show="showVerifyModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true"
            style="display: none;">
            <div x-show="showVerifyModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showVerifyModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600 sm:mx-0 sm:h-10 sm:w-10">
                                    <span class="material-symbols-outlined text-[24px]">check_circle</span>
                                </div>
                                <div class="text-center sm:ml-4 sm:text-left">
                                    <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white" id="modal-title">
                                        Verifikasi Sanksi
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500 dark:text-slate-400">
                                            Apakah Anda yakin ingin menandai sanksi pelanggaran ini sebagai selesai?
                                            Tindakan ini akan mencatat tanggal dan waktu verifikasi saat ini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <form action="{{ route('violations.verify-sanction', $violation->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex w-full justify-center rounded-lg bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto">
                                    Ya, Verifikasi Selesai
                                </button>
                            </form>
                            <button type="button" @click="showVerifyModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-slate-800 px-3 py-2 text-sm font-semibold text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 sm:mt-0 sm:w-auto">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection