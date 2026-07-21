@extends('layouts.app')

@section('title', 'Detail Izin Aktif — ' . $license->student->name)
@section('breadcrumb', 'Detail Check-in')
@section('breadcrumb_parent', 'Santri Izin (Aktif)')
@section('breadcrumb_parent_route', 'admin.licenses.active')
@section('mobile_title', 'Detail Check-in')

@section('content')
<div class="flex flex-col gap-6" x-data="{}">

    {{-- Back --}}
    <a href="{{ route('admin.licenses.active') }}"
        class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group">
        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
            <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
        </div>
        <span class="text-sm font-semibold">Kembali ke Santri Izin (Aktif)</span>
    </a>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 rounded-2xl p-4 border border-green-200 dark:border-green-800 flex items-center gap-3">
            <span class="material-symbols-outlined text-green-600 text-[24px]">check_circle</span>
            <p class="text-sm font-semibold text-green-700 dark:text-green-400">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Status Banner --}}
    @php
        $isLate = now()->startOfDay()->gt($license->end_date);
        $lateDays = $isLate ? now()->startOfDay()->diffInDays($license->end_date) : 0;
    @endphp

    @if($license->actual_return_date)
        <div class="bg-gradient-to-r from-emerald-500/10 to-teal-500/10 rounded-2xl p-6 border border-emerald-500/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
            <div class="flex items-center gap-5">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 border border-emerald-500/20 text-emerald-600 shadow-sm flex-shrink-0">
                    <span class="material-symbols-outlined text-[32px]">how_to_reg</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-emerald-700 dark:text-emerald-400">Santri Sudah Kembali</h3>
                    <p class="text-emerald-600/80 dark:text-emerald-400 text-sm mt-0.5">
                        Santri telah ditandai kembali pada <strong>{{ Carbon\Carbon::parse($license->actual_return_date)->locale('id')->translatedFormat('d F Y') }}</strong>.
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.licenses.show', $license->id) }}" class="flex shrink-0 items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-bold text-emerald-700 bg-white hover:bg-emerald-50 border border-emerald-200 transition-all">
                <span class="material-symbols-outlined text-[20px]">visibility</span>
                Lihat Detail Lengkap
            </a>
        </div>
    @elseif($isLate)
        <div class="bg-gradient-to-r from-rose-500/10 to-red-500/10 rounded-2xl p-6 border border-rose-500/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
            <div class="flex items-center gap-5">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 border border-rose-500/20 text-rose-600 shadow-sm flex-shrink-0">
                    <span class="material-symbols-outlined text-[32px]">schedule</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-rose-700 dark:text-rose-400">Telat Kembali!</h3>
                    <p class="text-rose-600/80 dark:text-rose-400 text-sm mt-0.5">
                        Sudah melewati batas waktu <strong>{{ $lateDays }} hari</strong> sejak {{ $license->end_date->locale('id')->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>
            {{-- Tombol Check-in --}}
            <form id="form-checkin-{{ $license->id }}" action="{{ route('admin.licenses.return', $license->id) }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="actual_return_date" value="{{ date('Y-m-d') }}">
            </form>
            <button type="button"
                @click="$store.deleteModal.open(
                    document.getElementById('form-checkin-{{ $license->id }}'),
                    'Anda yakin santri {{ addslashes($license->student->name) }} sudah kembali?',
                    'Tandai Kembali',
                    'Ya, Sudah Kembali',
                    'bg-emerald-600 hover:bg-emerald-700'
                )"
                class="flex shrink-0 items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 transition-all">
                <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                Tandai Kembali
            </button>
        </div>
    @else
        <div class="bg-gradient-to-r from-amber-500/10 to-yellow-500/10 rounded-2xl p-6 border border-amber-500/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
            <div class="flex items-center gap-5">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 border border-amber-500/20 text-amber-600 shadow-sm flex-shrink-0">
                    <span class="material-symbols-outlined text-[32px]">flight_takeoff</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-amber-700 dark:text-amber-400">Sedang Izin</h3>
                    <p class="text-amber-600/80 dark:text-amber-400 text-sm mt-0.5">
                        Jatuh tempo kembali pada <strong>{{ $license->end_date->locale('id')->translatedFormat('d F Y') }}</strong>
                        ({{ $license->end_date->diffForHumans() }})
                    </p>
                </div>
            </div>
            <form id="form-checkin-{{ $license->id }}" action="{{ route('admin.licenses.return', $license->id) }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="actual_return_date" value="{{ date('Y-m-d') }}">
            </form>
            <button type="button"
                @click="$store.deleteModal.open(
                    document.getElementById('form-checkin-{{ $license->id }}'),
                    'Anda yakin santri {{ addslashes($license->student->name) }} sudah kembali?',
                    'Tandai Kembali',
                    'Ya, Sudah Kembali',
                    'bg-emerald-600 hover:bg-emerald-700'
                )"
                class="flex shrink-0 items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 transition-all">
                <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                Tandai Kembali
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Info Santri --}}
        <div class="lg:col-span-1 flex flex-col gap-6">

            {{-- Profil Santri --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                @php
                    $name = $license->student->name;
                    $initials = strtoupper(substr($name, 0, 1) . (str_contains($name, ' ') ? substr($name, strpos($name, ' ') + 1, 1) : substr($name, 1, 1)));
                @endphp
                <div class="flex flex-col items-center text-center gap-3 mb-5">
                    @if($license->student->photo)
                        <img src="{{ asset('storage/' . $license->student->photo) }}" alt="{{ $name }}"
                            class="h-20 w-20 rounded-full object-cover ring-4 ring-primary/20">
                    @else
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-2xl">
                            {{ $initials }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $name }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $license->student->identifier_label ?? 'NIS' }}. {{ $license->student->nis ?? '-' }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-sm">
                        <span class="material-symbols-outlined text-[18px] text-slate-400">domain</span>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Rayon</p>
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $license->student->rayon->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="material-symbols-outlined text-[18px] text-slate-400">meeting_room</span>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Kamar</p>
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $license->student->room->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="material-symbols-outlined text-[18px] text-slate-400">wc</span>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Gender</p>
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $license->student->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="material-symbols-outlined text-[18px] text-slate-400">location_on</span>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Alamat</p>
                            <p class="font-semibold text-slate-800 dark:text-white text-sm leading-snug">{{ $license->student->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Orang Tua & Wali --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4">Orang Tua & Wali</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Ayah</p>
                        <p class="font-semibold text-slate-800 dark:text-white text-sm">{{ $license->student->father_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Ibu</p>
                        <p class="font-semibold text-slate-800 dark:text-white text-sm">{{ $license->student->mother_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Wali</p>
                        @forelse($license->student->guardians as $guardian)
                            <p class="font-semibold text-slate-800 dark:text-white text-sm">{{ $guardian->name }} <span class="text-slate-400 font-normal">({{ $guardian->phone ?? '-' }})</span></p>
                        @empty
                            <p class="text-slate-400 text-sm italic">Belum ada data wali</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Izin --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Detail Izin --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Detail Perizinan</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Kategori Izin</p>
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $license->leaveCategory->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Alasan</p>
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $license->leaveReason->reason ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Tanggal Berangkat</p>
                            <p class="font-semibold text-slate-800 dark:text-white flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] text-primary">flight_takeoff</span>
                                {{ $license->start_date->locale('id')->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Tanggal Kembali (Rencana)</p>
                            <p class="font-semibold {{ $isLate ? 'text-rose-600' : 'text-slate-800 dark:text-white' }} flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] {{ $isLate ? 'text-rose-500' : 'text-primary' }}">event</span>
                                {{ $license->end_date->locale('id')->translatedFormat('d F Y') }}
                                @if($isLate)
                                    <span class="text-xs bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full font-bold">Telat {{ $lateDays }} hari</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @php $duration = $license->start_date->diffInDays($license->end_date) + 1; @endphp
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Durasi Izin</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            {{ $duration }} Hari
                        </span>
                    </div>
                    @if($license->description)
                        <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                            <p class="text-xs text-slate-500 mb-1">Keterangan Tambahan</p>
                            <p class="text-slate-700 dark:text-slate-300">{{ $license->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Timeline Izin</h3>
                </div>
                <div class="p-6">
                    <div class="relative pl-6 space-y-6">
                        <div class="absolute left-2 top-2 bottom-2 w-0.5 bg-slate-200 dark:bg-slate-700"></div>

                        {{-- Pengajuan --}}
                        <div class="relative flex gap-4">
                            <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full bg-blue-500 ring-4 ring-white dark:ring-slate-900">
                                <span class="material-symbols-outlined text-white text-[12px]">edit_note</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-white">Izin Diajukan</p>
                                <p class="text-xs text-slate-500">{{ $license->created_at->locale('id')->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                        </div>

                        {{-- Disetujui --}}
                        <div class="relative flex gap-4">
                            <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full bg-green-500 ring-4 ring-white dark:ring-slate-900">
                                <span class="material-symbols-outlined text-white text-[12px]">check</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-white">Izin Disetujui</p>
                                <p class="text-xs text-slate-500">{{ $license->updated_at->locale('id')->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                        </div>

                        {{-- Jatuh Tempo --}}
                        <div class="relative flex gap-4">
                            <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full {{ $isLate ? 'bg-rose-500' : 'bg-amber-400' }} ring-4 ring-white dark:ring-slate-900">
                                <span class="material-symbols-outlined text-white text-[12px]">event</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold {{ $isLate ? 'text-rose-600' : 'text-slate-800 dark:text-white' }}">
                                    Jatuh Tempo Kembali {{ $isLate ? '(Telat!)' : '' }}
                                </p>
                                <p class="text-xs text-slate-500">{{ $license->end_date->locale('id')->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>

                        @if($license->actual_return_date)
                        {{-- Sudah Kembali --}}
                        <div class="relative flex gap-4">
                            <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500 ring-4 ring-white dark:ring-slate-900">
                                <span class="material-symbols-outlined text-white text-[12px]">how_to_reg</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">Santri Sudah Kembali</p>
                                <p class="text-xs text-slate-500">{{ Carbon\Carbon::parse($license->actual_return_date)->locale('id')->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        @else
                        {{-- Belum Kembali --}}
                        <div class="relative flex gap-4">
                            <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full bg-slate-300 dark:bg-slate-600 ring-4 ring-white dark:ring-slate-900">
                                <span class="material-symbols-outlined text-white text-[12px]">how_to_reg</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-400">Belum Ditandai Kembali</p>
                                <p class="text-xs text-slate-400">Klik tombol "Tandai Kembali" di atas</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
