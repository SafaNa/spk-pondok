@extends('layouts.app')

@section('title', 'Jenis Pelanggaran')
@section('breadcrumb', 'Jenis')
@section('breadcrumb_parent', 'Pelanggaran')
@section('breadcrumb_parent_route', 'admin.violations.index')
@section('mobile_title', 'Jenis Pelanggaran')

@section('content')
    <div class="flex flex-col gap-6">
        {{-- Header --}}
        <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
            style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                <div class="flex flex-col gap-1">
                    <h2 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Jenis Pelanggaran</h2>
                    <p class="text-[#4c739a] text-sm sm:text-base font-normal">Manajemen data jenis pelanggaran dan poin
                        sanksi</p>
                </div>
                <a href="{{ route('admin.violation-types.create') }}"
                    class="group flex items-center justify-center gap-2 rounded-xl px-5 h-11 bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-lg hover:shadow-xl hover:shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-200">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                    <span>Tambah Jenis Pelanggaran</span>
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="rounded-xl p-4 border border-green-200 bg-green-50 dark:bg-green-900/10 dark:border-green-800 flex items-start gap-4">
                <div
                    class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-800 text-green-600 dark:text-green-300">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                </div>
                <div class="flex-1 pt-1">
                    <p class="font-semibold text-green-800 dark:text-green-200 text-sm">Berhasil!</p>
                    <p class="text-green-700 dark:text-green-300 text-sm mt-0.5">{{ session('success') }}</p>
                </div>
                <button @click="show = false"
                    class="flex-shrink-0 text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-200 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="rounded-xl p-4 border border-red-200 bg-red-50 dark:bg-red-900/10 dark:border-red-800 flex items-start gap-4">
                <div
                    class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-800 text-red-600 dark:text-red-300">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                </div>
                <div class="flex-1 pt-1">
                    <p class="font-semibold text-red-800 dark:text-red-200 text-sm">Gagal!</p>
                    <p class="text-red-700 dark:text-red-300 text-sm mt-0.5">{{ session('error') }}</p>
                </div>
                <button @click="show = false"
                    class="flex-shrink-0 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
            <div class="px-4 py-4 border-b border-[#e7edf3] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col 2xl:flex-row justify-between items-start 2xl:items-center gap-4">
                <div class="flex items-center gap-3">
                    <h3 class="font-semibold text-[#0d141b] dark:text-white whitespace-nowrap">Daftar Jenis Pelanggaran</h3>
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-200 dark:bg-slate-700 text-[#4c739a]">
                        {{ $violationTypes->total() }} Data
                    </span>
                </div>
                
                {{-- Filter and Search Form --}}
                <form action="{{ route('admin.violation-types.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap items-center gap-2 w-full 2xl:w-auto">
                    <div class="w-full sm:w-auto">
                        <select name="ruleset" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm truncate" onchange="this.form.submit()">
                            <option value="">Semua Tata Tertib</option>
                            <option value="pesantren" {{ request('ruleset') == 'pesantren' ? 'selected' : '' }}>Pesantren</option>
                            <option value="madrasah" {{ request('ruleset') == 'madrasah' ? 'selected' : '' }}>Madrasah</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-auto">
                        <select name="category_id" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm truncate" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(Auth::user()->isAdmin())
                    <div class="w-full sm:w-auto">
                        <select name="department_id" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm truncate" onchange="this.form.submit()">
                            <option value="">Semua Departemen</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->acronym }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="w-full sm:w-auto flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama..." class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm" />
                    </div>
                    <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 px-3 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-colors text-sm">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        <span class="sm:hidden">Cari</span>
                    </button>
                    @if(request()->anyFilled(['search', 'category_id', 'ruleset', 'department_id']))
                        <a href="{{ route('admin.violation-types.index') }}" class="w-full sm:w-auto flex items-center justify-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors text-sm" title="Reset Filter">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                            <span class="sm:hidden">Reset</span>
                        </a>
                    @endif
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-[#e7edf3] dark:border-slate-700 text-xs uppercase tracking-wider text-[#4c739a] bg-slate-50/50 dark:bg-slate-800/20">
                            <th class="px-6 py-4 font-semibold whitespace-nowrap w-36">Kode</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap">Nama Pelanggaran</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap">Kategori</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap">Departemen</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap text-center">Poin</th>
                            <th class="px-6 py-4 font-semibold whitespace-nowrap text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                        @forelse($violationTypes as $violationType)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                        {{ $violationType->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-semibold text-[#0d141b] dark:text-white">{{ $violationType->name }}</span>
                                        <span
                                            class="text-xs text-[#4c739a] mt-0.5 line-clamp-1">{{ Str::limit($violationType->default_sanction, 50) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        {{ $violationType->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-[#4c739a]">{{ $violationType->department->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="font-bold text-[#0d141b] dark:text-white">{{ $violationType->category->points }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2 opacit-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.violation-types.edit', $violationType->id) }}"
                                            class="p-2 text-[#4c739a] hover:text-blue-600 transition-colors rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20"
                                            title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.violation-types.destroy', $violationType->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus jenis pelanggaran {{ $violationType->name }}?')"
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
                                <td colspan="6" class="px-6 py-12 text-center text-[#4c739a]">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                            <span class="material-symbols-outlined text-3xl opacity-50">gavel</span>
                                        </div>
                                        <p class="font-medium text-lg text-[#0d141b] dark:text-white mb-1">Belum ada data
                                            jenis pelanggaran</p>
                                        <p class="text-sm mb-4">Silakan tambahkan jenis pelanggaran baru</p>
                                        <a href="{{ route('admin.violation-types.create') }}"
                                            class="text-primary hover:underline text-sm font-medium">Tambah Jenis
                                            Pelanggaran</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700">
                {{ $violationTypes->links() }}
            </div>
        </div>
    </div>
@endsection