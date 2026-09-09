@extends('layouts.app')

@section('title', 'Santri Sedang Izin')
@section('breadcrumb', 'Santri Izin (Aktif)')
@section('breadcrumb_parent', 'Perizinan')
@section('breadcrumb_parent_route', 'admin.licenses.index')

@section('content')
<div class="space-y-4">
    {{-- Header --}}
    <div class="rounded-2xl border border-amber-100 px-6 py-5" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 55%, #fde68a 100%);">
        <div class="flex flex-wrap items-center gap-4">
            <div class="min-w-0 shrink-0">
                <h1 class="text-lg font-black text-amber-900">Santri Sedang Izin (Belum Kembali)</h1>
                <p class="text-sm text-amber-800 mt-1">Daftar santri yang masa izinnya sedang aktif atau sudah lewat masa berlaku (telat).</p>
            </div>
            
            <div class="flex flex-wrap gap-3 ml-auto">
                <div class="flex items-center gap-3 rounded-xl border border-white bg-white/60 px-4 py-3 shadow-sm min-w-[130px]">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <span class="material-symbols-outlined text-[22px]">hourglass_empty</span>
                    </div>
                    <div>
                        <p class="text-xl font-black text-amber-900 leading-none">{{ $licenses->total() }}</p>
                        <p class="text-xs font-semibold text-amber-800 mt-0.5">Total Santri</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.licenses.active') }}"
        class="rounded-xl border border-[#e7edf3] bg-white px-5 py-4 shadow-sm space-y-3">

        {{-- Baris 1: Search + Rayon + Kamar --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="mb-1 block text-xs font-semibold text-[#4c739a]">Cari Santri</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama / NIS..."
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-3 text-sm focus:border-primary focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/10 transition-all">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-[#4c739a]">Rayon</label>
                <select name="rayon_id" id="filter-rayon" onchange="filterKamar()"
                    class="w-full rounded-lg border border-slate-200 bg-white pl-3 pr-8 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10 transition-all">
                    <option value="">Semua Rayon</option>
                    @foreach($rayonList as $rayon)
                        <option value="{{ $rayon->id }}" {{ request('rayon_id') == $rayon->id ? 'selected' : '' }}>{{ $rayon->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-[#4c739a]">Kamar</label>
                <select name="room_id" id="filter-room"
                    class="w-full rounded-lg border border-slate-200 bg-white pl-3 pr-8 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10 transition-all">
                    <option value="">Semua Kamar</option>
                    @foreach($roomList as $room)
                        <option value="{{ $room->id }}" data-rayon="{{ $room->rayon_id }}"
                            {{ request('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Baris 2: Kategori + Alasan + Buttons --}}
        <div class="flex flex-wrap items-end gap-3">
            <div class="min-w-[180px]">
                <label class="mb-1 block text-xs font-semibold text-[#4c739a]">Kategori Alasan</label>
                <select name="leave_category_id" id="filter-kategori" onchange="filterAlasan()"
                    class="w-full rounded-lg border border-slate-200 bg-white pl-3 pr-8 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10 transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ request('leave_category_id') == $kat->id ? 'selected' : '' }}>{{ $kat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="mb-1 block text-xs font-semibold text-[#4c739a]">Alasan</label>
                <select name="leave_reason_id" id="filter-alasan"
                    class="w-full rounded-lg border border-slate-200 bg-white pl-3 pr-8 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/10 transition-all">
                    <option value="">Semua Alasan</option>
                    @foreach($alasanList as $alasan)
                        <option value="{{ $alasan->id }}" data-kategori="{{ $alasan->leave_category_id }}"
                            {{ request('leave_reason_id') == $alasan->id ? 'selected' : '' }}>{{ $alasan->reason }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 shrink-0">
                <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-primary min-h-[42px] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary/90 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">filter_alt</span> Filter
                </button>
                <a href="{{ route('admin.licenses.active') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white min-h-[42px] px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">refresh</span> Reset
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
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide">Santri</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide">Rayon / Kamar</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide">Alasan</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide">Jatuh Tempo</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide text-center">Status</th>
                        <th class="px-4 py-3.5 text-xs font-bold text-white uppercase tracking-wide text-center">Aksi</th>
                    </tr>
                </thead>
                @php
                    $grouped  = $licenses->getCollection()->groupBy(fn($l) => $l->start_date->format('Y-m'));
                @endphp
                <tbody class="divide-y divide-slate-100">
                    @if($grouped->isEmpty())
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <span class="material-symbols-outlined text-5xl text-slate-300 block mb-2">inbox</span>
                                <p class="text-sm font-medium text-[#4c739a]">Semua santri yang izin sudah kembali.</p>
                            </td>
                        </tr>
                    @else
                    @foreach($grouped as $monthKey => $monthLicenses)
                        <tr>
                            <td colspan="6" class="px-4 py-2 bg-slate-50 border-y border-slate-200">
                                <span class="text-xs font-bold text-[#4c739a] uppercase tracking-wider">
                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $monthKey)->locale('id')->translatedFormat('F Y') }}
                                </span>
                            </td>
                        </tr>
                        @foreach($monthLicenses as $license)
                            @php
                                $colors   = ['blue','pink','amber','rose','indigo','green','purple','cyan','orange','teal'];
                                $color    = $colors[crc32($license->student->id) % count($colors)];
                                $initials = strtoupper(substr($license->student->name, 0, 1) . (str_contains($license->student->name, ' ') ? substr($license->student->name, strpos($license->student->name, ' ') + 1, 1) : substr($license->student->name, 1, 1)));
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
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
                                            <div class="font-bold text-[#0d141b] text-sm">{{ $license->student->name }}</div>
                                            <div class="text-[11px] text-[#4c739a]">{{ $license->student->identifier_label ?? 'NIS' }}. {{ $license->student->nis ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="block text-sm text-[#0d141b]">{{ $license->student->rayon->name ?? '-' }}</span>
                                    <span class="text-[11px] text-[#4c739a]">{{ $license->student->room->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="block text-sm text-[#0d141b]">{{ $license->leaveReason->reason ?? ($license->leaveCategory->name ?? '-') }}</span>
                                    @if($license->leaveReason && $license->leaveCategory)
                                        <span class="text-[11px] text-[#4c739a]">{{ $license->leaveCategory->name }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="block text-sm font-semibold {{ now()->startOfDay()->gt($license->end_date) ? 'text-rose-600' : 'text-[#0d141b]' }}">
                                        {{ $license->end_date->locale('id')->translatedFormat('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if(now()->startOfDay()->gt($license->end_date))
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">
                                            Telat {{ now()->startOfDay()->diffInDays($license->end_date) }} hari
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                            Sedang Izin
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.licenses.active.show', $license->id) }}"
                                            class="rounded-lg min-h-[36px] px-3 py-1.5 bg-blue-50 text-blue-600 font-semibold hover:bg-blue-100 transition-colors text-xs flex items-center gap-1">
                                            Detail & Check-in
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        @if($licenses->hasPages())
            <div class="border-t border-[#e7edf3] px-5 py-4">
                {{ $licenses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterKamar() {
    const rayonId = document.getElementById('filter-rayon').value;
    const roomSel = document.getElementById('filter-room');
    Array.from(roomSel.options).forEach(opt => {
        if (!opt.value) return;
        opt.hidden = rayonId !== '' && opt.dataset.rayon !== rayonId;
    });
    if (rayonId && roomSel.value && roomSel.options[roomSel.selectedIndex]?.dataset.rayon !== rayonId) {
        roomSel.value = '';
    }
}
function filterAlasan() {
    const kategoriId = document.getElementById('filter-kategori').value;
    const alasanSel  = document.getElementById('filter-alasan');
    Array.from(alasanSel.options).forEach(opt => {
        if (!opt.value) return;
        opt.hidden = kategoriId !== '' && opt.dataset.kategori !== kategoriId;
    });
    if (kategoriId && alasanSel.value && alasanSel.options[alasanSel.selectedIndex]?.dataset.kategori !== kategoriId) {
        alasanSel.value = '';
    }
}
document.addEventListener('DOMContentLoaded', () => { filterKamar(); filterAlasan(); });
</script>
@endpush
