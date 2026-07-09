@extends('layouts.guardian')

@section('title', 'Riwayat Izin')
@section('mobile_title', 'Riwayat Izin')

@section('content')

    <div class="rounded-2xl p-5 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black text-[#0d141b] dark:text-white mb-0.5">Riwayat Izin</h1>
                <p class="text-sm text-[#4c739a]">Semua pengajuan izin untuk santri Anda.</p>
            </div>
            <a href="{{ route('guardian.licenses.create') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md transition-all">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Ajukan Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase whitespace-nowrap">Santri</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Alasan</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase whitespace-nowrap">Mulai</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase whitespace-nowrap">Kembali</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase whitespace-nowrap">Diajukan</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @forelse($licenses as $license)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-5 py-3 text-sm font-medium text-[#0d141b] dark:text-white whitespace-nowrap">{{ $license->student?->name ?? '-' }}</td>
                            <td class="px-5 py-3 text-sm text-[#0d141b] dark:text-white max-w-xs truncate">{{ $license->description ?? '-' }}</td>
                            <td class="px-5 py-3 text-sm text-[#4c739a] whitespace-nowrap">{{ $license->start_date->format('d M Y') }}</td>
                            <td class="px-5 py-3 text-sm text-[#4c739a] whitespace-nowrap">{{ $license->end_date->format('d M Y') }}</td>
                            <td class="px-5 py-3 text-sm text-[#4c739a] whitespace-nowrap">{{ $license->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($license->status === 'pending')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">Menunggu</span>
                                @elseif($license->status === 'approved')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Disetujui</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <span class="material-symbols-outlined text-4xl text-slate-300 block mb-2">history</span>
                                <p class="text-sm text-[#4c739a]">Belum ada riwayat pengajuan izin.</p>
                                <a href="{{ route('guardian.licenses.create') }}" class="inline-flex items-center gap-1 mt-3 text-sm font-semibold text-primary hover:underline">
                                    <span class="material-symbols-outlined text-[16px]">add</span>
                                    Ajukan sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($licenses->hasPages())
            <div class="px-5 py-4 border-t border-[#e7edf3] dark:border-slate-700">
                {{ $licenses->links() }}
            </div>
        @endif
    </div>

@endsection
