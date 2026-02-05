@extends('layouts.app')

@section('title', 'Data Santri - Santri Admin')
@section('mobile_title', 'Manajemen Santri')
@section('breadcrumb', 'Santri')

@section('content')
    <!-- Page Heading -->
    <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Data Santri</h1>
                <p class="text-[#4c739a] text-sm sm:text-base font-normal">Kelola data santri untuk rekomendasi pemulangan
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button onclick="toggleImportModal()"
                    class="flex items-center gap-2 h-10 sm:h-11 px-4 rounded-xl border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex-1 sm:flex-none justify-center">
                    <span class="material-symbols-outlined text-[20px]">upload_file</span>
                    <span>Impor</span>
                </button>
                <a href="#" {{-- {{ route('students.export') }} --}}
                    class="flex items-center gap-2 h-10 sm:h-11 px-4 rounded-xl border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex-1 sm:flex-none justify-center">
                    <span class="material-symbols-outlined text-[20px]">file_download</span>
                    <span>Ekspor</span>
                </a>
                <a href="{{ route('students.create') }}"
                    class="group flex items-center gap-2 h-10 sm:h-11 px-5 rounded-xl bg-primary hover:bg-primary/90 hover:shadow-xl hover:shadow-primary/30 text-white text-sm font-bold shadow-lg transition-all transform hover:-translate-y-0.5 flex-1 sm:flex-none justify-center">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                    <span class="whitespace-nowrap">Tambah Santri Baru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div
            class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div
            class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter & Search Bar -->
    {{-- <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-4">
        <form action="{{ route('students.index') }}" method="GET"
            class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <!-- Search logic simplified for now -->
        </form>
    </div> --}}

    <!-- Data Table -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-16 whitespace-nowrap">
                            No
                        </th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-24 whitespace-nowrap">
                            NIS
                        </th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">
                            Nama Lengkap
                        </th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-32 whitespace-nowrap">
                            Rayon
                        </th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-32 whitespace-nowrap">
                            Jenis Kelamin
                        </th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-32 whitespace-nowrap">
                            Status
                        </th>
                        <th
                            class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase w-40 text-center whitespace-nowrap">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @forelse($students as $s)
                        @php
                            $initials = strtoupper(substr($s->name, 0, 1) . (str_contains($s->name, ' ') ? substr($s->name, strpos($s->name, ' ') + 1, 1) : substr($s->name, 1, 1)));
                            $colors = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                            $colorIndex = crc32($s->id) % count($colors);
                            $color = $colors[$colorIndex];
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white whitespace-nowrap">
                                @if(method_exists($students, 'firstItem'))
                                    {{ $students->firstItem() + $loop->index }}
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </td>
                            <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white whitespace-nowrap">
                                {{ $s->nis }}
                            </td>
                            <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white flex items-center gap-3">
                                @if ($s->photo)
                                    <button
                                        @click="$store.imageModal.open('{{ asset('storage/' . $s->photo) }}', '{{ $s->name }}')"
                                        class="shrink-0 focus:outline-none focus:ring-2 focus:ring-primary rounded-full">
                                        <img src="{{ asset('storage/' . $s->photo) }}" alt="{{ $s->name }}"
                                            class="h-8 w-8 rounded-full object-cover ring-2 ring-white dark:ring-slate-800 hover:scale-110 transition-transform cursor-zoom-in">
                                    </button>
                                @else
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 font-bold text-xs ring-1 ring-{{ $color }}-600/20">
                                        {{ $initials }}
                                    </div>
                                @endif
                                <span class="whitespace-nowrap">{{ $s->name }}</span>
                            </td>
                            <td class="p-4 text-sm whitespace-nowrap">
                                <span class="whitespace-nowrap">
                                    {{ $s->rayon?->name }}
                                </span><br>
                                <span class="text-xs text-[#4c739a] whitespace-nowrap">
                                    {{ $s->room?->name }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-[#4c739a] whitespace-nowrap">
                                {{ $s->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                @if($s->status == 'active')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">Aktif</span>
                                @elseif($s->status == 'inactive')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">Nonaktif</span>
                                @elseif($s->status == 'graduated')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">Lulus</span>
                                @elseif($s->status == 'dropped_out')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">Keluar</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 border border-gray-200 dark:border-gray-800">{{ ucfirst($s->status) }}</span>
                                @endif
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <div
                                    class="flex items-center justify-center gap-2 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('students.show', $s) }}"
                                        class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                        title="Lihat Detail">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    <a href="{{ route('students.edit', $s) }}"
                                        class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors"
                                        title="Ubah">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form action="{{ route('students.destroy', $s) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus santri {{ $s->name }}?')"
                                            type="button"
                                            class="p-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 text-[#4c739a] hover:text-red-600 transition-colors"
                                            title="Hapus">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-[#4c739a]">
                                <span class="material-symbols-outlined text-4xl mb-2">person_off</span>
                                <p>Belum ada data santri</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
            <div
                class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 border-t border-[#e7edf3] dark:border-slate-800">
                <p class="text-sm text-[#4c739a]">
                    Menampilkan <span class="font-medium text-[#0d141b] dark:text-white">{{ $students->firstItem() }}</span>
                    sampai
                    <span class="font-medium text-[#0d141b] dark:text-white">{{ $students->lastItem() }}</span> dari
                    <span class="font-medium text-[#0d141b] dark:text-white">{{ $students->total() }}</span> data
                </p>
                <div class="flex items-center gap-1">
                    {{ $students->links() }}
                    {{-- Minimalist pagination, assuming existing links() works with Tailwind config --}}
                </div>
            </div>
        @endif
    </div>

    <!-- Import Modal (Temporarily hidden/disabled until Import logic refactored) -->
    {{-- Import modal code removed for now --}}

@endsection