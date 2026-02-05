@extends('layouts.app')

@section('title', 'Kelola Departemen')
@section('breadcrumb', 'Departemen')
@section('mobile_title', 'Departemen')

@section('content')
    <div class="flex flex-col gap-6">
        {{-- Header --}}
        <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
            style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                <div class="flex flex-col gap-1">
                    <h2 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Kelola Departemen</h2>
                    <p class="text-[#4c739a] text-sm sm:text-base font-normal">Manajemen departemen dan jenis pelanggaran di
                        pondok pesantren</p>
                </div>
                <a href="{{ route('departments.create') }}"
                    class="group flex items-center justify-center gap-2 rounded-xl px-5 h-11 bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-lg hover:shadow-xl hover:shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-200">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                    <span>Tambah Departemen</span>
                </a>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div
                class="bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-xl p-5 border border-blue-500/20 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#4c739a] text-xs font-medium uppercase tracking-wide mb-1">Total Departemen</p>
                        <p class="text-3xl font-bold text-[#0d141b] dark:text-white">{{ $departemen->count() }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500/20 text-blue-600">
                        <span class="material-symbols-outlined text-[28px] fill-1">apartment</span>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-xl p-5 border border-purple-500/20 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#4c739a] text-xs font-medium uppercase tracking-wide mb-1">Jenis Pelanggaran</p>
                        <p class="text-3xl font-bold text-[#0d141b] dark:text-white">
                            {{ $departemen->sum('jenis_pelanggaran_count') }}
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500/20 text-purple-600">
                        <span class="material-symbols-outlined text-[28px] fill-1">description</span>
                    </div>
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-green-500/10 to-emerald-500/10 rounded-xl p-5 border border-green-500/20 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#4c739a] text-xs font-medium uppercase tracking-wide mb-1">Rata-rata</p>
                        <p class="text-3xl font-bold text-[#0d141b] dark:text-white">
                            {{ $departemen->count() > 0 ? number_format($departemen->sum('jenis_pelanggaran_count') / $departemen->count(), 1) : 0 }}
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-500/20 text-green-600">
                        <span class="material-symbols-outlined text-[28px] fill-1">analytics</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Department List Table --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
            <div
                class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="font-semibold text-[#0d141b] dark:text-white">Daftar Departemen</h3>
                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-[#4c739a]">
                    {{ $departemen->count() }} Data
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-[#e7edf3] dark:border-slate-700 text-xs uppercase tracking-wider text-[#4c739a] bg-slate-50/50 dark:bg-slate-800/20">
                            <th class="px-6 py-4 font-semibold whitespace-nowrap w-20 text-center">No</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap w-24 text-center">Kode</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap">Nama Departemen</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap">Singkatan</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                        @forelse($departemen as $dept)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                <td class="px-6 py-4 text-sm text-[#4c739a] text-center">
                                    @if(method_exists($departemen, 'firstItem'))
                                        {{ $departemen->firstItem() + $loop->index }}
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                        {{ $dept->kode_departemen }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-semibold text-[#0d141b] dark:text-white">{{ $dept->nama_departemen }}</span>
                                        @if($dept->keterangan)
                                            <span class="text-xs text-[#4c739a] mt-0.5 line-clamp-1">{{ $dept->keterangan }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#4c739a] font-medium">{{ $dept->singkatan }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div
                                        class="flex items-center justify-end gap-2 opacit-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('departments.show', $dept->id) }}"
                                            class="p-2 text-[#4c739a] hover:text-primary transition-colors rounded-lg hover:bg-primary/5"
                                            title="Detail">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </a>
                                        <a href="{{ route('departments.edit', $dept->id) }}"
                                            class="p-2 text-[#4c739a] hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20"
                                            title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="{{ route('departments.destroy', $dept->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus departemen {{ $dept->nama_departemen }}?')"
                                                type="button"
                                                class="p-2 text-[#4c739a] hover:text-red-600 transition-colors rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"
                                                title="Hapus">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-[#4c739a]">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                            <span class="material-symbols-outlined text-3xl opacity-50">apartment</span>
                                        </div>
                                        <p class="font-medium text-lg text-[#0d141b] dark:text-white mb-1">Belum ada data
                                            departemen</p>
                                        <p class="text-sm mb-4">Silakan tambahkan departemen baru untuk memulai</p>
                                        <a href="{{ route('departments.create') }}"
                                            class="text-primary hover:underline text-sm font-medium">Tambah Departemen Baru</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700">
                {{ $departemen->links() }}
            </div>
        </div>
    </div>


@endsection