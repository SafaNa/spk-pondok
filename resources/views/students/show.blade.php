@extends('layouts.app')

@section('title', 'Detail Santri')
@section('breadcrumb', 'Detail')
@section('breadcrumb_parent', 'Santri')
@section('breadcrumb_parent_route', 'admin.students.index')
@section('mobile_title', 'Detail Santri')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('admin.students.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Data Santri</span>
        </a>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">

            {{-- Header --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-t-3xl px-6 py-6 sm:px-8 sm:py-8 border-b border-blue-200 dark:border-blue-800">
                <div class="flex flex-col sm:flex-row items-start justify-between gap-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                        {{-- Foto / Avatar --}}
                        @php
                            $initials = strtoupper(substr($student->name, 0, 1) . (str_contains($student->name, ' ') ? substr($student->name, strpos($student->name, ' ') + 1, 1) : substr($student->name, 1, 1)));
                        @endphp
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}"
                                class="h-20 w-20 rounded-2xl object-cover ring-4 ring-white dark:ring-slate-700 shadow-md">
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-primary/10 text-primary text-2xl font-bold shadow-sm border border-primary/20">
                                {{ $initials }}
                            </div>
                        @endif
                        <div>
                            <h1 class="text-2xl font-bold mb-1 text-slate-900 dark:text-white tracking-tight">{{ $student->name }}</h1>
                            <p class="text-slate-500 text-sm">{{ $student->identifier_label ?? 'NIS' }}: {{ $student->nis }}</p>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                @if($student->status == 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Aktif</span>
                                @elseif($student->status == 'inactive')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Nonaktif</span>
                                @elseif($student->status == 'graduated')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Lulus</span>
                                @elseif($student->status == 'dropped_out')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">Keluar</span>
                                @endif
                                
                                @if(isset($activeAcademicYear))
                                    @if($activeAcademicYear->max_leaves)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $approved_leaves_count >= $activeAcademicYear->max_leaves ? 'bg-red-100 text-red-700 border-red-200' : 'bg-slate-100 text-slate-700 border-slate-200' }} border shadow-sm">
                                            <span class="material-symbols-outlined text-[14px]">confirmation_number</span>
                                            Poin Kepulangan: {{ $approved_leaves_count }}/{{ $activeAcademicYear->max_leaves }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 shadow-sm">
                                            <span class="material-symbols-outlined text-[14px]">confirmation_number</span>
                                            Poin Kepulangan: {{ $approved_leaves_count }} kali
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->isAdmin())
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.students.edit', $student->id) }}"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                            Ubah
                        </a>
                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus santri {{ $student->name }}?')" type="button"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                Hapus
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6 sm:p-10 flex flex-col gap-8">

                {{-- SECTION: Data Identitas --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">person</span>
                        Data Identitas
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">id_card</span> {{ $student->identifier_label ?? 'NIS' }}
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->nis }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">badge</span> NIK
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->nik ?? '-' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">{{ $student->gender == 'male' ? 'male' : 'female' }}</span> Jenis Kelamin
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">cake</span> Tempat, Tanggal Lahir
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">
                                {{ $student->birth_place }}, {{ $student->birth_date ? $student->birth_date->isoFormat('D MMMM Y') : '-' }}
                            </div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">phone_iphone</span> No. HP / Kontak
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->phone ?? '-' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">login</span> Tanggal Masuk
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">
                                {{ $student->entry_date ? $student->entry_date->isoFormat('D MMMM Y') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                {{-- SECTION: Tempat Tinggal --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">home</span>
                        Tempat Tinggal
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">domain</span> Rayon
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->rayon?->name ?? '-' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">meeting_room</span> Kamar
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->room?->name ?? '-' }}</div>
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">location_on</span> Alamat
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">
                                {{ $student->address ?? '-' }}
                                @if($student->village && $student->district && $student->city && $student->province)
                                    <br><span class="text-sm text-slate-500">
                                        {{ $student->village->name }}, {{ $student->district->name }}, {{ $student->city->name }}, {{ $student->province->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                {{-- SECTION: Pendidikan --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">school</span>
                        Pendidikan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">menu_book</span> Pendidikan Formal
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->formalEducation?->name ?? '-' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">auto_stories</span> Pendidikan Diniyah
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->religiousEducation?->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                {{-- SECTION: Data Orang Tua --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">supervisor_account</span>
                        Data Orang Tua
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Ayah --}}
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 space-y-3">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Ayah</p>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Nama</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->father_name ?? '-' }}</div>
                            </div>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Pendidikan Terakhir</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->father_education ?? '-' }}</div>
                            </div>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Pekerjaan</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->father_occupation ?? '-' }}</div>
                            </div>
                        </div>

                        {{-- Ibu --}}
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 space-y-3">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Ibu</p>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Nama</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->mother_name ?? '-' }}</div>
                            </div>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Pendidikan Terakhir</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->mother_education ?? '-' }}</div>
                            </div>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Pekerjaan</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->mother_occupation ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        {{-- Wali --}}
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 space-y-3">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Data Wali</p>
                            @forelse($student->guardians as $guardian)
                                <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-3 mb-3 bg-white dark:bg-slate-800">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-slate-900 dark:text-white font-bold text-sm">{{ $guardian->name }}</div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div class="space-y-0.5">
                                            <span class="text-slate-500 block">Username</span>
                                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $guardian->username }}</span>
                                        </div>
                                        <div class="space-y-0.5">
                                            <span class="text-slate-500 block">No. HP / WA</span>
                                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $guardian->phone ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-slate-500 text-xs italic">Belum ada data wali</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                {{-- SECTION: Sistem --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">info</span>
                        Informasi Sistem
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">calendar_add_on</span> Tanggal Didaftarkan
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->created_at->isoFormat('D MMMM Y, H:mm') }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">update</span> Terakhir Diperbarui
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->updated_at->isoFormat('D MMMM Y, H:mm') }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Riwayat Card --}}
        <div x-data="{ activeTab: 'kepulangan' }" class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            <div class="flex border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 overflow-x-auto hide-scrollbar">
                <button @click="activeTab = 'kepulangan'" :class="{ 'border-b-2 border-primary text-primary font-bold bg-white dark:bg-slate-800': activeTab === 'kepulangan', 'text-slate-500 hover:text-slate-700 font-medium': activeTab !== 'kepulangan' }" class="px-6 py-4 text-sm outline-none transition-colors whitespace-nowrap">
                    Riwayat Kepulangan
                </button>
                <button @click="activeTab = 'pelanggaran'" :class="{ 'border-b-2 border-primary text-primary font-bold bg-white dark:bg-slate-800': activeTab === 'pelanggaran', 'text-slate-500 hover:text-slate-700 font-medium': activeTab !== 'pelanggaran' }" class="px-6 py-4 text-sm outline-none transition-colors whitespace-nowrap">
                    Riwayat Pelanggaran
                </button>
            </div>
            
            <div class="p-6 sm:p-10">
                {{-- Kepulangan Tab --}}
                <div x-show="activeTab === 'kepulangan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Riwayat Kepulangan</h3>
                    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 font-semibold uppercase text-xs tracking-wider border-b border-slate-200 dark:border-slate-700">
                                    <th class="px-4 py-3">Tanggal Izin</th>
                                    <th class="px-4 py-3">Tanggal Harus Kembali</th>
                                    <th class="px-4 py-3">Kategori</th>
                                    <th class="px-4 py-3">Alasan</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                @forelse($student->licenses as $license)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-200 whitespace-nowrap">{{ $license->start_date ? $license->start_date->isoFormat('D MMM Y') : '-' }}</td>
                                        <td class="px-4 py-3 text-slate-600 dark:text-slate-400 whitespace-nowrap">{{ $license->end_date ? $license->end_date->isoFormat('D MMM Y') : '-' }}</td>
                                        <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $license->leaveCategory?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $license->leaveReason?->reason ?? '-' }}</td>
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500 italic">Belum ada riwayat kepulangan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pelanggaran Tab --}}
                <div x-show="activeTab === 'pelanggaran'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">Riwayat Pelanggaran</h3>
                    </div>
                    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 font-semibold uppercase text-xs tracking-wider border-b border-slate-200 dark:border-slate-700">
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Kategori</th>
                                    <th class="px-4 py-3">Jenis Pelanggaran</th>
                                    <th class="px-4 py-3 text-center">Poin</th>
                                    <th class="px-4 py-3">Status Sanksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                @forelse($student->violationRecords as $violation)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-200 whitespace-nowrap">{{ $violation->date ? \Carbon\Carbon::parse($violation->date)->isoFormat('D MMM Y') : '-' }}</td>
                                        <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $violation->violationType?->category?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $violation->violationType?->name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @php $pts = $violation->violationType?->category?->points ?? 0; @endphp
                                            <span class="inline-flex items-center justify-center min-w-[24px] h-6 rounded-full font-bold text-xs {{ $pts > 0 ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600' }}">{{ $pts }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($violation->sanction_status == 'completed')
                                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">
                                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded">
                                                    <span class="material-symbols-outlined text-[14px]">pending</span> Menunggu
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-slate-500 italic">Belum ada riwayat pelanggaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
