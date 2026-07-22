@extends('layouts.app')

@section('title', 'Pengajuan Izin')
@section('breadcrumb', 'Pengajuan Izin')

@section('content')
<div class="space-y-4">

    @if(session('success'))
        <div class="flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <span class="material-symbols-outlined text-[18px]">error</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="rounded-2xl border border-blue-100 px-6 py-5" style="background: linear-gradient(135deg, #eff6ff 0%, #eef2ff 55%, #faf5ff 100%);">
        <div class="flex flex-wrap items-center gap-4">

            {{-- Title --}}
            <div class="min-w-0 shrink-0">
                <h1 class="text-lg font-black text-[#0d141b]">Pengajuan Izin Santri</h1>
                <p class="text-sm text-[#4c739a]">Kelola dan pantau seluruh pengajuan izin santri</p>
            </div>

            {{-- KPI Cards --}}
            <div class="flex flex-wrap gap-3 ml-auto">
                <div class="flex items-center gap-3 rounded-xl border border-[#e7edf3] bg-white px-4 py-3 shadow-sm min-w-[130px]">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <span class="material-symbols-outlined text-[22px]">assignment</span>
                    </div>
                    <div>
                        <p class="text-xl font-black text-[#0d141b] leading-none">{{ number_format($totalAll) }}</p>
                        <p class="text-xs font-semibold text-[#0d141b] mt-0.5">Total Pengajuan</p>
                        <p class="text-[10px] text-[#4c739a]">Semua status</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-amber-100 bg-amber-50 px-4 py-3 shadow-sm min-w-[120px]">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <span class="material-symbols-outlined text-[22px]">schedule</span>
                    </div>
                    <div>
                        <p class="text-xl font-black text-amber-900 leading-none">{{ number_format($totalPending) }}</p>
                        <p class="text-xs font-semibold text-amber-800 mt-0.5">Menunggu</p>
                        <p class="text-[10px] text-amber-600">Perlu validasi</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-violet-100 bg-violet-50 px-4 py-3 shadow-sm min-w-[120px]">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                        <span class="material-symbols-outlined text-[22px]">more_time</span>
                    </div>
                    <div>
                        <p class="text-xl font-black text-violet-900 leading-none">{{ number_format($totalPendingExt) }}</p>
                        <p class="text-xs font-semibold text-violet-800 mt-0.5">Perpanjangan</p>
                        <p class="text-[10px] text-violet-600">Menunggu validasi</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 shadow-sm min-w-[120px]">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                        <span class="material-symbols-outlined text-[22px]">check_circle</span>
                    </div>
                    <div>
                        <p class="text-xl font-black text-emerald-900 leading-none">{{ number_format($totalApproved) }}</p>
                        <p class="text-xs font-semibold text-emerald-800 mt-0.5">Disetujui</p>
                        <p class="text-[10px] text-emerald-600">Pengajuan disetujui</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 shadow-sm min-w-[110px]">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                        <span class="material-symbols-outlined text-[22px]">cancel</span>
                    </div>
                    <div>
                        <p class="text-xl font-black text-rose-900 leading-none">{{ number_format($totalRejected) }}</p>
                        <p class="text-xs font-semibold text-rose-800 mt-0.5">Ditolak</p>
                        <p class="text-[10px] text-rose-600">Pengajuan ditolak</p>
                    </div>
                </div>
            </div>

            {{-- Button --}}
            <a href="{{ route('admin.licenses.create') }}"
                class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-bold text-white shadow-md hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Ajukan Izin
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.licenses.index') }}"
        class="rounded-xl border border-[#e7edf3] bg-white px-5 py-4 shadow-sm">
        <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
        <div class="flex flex-wrap items-end gap-3">

            {{-- Search --}}
            <div class="flex-1 min-w-52">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-slate-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari santri, wali, atau no. hp..."
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/10 transition-all">
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="mb-1 block text-xs font-semibold text-[#4c739a]">Status</label>
                <select name="status"
                    class="rounded-lg border border-slate-200 bg-white pl-3 pr-8 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10 transition-all">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Menunggu Izin</option>
                    <option value="pending_extension" {{ request('status') === 'pending_extension' ? 'selected' : '' }}>Menunggu Perpanjangan</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>


            {{-- Tanggal Mulai --}}
            <div>
                <label class="mb-1 block text-xs font-semibold text-[#4c739a]">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10 transition-all">
            </div>

            {{-- Tanggal Selesai --}}
            <div>
                <label class="mb-1 block text-xs font-semibold text-[#4c739a]">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10 transition-all">
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary/90 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                    Filter
                </button>
                <a href="{{ route('admin.licenses.index', ['academic_year_id' => $selectedYearId]) }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">refresh</span>
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-[#e7edf3] bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr style="background-color: #1e3a5f;">
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide">No</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide">Nama Santri</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide whitespace-nowrap">Rayon / Kamar</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide">Tujuan</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide whitespace-nowrap">Tanggal Izin</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide text-center">Status</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f1f5f9]">
                    @forelse($recentLicenses as $license)
                        @php
                            $guardian = optional($license->student->guardians->first());
                            $rowNo    = ($recentLicenses->currentPage() - 1) * $recentLicenses->perPage() + $loop->iteration;
                            $colors   = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                            $color    = $colors[crc32($license->student->id) % count($colors)];
                            $initials = strtoupper(substr($license->student->name, 0, 1) . (str_contains($license->student->name, ' ') ? substr($license->student->name, strpos($license->student->name, ' ') + 1, 1) : substr($license->student->name, 1, 1)));
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">

                            {{-- No --}}
                            <td class="px-4 py-4 text-sm text-[#4c739a] font-medium">{{ $rowNo }}</td>

                            {{-- Nama Santri --}}
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2.5">
                                    @if($license->student->photo)
                                        <img src="{{ asset('storage/' . $license->student->photo) }}"
                                            alt="{{ $license->student->name }}"
                                            class="h-9 w-9 rounded-full object-cover shrink-0">
                                    @else
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 text-xs font-bold">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.licenses.show', $license->id) }}"
                                            class="block font-semibold text-[#0d141b] hover:text-primary transition-colors leading-tight">
                                            {{ $license->student->name }}
                                        </a>
                                        <span class="text-[11px] text-[#4c739a]">{{ $license->student->identifier_label ?? 'NIS' }}. {{ $license->student->nis ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Rayon / Kamar --}}
                            <td class="px-4 py-4">
                                <span class="block text-sm text-[#0d141b]">{{ $license->student->rayon->name ?? '-' }}</span>
                                <span class="text-[11px] text-[#4c739a]">{{ $license->student->room->name ?? '-' }}</span>
                            </td>


                            {{-- Tujuan --}}
                            <td class="px-4 py-4 max-w-[130px]">
                                <span class="block truncate text-sm text-[#4c739a]" title="{{ $license->description }}">
                                    {{ $license->description ?? '-' }}
                                </span>
                            </td>

                            {{-- Tanggal Izin --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="block text-sm text-[#0d141b]">{{ $license->start_date->locale('id')->translatedFormat('d M Y') }}</span>
                                <span class="text-[11px] text-[#4c739a]">s/d {{ $license->end_date->locale('id')->translatedFormat('d M Y') }}</span>
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-4">
                                <div class="flex flex-col items-center gap-1.5">
                                    @php $hasPendingExt = $license->extensions->where('status','pending')->isNotEmpty(); @endphp
                                    
                                    @if($hasPendingExt)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-violet-100 px-3 py-1 text-[11px] font-bold text-violet-700 shadow-sm ring-1 ring-violet-200 animate-pulse">
                                            <span class="material-symbols-outlined text-[14px]">more_time</span> Menunggu Perpanjangan
                                        </span>
                                    @elseif($license->is_emergency && $license->status === 'pending')
                                        <span class="inline-flex items-center rounded-full bg-orange-100 px-3 py-1 text-[11px] font-bold text-orange-700 shadow-sm ring-1 ring-orange-200">Darurat</span>
                                    @elseif($license->status === 'pending')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-[11px] font-bold text-amber-700 shadow-sm ring-1 ring-amber-200 animate-pulse">
                                            <span class="material-symbols-outlined text-[14px]">new_releases</span> Baru
                                        </span>
                                    @elseif($license->status === 'approved')
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-bold text-emerald-700 shadow-sm ring-1 ring-emerald-200">Disetujui</span>
                                    @elseif($license->status === 'rejected')
                                        <span class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1 text-[11px] font-bold text-rose-700 shadow-sm ring-1 ring-rose-200">Ditolak</span>
                                    @endif
                                    @if($license->student->pending_violations_count > 0 && !($license->status === 'approved' && !$hasPendingExt))
                                        <span class="inline-flex items-center gap-0.5 rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-medium text-red-600">
                                            <span class="material-symbols-outlined text-[11px]">warning</span> Pelanggaran
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.licenses.show', $license->id) }}"
                                        class="rounded-lg p-1.5 text-[#4c739a] hover:bg-slate-100 hover:text-primary transition-colors" title="Detail">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.licenses.edit', $license->id) }}"
                                        class="rounded-lg p-1.5 text-[#4c739a] hover:bg-slate-100 hover:text-primary transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form id="form-delete-license-{{ $license->id }}" action="{{ route('admin.licenses.destroy', $license->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" title="Hapus"
                                        @click="$store.deleteModal.open(
                                            document.getElementById('form-delete-license-{{ $license->id }}'),
                                            'Yakin ingin menghapus pengajuan izin {{ addslashes($license->student->name ?? '') }}?'
                                        )"
                                        class="rounded-lg p-1.5 text-rose-500 hover:bg-rose-50 transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-14 text-center">
                                <span class="material-symbols-outlined text-5xl text-slate-300 block mb-2">inbox</span>
                                <p class="text-sm font-medium text-[#4c739a]">Belum ada data pengajuan izin.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($recentLicenses->hasPages())
            <div class="flex items-center justify-between border-t border-[#e7edf3] px-5 py-3.5">
                <p class="text-xs text-[#4c739a]">
                    Menampilkan {{ $recentLicenses->firstItem() }}–{{ $recentLicenses->lastItem() }} dari {{ number_format($recentLicenses->total()) }} pengajuan
                </p>
                {{ $recentLicenses->links() }}
            </div>
        @else
            <div class="border-t border-[#e7edf3] px-5 py-3.5">
                <p class="text-xs text-[#4c739a]">Menampilkan {{ $recentLicenses->count() }} pengajuan</p>
            </div>
        @endif
    </div>
</div>
@endsection
