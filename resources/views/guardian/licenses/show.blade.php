@extends('layouts.guardian')

@section('title', 'Detail Perizinan')
@section('mobile_title', 'Detail Perizinan')

@section('content')

    <div class="rounded-2xl p-5 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex items-center gap-4">
            <a href="{{ route('guardian.licenses.index') }}" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#4c739a] shadow-sm hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl font-black text-[#0d141b] dark:text-white mb-0.5">Detail Perizinan</h1>
                <p class="text-sm text-[#4c739a]">Rincian pengajuan izin santri Anda.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Student Info --}}
        <div class="flex flex-col gap-6">
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

                <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-800">
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
                        <span class="material-symbols-outlined text-[18px] text-slate-400">school</span>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Pend. Diniyah</p>
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $license->student->religiousEducation?->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="material-symbols-outlined text-[18px] text-slate-400">school</span>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Pend. Formal</p>
                            <p class="font-semibold text-slate-800 dark:text-white">{{ $license->student->formalEducation?->name ?? '-' }}</p>
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

            @php
                $isLate = \Carbon\Carbon::now()->startOfDay()->gt(\Carbon\Carbon::parse($license->end_date)->startOfDay());
            @endphp
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
                        @if($license->extensions && $license->extensions->where('status', '!=', 'rejected')->count() > 0)
                            @foreach($license->extensions->where('status', '!=', 'rejected')->values() as $index => $ext)
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

        {{-- Right Column: License Details --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800">
                <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-800 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Informasi Izin</h3>
                    @if($license->status === 'pending')
                        <div class="flex gap-2">
                            <a href="{{ route('guardian.licenses.edit', $license->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-semibold bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">edit</span> Edit
                            </a>
                            <form action="{{ route('guardian.licenses.destroy', $license->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan izin ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-semibold bg-red-50 text-red-700 hover:bg-red-100 transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Status Izin</p>
                            @php
                                $statusClass = match($license->status) {
                                    'approved' => 'bg-green-100 text-green-700 border-green-200',
                                    'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                    default    => 'bg-amber-100 text-amber-700 border-amber-200',
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
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                                <span class="material-symbols-outlined text-[14px]">{{ $statusIcon }}</span>
                                {{ $statusText }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Diajukan Pada</p>
                            <p class="font-semibold text-slate-800 dark:text-white">
                                {{ $license->created_at->locale('id')->translatedFormat('d F Y, H:i') }}
                            </p>
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
                            <p class="font-semibold text-emerald-600 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                                {{ Carbon\Carbon::parse($license->actual_return_date)->locale('id')->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Status Kedatangan</p>
                            @php
                                $lateDaysReturn = $license->actual_return_date ? Carbon\Carbon::parse($license->actual_return_date)->startOfDay()->diffInDays($license->end_date->startOfDay(), false) : 0;
                                $isLateReturn = $lateDaysReturn < 0;
                            @endphp
                            @if($isLateReturn)
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                    Telat {{ abs($lateDaysReturn) }} Hari
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                    Tepat Waktu
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($license->description)
                        <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                            <p class="text-xs text-slate-500 mb-1">Keterangan Tambahan</p>
                            <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $license->description }}</p>
                        </div>
                    @endif

                    @if($license->attachment)
                        <div class="pt-2">
                            <p class="text-xs text-slate-500 mb-2">Lampiran / Bukti</p>
                            <a href="{{ Storage::url($license->attachment) }}" data-fslightbox="bukti_{{ $license->id }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-sm font-semibold text-slate-700 transition-colors">
                                <span class="material-symbols-outlined text-[20px] text-primary">image</span>
                                Lihat Bukti
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Riwayat Perpanjangan --}}
            @if($license->extensions->isNotEmpty())
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800">
                <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                            <span class="material-symbols-outlined text-[18px]">update</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white">Riwayat Perpanjangan</h3>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-3">
                        @foreach($license->extensions as $ext)
                            <div class="flex items-start gap-4 rounded-xl border border-[#e7edf3] px-4 py-3
                                {{ $ext->status === 'approved' ? 'bg-green-50/50' : ($ext->status === 'rejected' ? 'bg-red-50/50' : 'bg-amber-50/50') }}">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full font-bold text-white text-xs
                                    {{ $ext->status === 'approved' ? 'bg-green-500' : ($ext->status === 'rejected' ? 'bg-red-500' : 'bg-amber-500') }}">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px] text-slate-400">arrow_right_alt</span>
                                            {{ $ext->requested_new_end_date->locale('id')->translatedFormat('d F Y') }}
                                        </p>
                                        @if($ext->status === 'pending')
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700">Menunggu</span>
                                        @elseif($ext->status === 'approved')
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">Disetujui</span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700">Ditolak</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-400">Diajukan {{ $ext->requested_at->locale('id')->translatedFormat('d M Y, H:i') }}</p>
                                    @if($ext->notes)
                                        <p class="text-xs text-slate-500 mt-1">Catatan: {{ $ext->notes }}</p>
                                    @endif
                                </div>
                                @if($ext->attachment)
                                    <a href="{{ Storage::url($ext->attachment) }}" data-fslightbox="bukti_ext_{{ $ext->id }}"
                                        class="flex items-center gap-1 text-xs text-primary font-semibold hover:underline shrink-0">
                                        <span class="material-symbols-outlined text-[15px]">image</span> Bukti
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fslightbox/index.min.js"></script>
@endpush
