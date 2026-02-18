@extends('layouts.app')

@section('title', 'Detail Perizinan')
@section('breadcrumb', 'Detail')
@section('breadcrumb_parent', 'Perizinan')
@section('breadcrumb_parent_route', 'licenses.index')
@section('mobile_title', 'Detail Perizinan')

@section('content')
    <div class="flex flex-col gap-6" x-data="{ showApproveModal: false, showRejectModal: false }">
        {{-- Back Button --}}
        <a href="{{ route('licenses.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Daftar</span>
        </a>

        {{-- Status Banner --}}
        @if($license->status === 'approved')
            <div class="bg-gradient-to-r from-green-500/10 to-emerald-500/10 rounded-2xl p-6 border border-green-500/20">
                <div class="flex items-center gap-5">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border border-green-500/20 text-green-600 shadow-sm">
                        <span class="material-symbols-outlined text-[36px] fill-1">check_circle</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-green-700 dark:text-green-500">Izin Disetujui</h3>
                        <p class="text-green-600/80 dark:text-green-400 mt-1 text-sm">
                            Perizinan pulang santri telah disetujui dan berlaku mulai {{ $license->start_date->format('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        @elseif($license->status === 'rejected')
            <div class="bg-gradient-to-r from-red-500/10 to-rose-500/10 rounded-2xl p-6 border border-red-500/20">
                <div class="flex items-center gap-5">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border border-red-500/20 text-red-600 shadow-sm">
                        <span class="material-symbols-outlined text-[36px] fill-1">cancel</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-red-700 dark:text-red-500">Izin Ditolak</h3>
                        <p class="text-red-600/80 dark:text-red-400 mt-1 text-sm">
                            Perizinan pulang santri ini telah ditolak
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-amber-500/10 to-orange-500/10 rounded-2xl p-6 border border-amber-500/20">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm border border-amber-500/20 text-amber-600 shadow-sm">
                            <span class="material-symbols-outlined text-[36px] fill-1">hourglass_top</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-amber-700 dark:text-amber-500">Menunggu Persetujuan</h3>
                            <p class="text-amber-600/80 dark:text-amber-400 mt-1 text-sm">Perizinan ini masih menunggu persetujuan dari pengurus</p>
                        </div>
                    </div>
                    @if(!($license->student->pending_violations_count > 0))
                        <button type="button" @click="showApproveModal = true"
                            class="w-full sm:w-auto px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary/90 transition-all shadow-lg hover:shadow-primary/30 font-bold flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                            <span class="material-symbols-outlined">check_circle</span>
                            <span>Setujui Izin</span>
                        </button>
                    @endif
                    <button type="button" @click="showRejectModal = true"
                        class="w-full sm:w-auto px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all shadow-lg hover:shadow-red-500/30 font-bold flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                        <span class="material-symbols-outlined">cancel</span>
                        <span>Tolak Izin</span>
                    </button>
                </div>
                @if($license->student->pending_violations_count > 0)
                    <div class="mt-4 flex items-center gap-2 rounded-xl bg-red-50 dark:bg-red-900/20 p-3 border border-red-200 dark:border-red-800">
                        <span class="material-symbols-outlined text-red-600 text-[20px]">warning</span>
                        <p class="text-sm text-red-700 dark:text-red-400 font-medium">
                            Santri memiliki {{ $license->student->pending_violations_count }} pelanggaran yang belum selesai. Selesaikan terlebih dahulu sebelum menyetujui izin.
                        </p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Santri Info Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800">
            <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-800">
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Informasi Santri</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-5">
                    @php
                        $initials = strtoupper(substr($license->student->name, 0, 1) . (str_contains($license->student->name, ' ') ? substr($license->student->name, strpos($license->student->name, ' ') + 1, 1) : substr($license->student->name, 1, 1)));
                    @endphp
                    @if ($license->student->photo)
                        <button type="button"
                            @click="$store.imageModal.open('{{ asset('storage/' . $license->student->photo) }}', '{{ $license->student->name }}')"
                            class="shrink-0 rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                            <img src="{{ asset('storage/' . $license->student->photo) }}"
                                alt="{{ $license->student->name }}"
                                class="h-16 w-16 rounded-full object-cover ring-2 ring-white dark:ring-slate-800">
                        </button>
                    @else
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-xl">
                            {{ $initials }}
                        </div>
                    @endif
                    <div>
                        <p class="text-xl font-bold text-[#0d141b] dark:text-white">{{ $license->student->name }}</p>
                        <p class="text-sm text-[#4c739a] mt-0.5">NIS: {{ $license->student->nis ?? '-' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg px-4 py-3">
                        <p class="text-xs text-[#4c739a] mb-1">NIS</p>
                        <p class="text-sm font-semibold text-[#0d141b] dark:text-white">{{ $license->student->nis ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg px-4 py-3">
                        <p class="text-xs text-[#4c739a] mb-1">Jenis Kelamin</p>
                        <p class="text-sm font-semibold text-[#0d141b] dark:text-white">{{ $license->student->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg px-4 py-3">
                        <p class="text-xs text-[#4c739a] mb-1">Kamar</p>
                        <p class="text-sm font-semibold text-[#0d141b] dark:text-white">{{ $license->student->room->name ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg px-4 py-3">
                        <p class="text-xs text-[#4c739a] mb-1">Rayon</p>
                        <p class="text-sm font-semibold text-[#0d141b] dark:text-white">{{ $license->student->rayon->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- License Details --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800">
            <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white">Detail Perizinan</h3>
                <a href="{{ route('licenses.edit', $license->id) }}"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold text-primary hover:bg-primary/10 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Edit
                </a>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <p class="text-sm text-[#4c739a] mb-1">Tahun Ajaran</p>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-primary">calendar_month</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                            {{ $license->academicYear->name ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-[#4c739a] mb-1">Tipe Izin</p>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px] text-primary">badge</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $license->type === 'individual' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' }}">
                                {{ $license->type === 'individual' ? 'Individu' : 'Massal' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-[#4c739a] mb-1">Status</p>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px] {{ $license->status === 'approved' ? 'text-green-500' : ($license->status === 'rejected' ? 'text-red-500' : 'text-amber-500') }}">
                                {{ $license->status === 'approved' ? 'check_circle' : ($license->status === 'rejected' ? 'cancel' : 'pending') }}
                            </span>
                            @php
                                $statusClass = match($license->status) {
                                    'approved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    default => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">
                                {{ ucfirst($license->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-[#4c739a] mb-1">Tanggal Mulai</p>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px] text-primary">calendar_today</span>
                            <p class="font-medium text-[#0d141b] dark:text-white">
                                {{ $license->start_date->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-[#4c739a] mb-1">Tanggal Selesai</p>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px] text-primary">event</span>
                            <p class="font-medium text-[#0d141b] dark:text-white">
                                {{ $license->end_date->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                @php
                    $duration = $license->start_date->diffInDays($license->end_date) + 1;
                @endphp
                <div>
                    <p class="text-sm text-[#4c739a] mb-2">Durasi Izin</p>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/20">
                        <span class="material-symbols-outlined text-[20px] text-indigo-600">schedule</span>
                        <span class="font-bold text-lg text-indigo-700 dark:text-indigo-400">{{ $duration }} Hari</span>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-[#4c739a] mb-2">Status Hafalan</p>
                    <div class="flex items-center gap-2">
                        @if($license->memorization_check)
                            <span class="material-symbols-outlined text-[20px] text-green-600">verified</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                Sudah Dicek
                            </span>
                        @else
                            <span class="material-symbols-outlined text-[20px] text-slate-400">unpublished</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400">
                                Belum Dicek
                            </span>
                        @endif
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                    <p class="text-sm font-medium text-[#4c739a] mb-2">Keterangan</p>
                    <p class="text-[#0d141b] dark:text-white leading-relaxed">{{ $license->description ?? '-' }}</p>
                </div>

                @if($license->notes)
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-400 mb-2">Catatan Tambahan</p>
                        <p class="text-[#0d141b] dark:text-white">{{ $license->notes }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-sm text-[#4c739a] mb-2">Tanggal Input</p>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px] text-primary">schedule</span>
                        <p class="font-medium text-[#0d141b] dark:text-white">
                            {{ $license->created_at->format('d F Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Approve Confirmation Modal --}}
        <div x-show="showApproveModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true"
            style="display: none;">
            <div x-show="showApproveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showApproveModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 sm:mx-0 sm:h-10 sm:w-10">
                                    <span class="material-symbols-outlined text-[24px]">check_circle</span>
                                </div>
                                <div class="text-center sm:ml-4 sm:text-left">
                                    <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white" id="modal-title">
                                        Setujui Izin
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500 dark:text-slate-400">
                                            Apakah Anda yakin ingin menyetujui izin kepulangan <strong>{{ $license->student->name }}</strong>?
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <form action="{{ route('licenses.approve', $license->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex w-full justify-center rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary/90 sm:ml-3 sm:w-auto">
                                    Ya, Setujui
                                </button>
                            </form>
                            <button type="button" @click="showApproveModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-slate-800 px-3 py-2 text-sm font-semibold text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700 sm:mt-0 sm:w-auto">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reject Confirmation Modal --}}
        <div x-show="showRejectModal" class="relative z-50" aria-labelledby="reject-modal-title" role="dialog" aria-modal="true"
            style="display: none;">
            <div x-show="showRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showRejectModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 sm:mx-0 sm:h-10 sm:w-10">
                                    <span class="material-symbols-outlined text-[24px]">cancel</span>
                                </div>
                                <div class="text-center sm:ml-4 sm:text-left">
                                    <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white" id="reject-modal-title">
                                        Tolak Izin
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500 dark:text-slate-400">
                                            Apakah Anda yakin ingin menolak izin kepulangan <strong>{{ $license->student->name }}</strong>?
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <form action="{{ route('licenses.reject', $license->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                    Ya, Tolak
                                </button>
                            </form>
                            <button type="button" @click="showRejectModal = false"
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
