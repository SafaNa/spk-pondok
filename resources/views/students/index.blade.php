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
                @if(Auth::user()->isAdmin())
                <button onclick="toggleImportModal()"
                    class="flex items-center gap-2 h-10 sm:h-11 px-4 rounded-xl border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex-1 sm:flex-none justify-center">
                    <span class="material-symbols-outlined text-[20px]">upload_file</span>
                    <span>Impor</span>
                </button>
                <a href="#" {{-- {{ route('admin.students.export') }} --}}
                    class="flex items-center gap-2 h-10 sm:h-11 px-4 rounded-xl border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm flex-1 sm:flex-none justify-center">
                    <span class="material-symbols-outlined text-[20px]">file_download</span>
                    <span>Ekspor</span>
                </a>
                <a href="{{ route('admin.students.create') }}"
                    class="group flex items-center gap-2 h-10 sm:h-11 px-5 rounded-xl bg-primary hover:bg-primary/90 hover:shadow-xl hover:shadow-primary/30 text-white text-sm font-bold shadow-lg transition-all transform hover:-translate-y-0.5 flex-1 sm:flex-none justify-center">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                    <span class="whitespace-nowrap">Tambah Santri Baru</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="mb-6 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-4">
        <form action="{{ route('admin.students.index') }}" method="GET">
            <div class="flex flex-col gap-3">
                {{-- Baris 1: Search --}}
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="material-symbols-outlined text-[#4c739a] text-[20px]">search</span>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau NIS santri..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d141b] dark:text-white text-sm placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                </div>

                {{-- Baris 2: Filter + Tombol sejajar --}}
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    {{-- 4 Select Filter --}}
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 flex-1">
                        {{-- Pendidikan --}}
                        <div class="relative">
                            <select name="education_level" style="background-image:none;"
                                class="w-full pl-3 pr-9 py-2.5 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all appearance-none">
                                <option value="">Semua Pendidikan</option>
                                @foreach($educationLevels as $level)
                                    <option value="{{ $level->id }}" {{ request('education_level') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5">
                                <span class="material-symbols-outlined text-[18px] text-[#4c739a]">expand_more</span>
                            </div>
                        </div>

                        {{-- Rayon --}}
                        <div class="relative">
                            <select name="rayon" style="background-image:none;"
                                class="w-full pl-3 pr-9 py-2.5 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all appearance-none">
                                <option value="">Semua Rayon</option>
                                @foreach($rayons as $rayon)
                                    <option value="{{ $rayon->id }}" {{ request('rayon') == $rayon->id ? 'selected' : '' }}>
                                        {{ $rayon->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5">
                                <span class="material-symbols-outlined text-[18px] text-[#4c739a]">expand_more</span>
                            </div>
                        </div>

                        {{-- Kamar --}}
                        <div class="relative">
                            <select name="room" style="background-image:none;"
                                class="w-full pl-3 pr-9 py-2.5 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all appearance-none">
                                <option value="">Semua Kamar</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ request('room') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5">
                                <span class="material-symbols-outlined text-[18px] text-[#4c739a]">expand_more</span>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="relative">
                            <select name="status" style="background-image:none;"
                                class="w-full pl-3 pr-9 py-2.5 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all appearance-none">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                                <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Lulus</option>
                                <option value="dropped_out" {{ request('status') == 'dropped_out' ? 'selected' : '' }}>Keluar</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5">
                                <span class="material-symbols-outlined text-[18px] text-[#4c739a]">expand_more</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol sejajar dengan selects --}}
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="submit"
                            class="flex items-center gap-1.5 h-[42px] px-4 rounded-lg bg-primary hover:bg-primary/90 text-white text-sm font-medium transition-colors whitespace-nowrap">
                            <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                            Filter
                        </button>
                        @if(request()->hasAny(['search','education_level','rayon','room','status']))
                            <a href="{{ route('admin.students.index') }}"
                                class="flex items-center gap-1.5 h-[42px] px-4 rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 text-[#4c739a] hover:text-red-500 hover:border-red-200 text-sm font-medium transition-colors whitespace-nowrap">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                                Reset
                            </a>
                            <span class="text-xs text-[#4c739a] hidden lg:inline">{{ $students->total() }} santri</span>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border-hidden">
                <thead>
                    <tr class="bg-gray-50 text-gray-700">
                        <th class="border border-gray-200 p-3 text-center font-bold w-12">No</th>
                        <th class="border border-gray-200 p-3 text-center font-bold">Foto</th>
                        <th class="border border-gray-200 p-3 text-left font-bold">Nama Lengkap</th>
                        <th class="border border-gray-200 p-3 text-center font-bold">NIS</th>
                        <th class="border border-gray-200 p-3 text-left font-bold">Rayon & Kamar</th>
                        <th class="border border-gray-200 p-3 text-left font-bold">Pendidikan</th>
                        <th class="border border-gray-200 p-3 text-center font-bold">Status</th>
                        <th class="border border-gray-200 p-3 text-center font-bold w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php
                        $avatarColors = [
                            ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                            ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' => 'border-orange-200'],
                            ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                            ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200'],
                            ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
                            ['bg' => 'bg-teal-100', 'text' => 'text-teal-700', 'border' => 'border-teal-200'],
                            ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200'],
                            ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                            ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200'],
                            ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'border' => 'border-violet-200'],
                            ['bg' => 'bg-fuchsia-100', 'text' => 'text-fuchsia-700', 'border' => 'border-fuchsia-200'],
                            ['bg' => 'bg-pink-100', 'text' => 'text-pink-700', 'border' => 'border-pink-200'],
                            ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'border' => 'border-rose-200'],
                        ];
                    @endphp
                    @forelse($students as $s)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="border border-gray-200 p-3 text-center font-bold text-gray-800">
                                @if(method_exists($students, 'firstItem'))
                                    {{ $students->firstItem() + $loop->index }}
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </td>
                            <td class="border border-gray-200 p-3 text-center">
                                <div class="flex justify-center">
                                    @if ($s->photo)
                                        <button
                                            @click="$store.imageModal.open('{{ asset('storage/' . $s->photo) }}', '{{ $s->name }}')"
                                            class="shrink-0 focus:outline-none focus:ring-2 focus:ring-primary rounded-full">
                                            <img src="{{ asset('storage/' . $s->photo) }}" alt="{{ $s->name }}"
                                                class="w-10 h-10 rounded-full object-cover border border-gray-200 hover:scale-110 transition-transform cursor-zoom-in">
                                        </button>
                                    @else
                                        @php
                                            $colorIndex = abs(crc32($s->name)) % count($avatarColors);
                                            $color = $avatarColors[$colorIndex];
                                        @endphp
                                        <div
                                            class="w-10 h-10 rounded-full {{ $color['bg'] }} flex items-center justify-center {{ $color['text'] }} font-bold text-sm border {{ $color['border'] }}">
                                            {{ strtoupper(substr($s->name, 0, 1) . (str_contains($s->name, ' ') ? substr($s->name, strpos($s->name, ' ') + 1, 1) : substr($s->name, 1, 1))) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="border border-gray-200 p-3 text-left font-medium text-gray-800 whitespace-nowrap">
                                {{ $s->name }}
                            </td>
                            <td class="border border-gray-200 p-3 text-center font-medium text-gray-800 whitespace-nowrap">
                                {{ $s->nis }}
                            </td>
                            <td class="border border-gray-200 p-3 text-left whitespace-nowrap text-gray-600">
                                <span class="font-medium text-gray-800">{{ $s->rayon?->name }}</span><br>
                                <span class="text-xs">{{ $s->room?->name }}</span>
                            </td>
                            <td class="border border-gray-200 p-3 text-left whitespace-nowrap text-gray-600">
                                @if($s->formalEducation)
                                    <span class="font-medium text-gray-800">{{ $s->formalEducation->name }}</span><br>
                                @endif
                                @if($s->religiousEducation)
                                    <span class="text-xs">{{ $s->religiousEducation->name }}</span>
                                @endif
                                @if(!$s->formalEducation && !$s->religiousEducation)
                                    <span class="text-xs">-</span>
                                @endif
                            </td>
                            <td class="border border-gray-200 p-3 text-center whitespace-nowrap">
                                @if($s->status == 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Aktif</span>
                                @elseif($s->status == 'inactive')
                                    <span class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-red-100 text-red-700 border border-red-200">Nonaktif</span>
                                @elseif($s->status == 'graduated')
                                    <span class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">Lulus</span>
                                @elseif($s->status == 'dropped_out')
                                    <span class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-200">Keluar</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">{{ ucfirst($s->status) }}</span>
                                @endif
                            </td>
                            <td class="border border-gray-200 p-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.students.show', $s) }}"
                                        class="w-8 h-8 rounded flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white transition-colors"
                                        title="Lihat Detail">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    </a>
                                    @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.students.edit', $s) }}"
                                        class="w-8 h-8 rounded flex items-center justify-center bg-yellow-400 hover:bg-yellow-500 text-white transition-colors"
                                        title="Edit">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $s) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus santri {{ $s->name }}?')"
                                            class="w-8 h-8 rounded flex items-center justify-center bg-red-600 hover:bg-red-700 text-white transition-colors"
                                            title="Hapus">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border border-gray-200 p-8 text-center text-gray-500">
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