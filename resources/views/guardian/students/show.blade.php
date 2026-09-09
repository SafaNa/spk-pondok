@extends('layouts.guardian')

@section('title', 'Detail Santri - ' . $student->name)
@section('mobile_title', 'Detail Santri')

@section('content')

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('guardian.dashboard') }}"
            class="flex items-center justify-center w-9 h-9 rounded-lg bg-white dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 text-[#4c739a] hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-lg font-black text-[#0d141b] dark:text-white leading-tight">Detail Santri</h1>
            <p class="text-xs text-[#4c739a]">{{ $student->name }}</p>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#e7edf3] dark:border-slate-800 shadow-sm p-5 mb-4">
        <div class="flex items-start gap-4">
            @if($student->photo)
                <img src="{{ asset('storage/' . $student->photo) }}"
                     alt="{{ $student->name }}"
                     class="w-20 h-20 rounded-xl object-cover shrink-0 border-2 border-[#e7edf3] dark:border-slate-700">
            @else
                @php
                    $colors   = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                    $color    = $colors[crc32($student->id) % count($colors)];
                    $nameParts = explode(' ', trim($student->name));
                    $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                @endphp
                <div class="flex w-20 h-20 shrink-0 items-center justify-center rounded-xl bg-{{ $color }}-100 text-{{ $color }}-600 text-2xl font-black border-2 border-[#e7edf3] dark:border-slate-700">
                    {{ $initials }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h2 class="text-base font-black text-[#0d141b] dark:text-white leading-tight">{{ $student->name }}</h2>
                        <p class="text-sm text-[#4c739a] mt-0.5">{{ $student->identifier_label }}: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->nis ?? '-' }}</span></p>
                        @if($student->nik)
                            <p class="text-xs text-[#4c739a] mt-0.5">NIK: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->nik }}</span></p>
                        @endif
                    </div>
                    <span class="inline-flex shrink-0 items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $student->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $student->status === 'active' ? 'Aktif' : ucfirst($student->status ?? '-') }}
                    </span>
                </div>
                <div class="mt-3 grid grid-cols-2 gap-y-2 gap-x-4 text-xs">
                    <div>
                        <span class="text-[#4c739a]">Rayon</span>
                        <p class="font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $student->rayon?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-[#4c739a]">Kamar</span>
                        <p class="font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $student->room?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-[#4c739a]">Pend. Diniyah</span>
                        <p class="font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $student->religiousEducation?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-[#4c739a]">Pend. Formal</span>
                        <p class="font-semibold text-slate-700 dark:text-slate-300 mt-0.5">{{ $student->formalEducation?->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-[#e7edf3] dark:border-slate-700">
            <a href="{{ route('guardian.students.edit', $student) }}"
                class="flex items-center justify-center gap-1.5 w-full py-2.5 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-sm font-semibold transition-colors">
                <span class="material-symbols-outlined text-[16px]">edit</span>
                Lengkapi / Edit Data
            </a>
        </div>
    </div>

    {{-- Poin Kepulangan Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#e7edf3] dark:border-slate-800 shadow-sm p-5 mb-4">
        <h3 class="text-sm font-bold text-[#0d141b] dark:text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-blue-500">flight_takeoff</span>
            Poin Kepulangan
        </h3>
        @if($activeAcademicYear)
            @php
                $maxLeaves = $activeAcademicYear->max_leaves ?? null;
                $isFull = $maxLeaves && $approvedLeavesCount >= $maxLeaves;
                $countColor = $isFull ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400';
                $cardBg = $isFull ? 'bg-red-50 dark:bg-red-900/20 border-red-100 dark:border-red-800' : 'bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800';
            @endphp
            <div class="flex items-center gap-4">
                <div class="flex-1 {{ $cardBg }} rounded-xl p-4 text-center border">
                    <p class="font-black {{ $countColor }} leading-none">
                        <span class="text-4xl">{{ $approvedLeavesCount }}</span>
                        @if($maxLeaves)
                            <span class="text-xl text-[#4c739a]"> / {{ $maxLeaves }}</span>
                        @endif
                    </p>
                    <p class="text-xs font-semibold {{ $isFull ? 'text-red-500' : 'text-blue-500' }} mt-2">
                        {{ $isFull ? 'Batas Tercapai' : 'Kepulangan Disetujui' }}
                    </p>
                    <p class="text-[11px] text-[#4c739a] mt-0.5">TA {{ $activeAcademicYear->name }}</p>
                </div>
                <div class="flex-1 text-sm text-[#4c739a] space-y-1.5">
                    @if($maxLeaves)
                        @php $sisa = max(0, $maxLeaves - $approvedLeavesCount); @endphp
                        <p class="flex items-center gap-1.5 text-xs">
                            <span class="material-symbols-outlined text-[15px] {{ $isFull ? 'text-red-400' : 'text-blue-400' }}">{{ $isFull ? 'warning' : 'confirmation_number' }}</span>
                            Sisa kepulangan: <strong class="{{ $isFull ? 'text-red-600' : 'text-slate-700 dark:text-slate-300' }}">{{ $sisa }} kali</strong>
                        </p>
                    @else
                        <p class="flex items-center gap-1.5 text-xs">
                            <span class="material-symbols-outlined text-[15px] text-blue-400">info</span>
                            Total kepulangan disetujui tahun ajaran ini.
                        </p>
                    @endif
                    @if($activeAcademicYear->stage1_deadline || $activeAcademicYear->stage2_deadline)
                        <div class="text-[11px] space-y-1 mt-1">
                            @if($activeAcademicYear->stage1_deadline)
                                <p>Batas Tahap 1: <strong class="text-slate-700 dark:text-slate-300">{{ $activeAcademicYear->stage1_deadline->locale('id')->translatedFormat('d F Y') }}</strong></p>
                            @endif
                            @if($activeAcademicYear->stage2_deadline)
                                <p>Batas Tahap 2: <strong class="text-slate-700 dark:text-slate-300">{{ $activeAcademicYear->stage2_deadline->locale('id')->translatedFormat('d F Y') }}</strong></p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="flex items-center gap-2 text-sm text-[#4c739a] bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4">
                <span class="material-symbols-outlined text-[18px]">info</span>
                Tidak ada tahun ajaran aktif. Hubungi admin pondok.
            </div>
        @endif
    </div>

    {{-- Data Pribadi --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#e7edf3] dark:border-slate-800 shadow-sm p-5 mb-4">
        <h3 class="text-sm font-bold text-[#0d141b] dark:text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-purple-500">badge</span>
            Data Pribadi
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-[#4c739a] font-semibold uppercase tracking-wide mb-1">Tempat, Tanggal Lahir</p>
                <p class="text-slate-700 dark:text-slate-300 font-medium">
                    @if($student->birth_place || $student->birth_date)
                        {{ $student->birth_place ?? '-' }}{{ $student->birth_date ? ', ' . $student->birth_date->locale('id')->translatedFormat('d F Y') : '' }}
                    @else
                        -
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs text-[#4c739a] font-semibold uppercase tracking-wide mb-1">No. HP / WA</p>
                <p class="text-slate-700 dark:text-slate-300 font-medium">{{ $student->phone ?? '-' }}</p>
            </div>
            <div class="sm:col-span-2">
                <p class="text-xs text-[#4c739a] font-semibold uppercase tracking-wide mb-1">Alamat</p>
                <p class="text-slate-700 dark:text-slate-300 font-medium">
                    @php
                        $addressParts = array_filter([
                            $student->address,
                            $student->village?->name,
                            $student->district?->name ? 'Kec. ' . $student->district->name : null,
                            $student->city?->name,
                            $student->province?->name,
                        ]);
                    @endphp
                    {{ count($addressParts) ? implode(', ', $addressParts) : '-' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Data Orang Tua --}}
    @if($student->father_name || $student->mother_name)
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#e7edf3] dark:border-slate-800 shadow-sm p-5 mb-4">
        <h3 class="text-sm font-bold text-[#0d141b] dark:text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-green-500">family_restroom</span>
            Data Orang Tua
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if($student->father_name)
            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 space-y-2.5">
                <p class="text-xs font-bold text-[#4c739a] uppercase tracking-wide flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[14px]">man</span> Ayah
                </p>
                <div class="space-y-1.5 text-sm">
                    <div>
                        <span class="text-[#4c739a] text-xs">Nama</span>
                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->father_name }}</p>
                    </div>
                    @if($student->father_education)
                    <div>
                        <span class="text-[#4c739a] text-xs">Pendidikan</span>
                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->father_education }}</p>
                    </div>
                    @endif
                    @if($student->father_occupation)
                    <div>
                        <span class="text-[#4c739a] text-xs">Pekerjaan</span>
                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->father_occupation }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            @if($student->mother_name)
            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 space-y-2.5">
                <p class="text-xs font-bold text-[#4c739a] uppercase tracking-wide flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[14px]">woman</span> Ibu
                </p>
                <div class="space-y-1.5 text-sm">
                    <div>
                        <span class="text-[#4c739a] text-xs">Nama</span>
                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->mother_name }}</p>
                    </div>
                    @if($student->mother_education)
                    <div>
                        <span class="text-[#4c739a] text-xs">Pendidikan</span>
                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->mother_education }}</p>
                    </div>
                    @endif
                    @if($student->mother_occupation)
                    <div>
                        <span class="text-[#4c739a] text-xs">Pekerjaan</span>
                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->mother_occupation }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Riwayat Kepulangan --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#e7edf3] dark:border-slate-800 shadow-sm overflow-hidden mb-4">
        <div class="px-5 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-blue-500">flight_takeoff</span>
            <h3 class="text-sm font-bold text-[#0d141b] dark:text-white">Riwayat Kepulangan</h3>
            <span class="ml-auto text-xs font-semibold text-[#4c739a] bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-full">{{ $student->licenses->count() }}</span>
        </div>
        @if($student->licenses->isEmpty())
            <div class="px-5 py-8 text-center text-sm text-[#4c739a] italic">Belum ada riwayat kepulangan</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-[#4c739a] font-semibold uppercase text-xs tracking-wider border-b border-[#e7edf3] dark:border-slate-700">
                            <th class="px-4 py-3">Tahun Ajaran</th>
                            <th class="px-4 py-3">Tanggal Izin</th>
                            <th class="px-4 py-3">Kembali</th>
                            <th class="px-4 py-3">Alasan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                        @foreach($student->licenses->sortByDesc('start_date') as $license)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($license->academicYear)
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">{{ $license->academicYear->name }}</span>
                                    @else
                                        <span class="text-[#4c739a]">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                    {{ $license->start_date ? $license->start_date->locale('id')->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-[#4c739a] whitespace-nowrap">
                                    {{ $license->end_date ? $license->end_date->locale('id')->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-[#4c739a] max-w-[140px] truncate">{{ $license->leaveReason?->reason ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($license->status == 'approved')
                                        <span class="inline-flex px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-700">Disetujui</span>
                                    @elseif($license->status == 'rejected')
                                        <span class="inline-flex px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-700">Ditolak</span>
                                    @elseif(in_array($license->status, ['pending', 'pending_extension']))
                                        <span class="inline-flex px-2 py-1 rounded text-xs font-semibold bg-amber-100 text-amber-700">Menunggu</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 rounded text-xs font-semibold bg-slate-100 text-slate-700">{{ ucfirst($license->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('guardian.licenses.show', $license) }}"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-semibold transition-colors">
                                        <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Riwayat Pelanggaran --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-[#e7edf3] dark:border-slate-800 shadow-sm overflow-hidden mb-4">
        <div class="px-5 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-red-500">gavel</span>
            <h3 class="text-sm font-bold text-[#0d141b] dark:text-white">Riwayat Pelanggaran</h3>
            <span class="ml-auto text-xs font-semibold text-[#4c739a] bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-full">{{ $student->violationRecords->count() }}</span>
        </div>
        @if($student->violationRecords->isEmpty())
            <div class="px-5 py-8 text-center text-sm text-green-600 flex flex-col items-center gap-2">
                <span class="material-symbols-outlined text-[32px]">verified</span>
                Bersih dari pelanggaran
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-[#4c739a] font-semibold uppercase text-xs tracking-wider border-b border-[#e7edf3] dark:border-slate-700">
                            <th class="px-4 py-3">Jenis Pelanggaran</th>
                            <th class="px-4 py-3">Tingkat</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Status Sanksi</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                        @foreach($student->violationRecords->sortByDesc('violation_date') as $violation)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300 font-medium max-w-[160px] truncate">
                                    {{ $violation->violationType?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @php $level = $violation->violationType?->category?->level ?? ''; @endphp
                                    @if($level === 'berat')
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700">Berat</span>
                                    @elseif($level === 'sedang')
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-700">Sedang</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700">{{ ucfirst($level ?: 'Ringan') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-[#4c739a] whitespace-nowrap">
                                    {{ $violation->violation_date ? \Carbon\Carbon::parse($violation->violation_date)->locale('id')->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($violation->sanction_status === 'completed')
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-700">Selesai</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-700">Belum Selesai</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('guardian.violations.show', $violation) }}"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold transition-colors">
                                        <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
