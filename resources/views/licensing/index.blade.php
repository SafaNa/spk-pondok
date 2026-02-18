@extends('layouts.app')

@section('content')
    <div class="pt-1 px-3 pb-3" x-data="{ showApproveModal: false, approveActionUrl: '', showRejectModal: false, rejectActionUrl: '' }">
        {{-- Header with Gradient --}}
        <div class="mb-3 rounded-2xl bg-gradient-to-br from-blue-50 via-indigo-50/50 to-purple-50/30 px-5 py-4 dark:from-slate-800 dark:via-slate-800/80 dark:to-slate-800/50">
            <div class="flex items-center justify-between">
                <div>
                    <div class="mb-1 inline-flex items-center gap-1.5 rounded-full bg-white/80 px-2.5 py-0.5 text-xs font-medium text-primary backdrop-blur-sm dark:bg-slate-700/80">
                        <span class="material-symbols-outlined text-[14px]">verified_user</span>
                        Sistem Perizinan Pulang
                    </div>
                    <h1 class="font-outfit text-xl font-bold text-gray-900 dark:text-white">Dashboard Perizinan</h1>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Academic Year Filter --}}
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button type="button" @click="open = !open"
                            class="group flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-md hover:ring-gray-300 dark:bg-slate-800 dark:text-gray-200 dark:ring-slate-700 dark:hover:ring-slate-600">
                            <span class="material-symbols-outlined text-[22px]">calendar_month</span>
                            {{ $academicYears->firstWhere('id', $selectedYearId)?->name ?? 'Pilih Tahun' }}
                            <span class="material-symbols-outlined text-[18px] text-gray-400 transition-transform" :class="open && 'rotate-180'">expand_more</span>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 z-20 mt-2 w-48 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700 overflow-hidden" style="display: none;">
                            @foreach($academicYears as $year)
                                <a href="{{ route('licenses.index', ['academic_year_id' => $year->id]) }}"
                                    class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors {{ $selectedYearId == $year->id ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700' }}">
                                    @if($selectedYearId == $year->id)
                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                    @else
                                        <span class="w-4"></span>
                                    @endif
                                    {{ $year->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('licenses.create') }}"
                        class="group flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-md hover:ring-gray-300 dark:bg-slate-800 dark:text-gray-200 dark:ring-slate-700 dark:hover:ring-slate-600">
                        <span class="material-symbols-outlined text-[22px] transition-transform group-hover:scale-110">person_add</span>
                        Izin Individu
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="mb-3 grid gap-3 md:grid-cols-3">
            <div class="group relative overflow-hidden rounded-xl px-5 py-5 shadow-md transition-all hover:shadow-lg"
                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                <div class="absolute -right-3 -top-3 h-16 w-16 rounded-full" style="background: rgba(255, 255, 255, 0.1);"></div>
                <div class="relative flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg" style="background: rgba(255, 255, 255, 0.2);">
                        <span class="material-symbols-outlined text-[22px]">check_circle</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold leading-none">{{ $recentLicenses->where('status', 'approved')->count() }}</div>
                        <div class="text-xs mt-0.5" style="color: rgba(255, 255, 255, 0.9);">Disetujui</div>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl px-5 py-5 shadow-md transition-all hover:shadow-lg"
                style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); color: white;">
                <div class="absolute -right-3 -top-3 h-16 w-16 rounded-full" style="background: rgba(255, 255, 255, 0.1);"></div>
                <div class="relative flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg" style="background: rgba(255, 255, 255, 0.2);">
                        <span class="material-symbols-outlined text-[22px]">pending</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold leading-none">{{ $recentLicenses->where('status', 'pending')->count() }}</div>
                        <div class="text-xs mt-0.5" style="color: rgba(255, 255, 255, 0.9);">Pending</div>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl px-5 py-5 shadow-md transition-all hover:shadow-lg"
                style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); color: white;">
                <div class="absolute -right-3 -top-3 h-16 w-16 rounded-full" style="background: rgba(255, 255, 255, 0.1);"></div>
                <div class="relative flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg" style="background: rgba(255, 255, 255, 0.2);">
                        <span class="material-symbols-outlined text-[22px]">person</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold leading-none">{{ $recentLicenses->count() }}</div>
                        <div class="text-xs mt-0.5" style="color: rgba(255, 255, 255, 0.9);">Total Perizinan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Licenses --}}
        <div>
            <div class="mb-2 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Daftar Perizinan</h2>
                </div>
            </div>
            
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-slate-700 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">Santri</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">Keterangan</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">Status Hafalan</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">Status Izin</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($recentLicenses as $license)
                            <tr class="group transition-colors hover:bg-gray-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $initials = strtoupper(substr($license->student->name, 0, 1) . (str_contains($license->student->name, ' ') ? substr($license->student->name, strpos($license->student->name, ' ') + 1, 1) : substr($license->student->name, 1, 1)));
                                            $colors = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                                            $colorIndex = crc32($license->student->id) % count($colors);
                                            $color = $colors[$colorIndex];
                                        @endphp
                                        @if ($license->student->photo)
                                            <button type="button"
                                                @click="$store.imageModal.open('{{ asset('storage/' . $license->student->photo) }}', '{{ $license->student->name }}')"
                                                class="shrink-0 rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                                                <img src="{{ asset('storage/' . $license->student->photo) }}"
                                                    alt="{{ $license->student->name }}"
                                                    class="h-10 w-10 rounded-full object-cover ring-2 ring-white transition-all group-hover:ring-primary dark:ring-slate-800">
                                            </button>
                                        @else
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 font-bold text-sm ring-1 ring-{{ $color }}-600/20">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('licenses.show', $license->id) }}" class="font-semibold text-gray-900 dark:text-white hover:text-primary transition-colors">{{ $license->student->name }}</a>
                                            <div class="text-xs text-gray-500">{{ $license->student->room->name ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs mb-1">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ Str::limit($license->description ?? '-', 30) }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $license->start_date->format('d M') }} - {{ $license->end_date->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $license->memorization_check ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300' }}">
                                        {{ $license->memorization_check ? 'Sudah Cek' : 'Belum Cek' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center w-fit rounded-full px-3 py-1.5 text-xs font-semibold 
                                            {{ $license->status === 'approved' 
                                                ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30' 
                                                : ($license->status === 'rejected' 
                                                    ? 'bg-red-50 text-red-700 ring-1 ring-red-600/10 dark:bg-red-400/10 dark:text-red-400 dark:ring-red-400/30' 
                                                    : 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-500 dark:ring-amber-400/20') }}">
                                            {{ ucfirst($license->status) }}
                                        </span>
                                        @if($license->student->pending_violations_count > 0)
                                            <span class="inline-flex items-center w-fit gap-1 rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-medium text-red-700 ring-1 ring-red-600/10 dark:bg-red-400/10 dark:text-red-400 dark:ring-red-400/30" title="Santri memiliki pelanggaran yang belum selesai">
                                                <span class="material-symbols-outlined text-[12px]">warning</span>
                                                Ada Pelanggaran
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($license->status === 'pending')
                                            @if($license->student->pending_violations_count > 0)
                                                <button type="button" disabled
                                                    class="cursor-not-allowed rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-400 transition-colors dark:bg-slate-700 dark:text-slate-500"
                                                    title="Selesaikan pelanggaran terlebih dahulu">
                                                    Approve
                                                </button>
                                            @else
                                                <button type="button"
                                                    @click="approveActionUrl = '{{ route('licenses.approve', $license->id) }}'; showApproveModal = true"
                                                    class="rounded-lg bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary hover:bg-primary/20 transition-colors">
                                                    Approve
                                                </button>
                                            @endif
                                            <button type="button"
                                                @click="rejectActionUrl = '{{ route('licenses.reject', $license->id) }}'; showRejectModal = true"
                                                class="rounded-lg bg-red-500/10 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-500/20 transition-colors">
                                                Reject
                                            </button>
                                        @endif
                                        <a href="{{ route('licenses.show', $license->id) }}" 
                                            class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-primary dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors"
                                            title="Detail Izin">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </a>
                                        <a href="{{ route('licenses.edit', $license->id) }}" 
                                            class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-primary dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors"
                                            title="Edit Izin">
                                            <span class="material-symbols-outlined text-[20px]">edit_square</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-slate-700">
                                            <span class="material-symbols-outlined text-[32px] text-gray-400">inbox</span>
                                        </div>
                                        <p class="font-medium text-gray-500 dark:text-gray-400">Belum ada data perizinan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="mt-4">
                {{ $recentLicenses->links() }}
            </div>
        </div>

        {{-- Approve Confirmation Modal (self-contained, same pattern as violations verify modal) --}}
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
                                            Apakah Anda yakin ingin menyetujui izin kepulangan santri ini?
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <form :action="approveActionUrl" method="POST">
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
                                            Apakah Anda yakin ingin menolak izin kepulangan santri ini?
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-slate-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <form :action="rejectActionUrl" method="POST">
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
