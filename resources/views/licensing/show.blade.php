@extends('layouts.app')

@section('title', 'Detail Perizinan')
@section('breadcrumb', 'Detail')
@section('breadcrumb_parent', 'Perizinan')
@section('breadcrumb_parent_route', 'admin.licenses.index')
@section('mobile_title', 'Detail Perizinan')

@section('content')
    <div class="flex flex-col gap-6" x-data="licenseApproval">
        {{-- Back Button --}}
        <a href="{{ route('admin.licenses.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Daftar</span>
        </a>

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-4 border border-red-200 dark:border-red-800 flex items-center gap-3">
                <span class="material-symbols-outlined text-red-600 text-[24px]">error</span>
                <p class="text-sm font-semibold text-red-700 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 rounded-2xl p-4 border border-green-200 dark:border-green-800 flex items-center gap-3">
                <span class="material-symbols-outlined text-green-600 text-[24px]">check_circle</span>
                <p class="text-sm font-semibold text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif

    @php
        $isLate = now()->startOfDay()->gt($license->end_date);
        $lateDays = $isLate ? now()->startOfDay()->diffInDays($license->end_date) : 0;
        
        $actualReturn = $license->actual_return_date ? Carbon\Carbon::parse($license->actual_return_date)->startOfDay() : null;
        $expectedReturn = $license->end_date->startOfDay();
        $isLateReturn = $actualReturn ? $actualReturn->gt($expectedReturn) : false;
        $lateDaysReturn = $actualReturn && $isLateReturn ? $actualReturn->diffInDays($expectedReturn) : 0;

        $extensions = $license->extensions ?? collect();
        $activeExt  = $extensions->where('status','pending')->first();
        $isPendingExtension = $activeExt !== null;
    @endphp

    {{-- Status Banner --}}
    @if($license->status === 'approved')
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
            </div>
        @else
            <div class="bg-gradient-to-r from-green-500/10 to-emerald-500/10 rounded-2xl p-6 border border-green-500/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                <div class="flex items-center gap-5">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 border border-green-500/20 text-green-600 shadow-sm flex-shrink-0">
                        <span class="material-symbols-outlined text-[32px]">check_circle</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-green-700 dark:text-green-400 flex flex-wrap items-center gap-2">
                            Izin Disetujui
                            @if($license->is_emergency)
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">Darurat</span>
                            @endif
                            @if($isPendingExtension)
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400 shadow-sm animate-pulse flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">more_time</span>
                                    Menunggu Perpanjangan
                                </span>
                            @endif
                        </h3>
                        <p class="text-green-600/80 dark:text-green-400 text-sm mt-0.5">
                            Berlaku mulai {{ $license->start_date->locale('id')->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @elseif($license->status === 'rejected')
        <div class="bg-gradient-to-r from-red-500/10 to-rose-500/10 rounded-2xl p-6 border border-red-500/20 flex items-center gap-5">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 border border-red-500/20 text-red-600 shadow-sm flex-shrink-0">
                <span class="material-symbols-outlined text-[32px]">cancel</span>
            </div>
            <div>
                <h3 class="text-lg font-bold text-red-700 dark:text-red-400">Izin Ditolak</h3>
                <p class="text-red-600/80 dark:text-red-400 text-sm mt-0.5">Perizinan ini telah ditolak</p>
            </div>
        </div>
    @else
        <div class="bg-gradient-to-r from-amber-500/10 to-orange-500/10 rounded-2xl p-6 border border-amber-500/20 flex items-center gap-5">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 border border-amber-500/20 text-amber-600 shadow-sm flex-shrink-0">
                <span class="material-symbols-outlined text-[32px]">hourglass_top</span>
            </div>
            <div>
                <h3 class="text-lg font-bold text-amber-700 dark:text-amber-400 flex flex-wrap items-center gap-2">
                    Menunggu Validasi
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 shadow-sm animate-pulse flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">new_releases</span>
                        Baru
                    </span>
                </h3>
                <p class="text-amber-600/80 dark:text-amber-400 text-sm mt-0.5">Periksa hasil validasi di bawah sebelum menyetujui</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Info Santri & Wali --}}
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
                            class="h-20 w-20 rounded-full object-cover ring-4 ring-primary/20 flex-shrink-0">
                    @else
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-2xl flex-shrink-0">
                            {{ $initials }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $name }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">NIS. {{ $license->student->nis ?? '-' }}</p>
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
                        <span class="material-symbols-outlined text-[18px] text-slate-400">gavel</span>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Pelanggaran Aktif</p>
                            <p class="font-semibold {{ $license->student->pending_violations_count > 0 ? 'text-red-600 dark:text-red-400' : 'text-slate-800 dark:text-white' }}">
                                {{ $license->student->pending_violations_count }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="material-symbols-outlined text-[18px] text-slate-400">assignment_turned_in</span>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Total Izin Disetujui</p>
                            <p class="font-semibold text-slate-800 dark:text-white">
                                {{ $approvedCount }}{{ $maxLeaves ? '/'.$maxLeaves : '' }}
                            </p>
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

                        {{-- Status Persetujuan --}}
                        <div class="relative flex gap-4">
                            @if($license->status === 'approved')
                                <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full bg-green-500 ring-4 ring-white dark:ring-slate-900">
                                    <span class="material-symbols-outlined text-white text-[12px]">check</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">Izin Disetujui</p>
                                    <p class="text-xs text-slate-500">{{ $license->updated_at->locale('id')->translatedFormat('d F Y, H:i') }}</p>
                                </div>
                            @elseif($license->status === 'rejected')
                                <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 ring-4 ring-white dark:ring-slate-900">
                                    <span class="material-symbols-outlined text-white text-[12px]">close</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-red-600 dark:text-red-400">Izin Ditolak</p>
                                    <p class="text-xs text-slate-500">{{ $license->updated_at->locale('id')->translatedFormat('d F Y, H:i') }}</p>
                                </div>
                            @else
                                <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full bg-amber-400 ring-4 ring-white dark:ring-slate-900">
                                    <span class="material-symbols-outlined text-white text-[12px]">hourglass_top</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-amber-600 dark:text-amber-400">Menunggu Persetujuan</p>
                                    <p class="text-xs text-slate-500">Menunggu verifikasi admin</p>
                                </div>
                            @endif
                        </div>

                        {{-- Riwayat Perpanjangan di Timeline --}}
                        @if(isset($extensions) && $extensions->where('status', '!=', 'rejected')->count() > 0)
                            @foreach($extensions->where('status', '!=', 'rejected')->values() as $index => $ext)
                                {{-- Node: Pengajuan Perpanjangan --}}
                                <div class="relative flex gap-4">
                                    <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full bg-blue-400 ring-4 ring-white dark:ring-slate-900">
                                        <span class="material-symbols-outlined text-white text-[12px]">edit_note</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-blue-600 dark:text-blue-400">Pengajuan Perpanjangan #{{ $index + 1 }}</p>
                                        <p class="text-xs text-slate-500">
                                            Diajukan {{ $ext->created_at->locale('id')->translatedFormat('d F Y, H:i') }}
                                        </p>
                                    </div>
                                </div>
                                
                                {{-- Node: Status Perpanjangan --}}
                                @if($ext->status === 'approved')
                                    <div class="relative flex gap-4">
                                        <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full bg-violet-500 ring-4 ring-white dark:ring-slate-900">
                                            <span class="material-symbols-outlined text-white text-[12px]">check</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-violet-600 dark:text-violet-400">Disetujui</p>
                                            <p class="text-xs text-slate-500">
                                                Diperpanjang hingga {{ Carbon\Carbon::parse($ext->requested_new_end_date)->locale('id')->translatedFormat('d F Y') }}
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="relative flex gap-4">
                                        <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full bg-amber-400 ring-4 ring-white dark:ring-slate-900">
                                            <span class="material-symbols-outlined text-white text-[12px]">hourglass_top</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-amber-600 dark:text-amber-400">Menunggu Persetujuan</p>
                                            <p class="text-xs text-slate-500">
                                                Meminta perpanjangan hingga {{ Carbon\Carbon::parse($ext->requested_new_end_date)->locale('id')->translatedFormat('d F Y') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        {{-- Jatuh Tempo --}}
                        @if($license->status !== 'rejected')
                            <div class="relative flex gap-4">
                                <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full {{ ($isLate && !$license->actual_return_date) ? 'bg-rose-500' : 'bg-amber-400' }} ring-4 ring-white dark:ring-slate-900">
                                    <span class="material-symbols-outlined text-white text-[12px]">event</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold {{ ($isLate && !$license->actual_return_date) ? 'text-rose-600' : 'text-slate-800 dark:text-white' }}">
                                        Jatuh Tempo Kembali {{ ($isLate && !$license->actual_return_date) ? '(Telat!)' : '' }}
                                    </p>
                                    <p class="text-xs text-slate-500">{{ $license->end_date->locale('id')->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>
                        @endif

                        {{-- Status Kepulangan --}}
                        @if($license->status === 'approved')
                            @if($license->actual_return_date)
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
                                <div class="relative flex gap-4">
                                    <div class="absolute -left-6 flex h-5 w-5 items-center justify-center rounded-full bg-slate-300 dark:bg-slate-600 ring-4 ring-white dark:ring-slate-900">
                                        <span class="material-symbols-outlined text-white text-[12px]">how_to_reg</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-400">Belum Kembali</p>
                                        <p class="text-xs text-slate-400">Santri belum melakukan check-in kembali</p>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Validasi, Detail & Timeline --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            {{-- Validasi Section (hanya tampil saat pending) --}}
            @if($license->status === 'pending')
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">checklist</span>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white">Hasil Validasi</h3>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($validation as $key => $check)
                            @php
                                $isPending = $check['pending'];
                                $pass      = !$isPending && $check['pass'];
                            @endphp
                            <div class="flex items-center gap-4 px-6 py-4">
                                {{-- Icon --}}
                                @if($key === 'alasan' && $canSkip)
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full flex-shrink-0 bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">
                                        <span class="material-symbols-outlined text-[20px]">warning</span>
                                    </div>
                                @elseif($isPending)
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full flex-shrink-0 bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                                        <span class="material-symbols-outlined text-[20px]">schedule</span>
                                    </div>
                                @elseif($pass)
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full flex-shrink-0 bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                        <span class="material-symbols-outlined text-[20px]">check</span>
                                    </div>
                                @else
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full flex-shrink-0 bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                                        <span class="material-symbols-outlined text-[20px]">close</span>
                                    </div>
                                @endif

                                {{-- Label & Detail --}}
                                <div class="flex-1">
                                    <p class="text-sm font-semibold {{ $isPending ? 'text-slate-400 dark:text-slate-500' : 'text-slate-800 dark:text-white' }}">
                                        {{ $check['label'] }}
                                    </p>
                                    <p class="text-xs mt-0.5 {{ ($key === 'alasan' && $canSkip) ? 'text-orange-600 dark:text-orange-400 font-medium' : ($isPending ? 'text-slate-400 dark:text-slate-500' : ($pass ? 'text-slate-500 dark:text-slate-400' : 'text-red-600 dark:text-red-400 font-medium')) }}">
                                        {{ $check['detail'] }}
                                    </p>
                                </div>

                                {{-- Badge --}}
                                @if($key === 'alasan' && $canSkip)
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">warning</span>
                                        Darurat
                                    </span>
                                @elseif($isPending)
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                                        Segera
                                    </span>
                                @elseif($pass)
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        Lolos
                                    </span>
                                @else
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                        Tidak Lolos
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if(!$allPass)
                        <div class="px-6 py-4 bg-amber-50 dark:bg-amber-900/20 border-t border-amber-100 dark:border-amber-800">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" x-model="isOverride" 
                                    class="mt-1 w-4 h-4 text-amber-600 rounded border-amber-300 focus:ring-amber-500">
                                <div>
                                    <span class="text-sm font-bold text-amber-800 dark:text-amber-400">Loloskan Validasi</span>
                                    <p class="text-xs text-amber-600 dark:text-amber-500 mt-0.5">Paksakan persetujuan izin meskipun ada syarat yang tidak lolos.</p>
                                </div>
                            </label>
                            
                            <div x-show="isOverride" x-transition class="mt-4 pl-7">
                                <label class="block text-xs font-semibold text-amber-700 dark:text-amber-400 mb-1">Alasan Meloloskan <span class="text-red-500">*</span></label>
                                <textarea x-model="overrideReason" rows="2" 
                                    class="w-full rounded-lg border border-amber-200 bg-white px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                                    placeholder="Masukkan alasan mengapa validasi ini diloloskan..."></textarea>
                            </div>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="px-6 py-5 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row gap-3">
                        <button type="button" @click="showApproveModal = true"
                            :disabled="!canApprove()"
                            :class="canApprove() ? 'bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20 hover:shadow-primary/30' : 'opacity-50 cursor-not-allowed bg-slate-300 dark:bg-slate-700 text-slate-500'"
                            class="flex-1 px-5 py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all">
                            <span class="material-symbols-outlined">check_circle</span>
                            Setujui Izin
                        </button>
                        <button type="button" @click="showRejectModal = true"
                            class="flex-1 sm:flex-none px-5 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 font-bold flex items-center justify-center gap-2 shadow-lg shadow-red-500/20 transition-all">
                            <span class="material-symbols-outlined">cancel</span>
                            Tolak
                        </button>
                    </div>
                </div>
            @endif

            {{-- Detail Perizinan --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Detail Perizinan</h3>
                    <div class="flex items-center gap-2">
                        @if($license->status === 'pending')
                            <a href="{{ route('admin.licenses.edit', $license->id) }}"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold text-primary hover:bg-primary/10 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                Edit
                            </a>
                            <form id="form-del-license-show" action="{{ route('admin.licenses.destroy', $license->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button"
                                @click="$store.deleteModal.open(
                                    document.getElementById('form-del-license-show'),
                                    'Yakin ingin menghapus pengajuan izin {{ addslashes($license->student->name ?? '') }}?'
                                )"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                Hapus
                            </button>
                        @endif
                    </div>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Tahun Ajaran</p>
                            <p class="font-semibold text-slate-800 dark:text-white flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] text-slate-400">calendar_month</span>
                                {{ $license->academicYear->name ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Status Izin</p>
                            @php
                                $statusClass = match($license->status) {
                                    'approved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    default    => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                };
                                $statusText = match($license->status) {
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default    => 'Menunggu',
                                };
                                $statusIcon = match($license->status) {
                                    'approved' => 'check_circle',
                                    'rejected' => 'cancel',
                                    default    => 'pending',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $statusClass }}">
                                <span class="material-symbols-outlined text-[14px]">{{ $statusIcon }}</span>
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Kategori</p>
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $license->leaveCategory->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Alasan</p>
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $license->leaveReason->reason ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Mulai Izin</p>
                            <p class="font-semibold text-slate-800 dark:text-white flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] text-primary">calendar_today</span>
                                {{ $license->start_date->locale('id')->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Batas Waktu Izin</p>
                            <p class="font-semibold text-slate-800 dark:text-white flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] text-primary">event</span>
                                {{ $license->end_date->locale('id')->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>

                    @if($license->actual_return_date)
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Waktu Tiba (Aktual)</p>
                            <p class="font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] text-emerald-500">how_to_reg</span>
                                {{ Carbon\Carbon::parse($license->actual_return_date)->locale('id')->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Status Kedatangan</p>
                            @if($isLateReturn)
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">
                                    Telat {{ $lateDaysReturn }} Hari
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    Tepat Waktu
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

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
                            <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $license->description }}</p>
                        </div>
                    @endif

                    {{-- Timeline --}}
                    <div>
                        <p class="text-xs text-slate-500 mb-3">Timeline</p>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 flex-shrink-0">
                                    <span class="material-symbols-outlined text-[16px]">send</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Izin Diajukan</p>
                                    <p class="text-xs text-slate-500">{{ $license->submitted_at?->locale('id')->translatedFormat('d F Y, H:i') ?? '-' }}</p>
                                </div>
                            </div>
                            @if($license->approved_at)
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 flex-shrink-0">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                            Izin Disetujui
                                            @if($license->is_emergency)
                                                <span class="ml-1 text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">Darurat</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-500">{{ $license->approved_at->locale('id')->translatedFormat('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                            @if($license->rejected_at)
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 flex-shrink-0">
                                        <span class="material-symbols-outlined text-[16px]">cancel</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Izin Ditolak</p>
                                        <p class="text-xs text-slate-500">{{ $license->rejected_at->locale('id')->translatedFormat('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- ===== RIWAYAT PERPANJANGAN ===== --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-50 text-violet-500 border border-violet-100 dark:bg-violet-900/30 dark:text-violet-400 dark:border-violet-800">
                            <span class="material-symbols-outlined text-[20px]">more_time</span>
                        </div>
                        <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Riwayat Perpanjangan</h3>
                    <p class="text-xs text-slate-400">{{ $extensions->count() }} kali perpanjangan</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                @if($isPendingExtension)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                        Ada Pending
                    </span>
                @endif
                
                <button type="button" @click="showExtensionModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md transition-all shrink-0">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Tambah Perpanjangan
                </button>
            </div>
        </div>

        {{-- Form tambah via telepon (collapsible) --}}


        {{-- Alert perpanjangan pending --}}
        @if($isPendingExtension)
            <div class="mx-6 mt-4 rounded-xl border border-amber-200 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-700 px-4 py-3">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-600 text-[20px] shrink-0">warning</span>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-amber-800 dark:text-amber-200">Perpanjangan Menunggu Persetujuan</p>
                        <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">
                            {{ $activeExt->source === 'guardian' ? 'Wali santri' : 'Pengurus' }} mengajukan perpanjangan
                            hingga <strong>{{ $activeExt->requested_new_end_date->locale('id')->translatedFormat('d F Y') }}</strong>.
                            Santri boleh tetap di rumah sambil menunggu keputusan.
                        </p>
                        @if($activeExt->notes)
                            <p class="text-xs text-amber-600 mt-1">Catatan: {{ $activeExt->notes }}</p>
                        @endif
                        @if($activeExt->attachment)
                            <a href="{{ Storage::url($activeExt->attachment) }}" data-fslightbox="bukti_pending_{{ $activeExt->id }}"
                                class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-amber-700 hover:underline">
                                <span class="material-symbols-outlined text-[14px]">attach_file</span>
                                Lihat Bukti
                            </a>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2 shrink-0">
                        {{-- Setujui --}}
                        <button type="button" @click="showApproveExtModal = true"
                            class="w-full inline-flex items-center justify-center gap-1 px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white text-xs font-bold transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[14px]">check_circle</span>
                            Setujui
                        </button>
                        {{-- Tolak --}}
                        <button type="button" @click="showRejectExtModal = true"
                            class="w-full inline-flex items-center justify-center gap-1 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[14px]">cancel</span>
                            Tolak
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tabel riwayat --}}
        @if($extensions->isNotEmpty())
            <div class="px-6 py-4">
                <div class="space-y-3">
                    @foreach($extensions as $idx => $ext)
                        <div class="flex items-start gap-4 rounded-xl border border-[#e7edf3] dark:border-slate-700 px-4 py-3
                            {{ $ext->status === 'approved' ? 'bg-green-50/50 dark:bg-green-900/10' : ($ext->status === 'rejected' ? 'bg-red-50/50 dark:bg-red-900/10' : 'bg-amber-50/50 dark:bg-amber-900/10') }}">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-black text-white
                                {{ $ext->status === 'approved' ? 'bg-green-500' : ($ext->status === 'rejected' ? 'bg-red-500' : 'bg-amber-500') }}">
                                {{ $idx + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-0.5">
                                    <span class="text-sm font-bold text-slate-800 dark:text-white">
                                        → {{ $ext->requested_new_end_date->locale('id')->translatedFormat('d F Y') }}
                                    </span>
                                    @if($ext->status === 'pending')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Menunggu</span>
                                    @elseif($ext->status === 'approved')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Disetujui</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Ditolak</span>
                                    @endif
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $ext->source === 'guardian' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $ext->source === 'guardian' ? 'Via Wali' : 'Via Telepon/Pengurus' }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400">Diajukan {{ $ext->requested_at->locale('id')->translatedFormat('d M Y, H:i') }}</p>
                                @if($ext->notes)
                                    <p class="text-xs text-slate-500 mt-1">Catatan wali: {{ $ext->notes }}</p>
                                @endif
                                @if($ext->admin_notes)
                                    <p class="text-xs text-slate-500 mt-0.5 italic">Catatan pengurus: {{ $ext->admin_notes }}</p>
                                @endif
                            </div>
                            @if($ext->attachment)
                                <a href="{{ Storage::url($ext->attachment) }}" data-fslightbox="bukti_ext_{{ $ext->id }}"
                                    class="flex items-center gap-1 text-xs text-primary font-semibold hover:underline shrink-0">
                                    <span class="material-symbols-outlined text-[15px]">attach_file</span>
                                    Bukti
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="px-6 py-8 text-center">
                <span class="material-symbols-outlined text-3xl text-slate-200 dark:text-slate-600 block mb-2">more_time</span>
                <p class="text-sm text-slate-400">Belum ada riwayat perpanjangan</p>
            </div>
        @endif

    </div>
    </div>
    </div>

        {{-- Modal: Setujui --}}

        <div x-show="showApproveModal" class="relative z-50" style="display:none">
            <div class="fixed inset-0 bg-black/50" x-transition></div>
            <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                <div x-show="showApproveModal" x-transition
                    class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600">
                            <span class="material-symbols-outlined">check_circle</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Setujui Izin</h3>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                        Setujui izin kepulangan <strong class="text-slate-800 dark:text-white">{{ $license->student->name }}</strong>?
                    </p>
                    <div class="flex gap-3">
                        <button type="button" @click="showApproveModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </button>
                        <form action="{{ route('admin.licenses.approve', $license->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="override_validation" x-bind:value="isOverride ? '1' : '0'">
                            <input type="hidden" name="override_reason" x-bind:value="overrideReason">
                            <button type="submit"
                                class="w-full px-4 py-2.5 rounded-xl bg-primary text-white font-bold hover:bg-primary/90 transition-colors">
                                Ya, Setujui
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal: Setujui Darurat (bypass) --}}
        <div x-show="showForceModal" class="relative z-50" style="display:none">
            <div class="fixed inset-0 bg-black/50" x-transition></div>
            <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                <div x-show="showForceModal" x-transition
                    class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                            <span class="material-symbols-outlined">priority_high</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Setujui sebagai Darurat</h3>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">
                        Validasi tidak sepenuhnya lolos. Setujui izin <strong class="text-slate-800 dark:text-white">{{ $license->student->name }}</strong> sebagai kasus darurat?
                    </p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 rounded-lg px-3 py-2 mb-6">
                        Izin akan ditandai sebagai darurat dan disetujui tanpa memenuhi semua syarat validasi.
                    </p>
                    <div class="flex gap-3">
                        <button type="button" @click="showForceModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </button>
                        <form action="{{ route('admin.licenses.force-approve', $license->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2.5 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-600 transition-colors">
                                Tetap Setujui
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal: Tolak --}}
        <div x-show="showRejectModal" class="relative z-50" style="display:none">
            <div class="fixed inset-0 bg-black/50" x-transition></div>
            <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                <div x-show="showRejectModal" x-transition
                    class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <span class="material-symbols-outlined">cancel</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Tolak Izin</h3>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                        Tolak izin kepulangan <strong class="text-slate-800 dark:text-white">{{ $license->student->name }}</strong>?
                    </p>
                    <div class="flex gap-3">
                        <button type="button" @click="showRejectModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </button>
                        <form action="{{ route('admin.licenses.reject', $license->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2.5 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors">
                                Ya, Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal: Tambah Perpanjangan --}}
        <div x-show="showExtensionModal" class="relative z-50" style="display:none">
            <div class="fixed inset-0 bg-black/50" x-transition></div>
            <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                <div x-show="showExtensionModal" x-transition @click.away="showExtensionModal = false"
                    class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-xl overflow-hidden">
                    
                    <div class="flex items-center justify-between px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 bg-[#f8fafc] dark:bg-slate-800/50">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                                <span class="material-symbols-outlined">more_time</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Tambah Perpanjangan</h3>
                        </div>
                        <button type="button" @click="showExtensionModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        @if($license->status !== 'approved')
                            <div class="rounded-xl border border-red-200 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                                <span class="material-symbols-outlined text-[16px] inline-block align-text-bottom mr-1">info</span>
                                Perpanjangan tidak dapat diajukan karena izin ini belum disetujui atau telah ditolak.
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="button" @click="showExtensionModal = false"
                                    class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors">Tutup</button>
                            </div>
                        @elseif($license->actual_return_date)
                            <div class="rounded-xl border border-red-200 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                                <span class="material-symbols-outlined text-[16px] inline-block align-text-bottom mr-1">info</span>
                                Perpanjangan tidak dapat diajukan karena santri sudah kembali ke pondok.
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="button" @click="showExtensionModal = false"
                                    class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors">Tutup</button>
                            </div>
                        @elseif($isPendingExtension)
                            <div class="rounded-xl border border-amber-200 bg-amber-50 dark:bg-amber-900/20 px-4 py-3 text-sm text-amber-700 dark:text-amber-300">
                                <span class="material-symbols-outlined text-[16px] inline-block align-text-bottom mr-1">warning</span>
                                Masih ada pengajuan perpanjangan yang sedang menunggu persetujuan. Harap proses terlebih dahulu.
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="button" @click="showExtensionModal = false"
                                    class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors">Tutup</button>
                            </div>
                        @else
                            <form action="{{ route('admin.licenses.extend-phone', $license) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">
                                            Tanggal Kembali Baru <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="requested_new_end_date" required
                                            min="{{ $license->end_date->copy()->addDay()->format('Y-m-d') }}"
                                            max="{{ $license->end_date->copy()->addDays(3)->format('Y-m-d') }}"
                                            class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-800 dark:text-white focus:outline-none focus:border-primary">
                                        <p class="text-[10px] text-slate-400">Maks. {{ $license->end_date->copy()->addDays(3)->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Upload Bukti <span class="text-slate-400 font-normal">(opsional)</span></label>
                                        <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf"
                                            class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Catatan</label>
                                    <textarea name="notes" rows="3" placeholder="Alasan perpanjangan..."
                                        class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-800 dark:text-white focus:outline-none focus:border-primary resize-none"></textarea>
                                </div>
                                <div class="flex items-center gap-4 mt-6">
                                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer flex-1">
                                        <input type="checkbox" name="auto_approve" value="1"
                                            class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary">
                                        Langsung Setujui
                                    </label>
                                    <button type="button" @click="showExtensionModal = false"
                                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-sm transition-colors">
                                        Simpan Perpanjangan
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal: Setujui Perpanjangan --}}
        @if($isPendingExtension)
        <div x-show="showApproveExtModal" class="relative z-50" style="display:none">
            <div class="fixed inset-0 bg-black/50" x-transition></div>
            <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                <div x-show="showApproveExtModal" x-transition @click.away="showApproveExtModal = false"
                    class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl overflow-hidden">
                    
                    <div class="flex items-center justify-between px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 bg-[#f8fafc] dark:bg-slate-800/50">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-600 border border-green-100">
                                <span class="material-symbols-outlined">check_circle</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Setujui Perpanjangan</h3>
                        </div>
                        <button type="button" @click="showApproveExtModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <form action="{{ route('admin.extensions.approve', $activeExt->id) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Kembali Baru <span class="text-red-500">*</span></label>
                                <input type="date" name="new_end_date" required
                                    value="{{ $activeExt->requested_new_end_date->format('Y-m-d') }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-800 dark:text-white focus:outline-none focus:border-primary">
                                <p class="text-xs text-slate-500">Anda dapat merubah tanggal yang diajukan oleh wali ({{ $activeExt->requested_new_end_date->locale('id')->translatedFormat('d F Y') }}).</p>
                            </div>
                            
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Catatan Pengurus (Opsional)</label>
                                <textarea name="admin_notes" rows="2" placeholder="Pesan untuk wali..."
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-800 dark:text-white focus:outline-none focus:border-primary resize-none"></textarea>
                            </div>
                            
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" @click="showApproveExtModal = false"
                                    class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 font-bold hover:bg-slate-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white font-bold transition-colors shadow-sm">
                                    Setujui Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal: Tolak Perpanjangan --}}
        @if($isPendingExtension)
        <div x-show="showRejectExtModal" class="relative z-50" style="display:none">
            <div class="fixed inset-0 bg-black/50" x-transition></div>
            <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                <div x-show="showRejectExtModal" x-transition @click.away="showRejectExtModal = false"
                    class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl overflow-hidden">
                    
                    <div class="flex items-center justify-between px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 bg-[#f8fafc] dark:bg-slate-800/50">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600 border border-red-100">
                                <span class="material-symbols-outlined">cancel</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Tolak Perpanjangan</h3>
                        </div>
                        <button type="button" @click="showRejectExtModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                            Anda yakin ingin menolak perpanjangan yang diajukan oleh wali untuk santri <strong class="text-slate-800 dark:text-white">{{ $license->student->name }}</strong>?
                        </p>
                        <form action="{{ route('admin.extensions.reject', $activeExt->id) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Catatan Penolakan (Opsional)</label>
                                <textarea name="admin_notes" rows="2" placeholder="Berikan alasan penolakan..."
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-800 dark:text-white focus:outline-none focus:border-primary resize-none"></textarea>
                            </div>
                            
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" @click="showRejectExtModal = false"
                                    class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 font-bold hover:bg-slate-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold transition-colors shadow-sm">
                                    Ya, Tolak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif

    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fslightbox/index.min.js"></script>
<script>
    (function() {
        let initialized = false;
        window.initAlpineApproval = function() {
            if (initialized) return;
            if (!window.Alpine) return;
            initialized = true;
            Alpine.data('licenseApproval', () => ({
                showApproveModal: false,
                showRejectModal: false,
                showForceModal: false,
                showReturnModal: false,
                showExtensionModal: false,
                showApproveExtModal: false,
                showRejectExtModal: false,
                isOverride: false,
                overrideReason: '',
                allPass: {{ $allPass ? 'true' : 'false' }},
                canApprove() {
                    if (this.allPass) return true;
                    if (!this.isOverride) return false;
                    return typeof this.overrideReason === 'string' && this.overrideReason.trim() !== '';
                }
            }));
        };

        if (window.Alpine) {
            window.initAlpineApproval();
        } else {
            document.addEventListener('alpine:init', window.initAlpineApproval);
        }
    })();
</script>
@endpush
