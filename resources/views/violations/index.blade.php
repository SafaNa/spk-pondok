@extends('layouts.app')

@section('title', 'Kelola Pelanggaran')
@section('breadcrumb', 'Pelanggaran')
@section('mobile_title', 'Pelanggaran')

@section('content')
    <div class="flex flex-col gap-6" x-data="{ showVerifyModal: false, verifyActionUrl: '' }">
        {{-- Header with Stats --}}
        {{-- Header with Stats --}}
        <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
            style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                <div class="flex flex-col gap-1">
                    <h2 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Kelola Pelanggaran Santri
                    </h2>
                    <p class="text-[#4c739a] text-sm sm:text-base font-normal">Catat dan pantau pelanggaran santri secara
                        real-time</p>
                </div>
                @if(!Auth::user()->isLicensingOfficer())
                    <a href="{{ route('violations.create') }}"
                        class="group flex items-center justify-center gap-2 rounded-xl px-5 h-11 bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-lg hover:shadow-xl hover:shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-200">
                        <span
                            class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                        <span>Catat Pelanggaran</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-xl p-6 border border-blue-500/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#4c739a] text-sm font-medium">Total Pelanggaran</p>
                        <p class="text-3xl font-bold text-[#0d141b] dark:text-white mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-500/20 text-blue-600">
                        <span class="material-symbols-outlined text-[32px] fill-1">assignment_late</span>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-red-500/10 to-orange-500/10 rounded-xl p-6 border border-red-500/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#4c739a] text-sm font-medium">Belum Selesai</p>
                        <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-red-500/20 text-red-600">
                        <span class="material-symbols-outlined text-[32px] fill-1">warning</span>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-xl p-6 border border-green-500/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#4c739a] text-sm font-medium">Selesai</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['completed'] }}</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-green-500/20 text-green-600">
                        <span class="material-symbols-outlined text-[32px] fill-1">check_circle</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Violations List --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800">
            <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-800">
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Daftar Pelanggaran</h3>
            </div>
            <div class="divide-y divide-[#e7edf3] dark:divide-slate-800">
                @forelse($violations as $violation)
                    @php
                        $initials = strtoupper(substr($violation->student->name, 0, 1) . (str_contains($violation->student->name, ' ') ? substr($violation->student->name, strpos($violation->student->name, ' ') + 1, 1) : substr($violation->student->name, 1, 1)));
                        $colors = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                        $colorIndex = crc32($violation->student->id) % count($colors);
                        $color = $colors[$colorIndex];
                    @endphp
                    <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="flex h-10 w-10 items-center justify-center">
                                        @if ($violation->student->photo)
                                            <button
                                                @click="$store.imageModal.open('{{ asset('storage/' . $violation->student->photo) }}', '{{ $violation->student->name }}')"
                                                class="shrink-0 focus:outline-none focus:ring-2 focus:ring-primary rounded-full">
                                                <img src="{{ asset('storage/' . $violation->student->photo) }}"
                                                    alt="{{ $violation->student->name }}"
                                                    class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-slate-800 hover:scale-110 transition-transform cursor-zoom-in">
                                            </button>
                                        @else
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 font-bold text-xs ring-1 ring-{{ $color }}-600/20">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#0d141b] dark:text-white">{{ $violation->student->name }}</p>
                                        <p class="text-xs text-[#4c739a]">{{ $violation->student->nis }} -
                                            {{ $violation->student->rayon?->name }} - {{ $violation->student->room?->name }}</p>
                                    </div>
                                </div>

                                <div class="ml-13 space-y-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span
                                            class="text-sm font-medium text-[#0d141b] dark:text-white">{{ $violation->violationType->name }}</span>
                                        @php
                                            $category = $violation->violationType->category;
                                            $badgeColor = match ($category->name) {
                                                'Ringan' => 'bg-yellow-500/10 text-yellow-700',
                                                'Sedang' => 'bg-orange-500/10 text-orange-700',
                                                'Berat' => 'bg-red-500/10 text-red-700',
                                                default => 'bg-gray-500/10 text-gray-700'
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded {{ $badgeColor }}">
                                            {{ $category->name }}
                                        </span>
                                        <span class="px-2 py-0.5 text-xs bg-slate-100 dark:bg-slate-800 text-[#4c739a] rounded">
                                            {{ $violation->violationType->department->acronym }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-[#4c739a]">
                                        <span class="font-medium">Sanksi:</span> {{ Str::limit($violation->sanction, 80) }}
                                    </p>

                                    <div class="flex items-center gap-4 text-xs text-[#4c739a]">
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                            {{ $violation->date->format('d M Y') }}
                                        </span>
                                        @if($violation->sanction_status === 'completed')
                                            <span class="flex items-center gap-1 text-green-600">
                                                <span class="material-symbols-outlined text-[16px] fill-1">check_circle</span>
                                                Selesai
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1 text-red-600">
                                                <span class="material-symbols-outlined text-[16px] fill-1">pending</span>
                                                Belum Selesai
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <a href="{{ route('violations.show', $violation->id) }}"
                                    class="px-4 py-2 text-sm text-primary hover:bg-primary/10 rounded transition-colors text-center">
                                    Detail
                                </a>

                                @if(Auth::user()->isAdmin() || (Auth::user()->isDepartmentOfficer() && $violation->violationType->department_id == Auth::user()->department_id))
                                    @if(Route::has('violations.edit') && $violation->sanction_status !== 'completed')
                                        <div class="flex gap-2">
                                            <a href="{{ route('violations.edit', $violation->id) }}"
                                                class="w-full px-4 py-2 text-sm bg-yellow-500/10 text-yellow-600 hover:bg-yellow-500/20 rounded transition-colors text-center">
                                                Edit
                                            </a>
                                            <form action="{{ route('violations.destroy', $violation->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus pelanggaran {{ $violation->student->name }}?')"
                                                    class="w-full px-4 py-2 text-sm bg-red-500/10 text-red-600 hover:bg-red-500/20 rounded transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @endif

                                    @if($violation->sanction_status === 'pending')
                                        <button type="button"
                                            @click="verifyActionUrl = '{{ route('violations.verify-sanction', $violation->id) }}'; showVerifyModal = true"
                                            class="w-full px-4 py-2 text-sm bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
                                            Verifikasi
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-[#4c739a]">
                        <span class="material-symbols-outlined text-[64px] opacity-20">folder_open</span>
                        <p class="mt-4 text-lg">Tidak ada data pelanggaran</p>
                        <p class="text-sm mt-1">Belum ada pelanggaran yang tercatat</p>
                    </div>
                @endforelse
            </div>

            @if($violations->hasPages())
                <div class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-800">
                    {{ $violations->links() }}
                </div>
            @endif
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
                            <form :action="verifyActionUrl" method="POST">
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