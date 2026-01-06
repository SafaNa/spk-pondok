@extends('layouts.app')

@section('title', 'Manage Subkriteria - ' . $kriteria->nama_kriteria)
@section('mobile_title', 'Subkriteria')
@section('breadcrumb', 'Kriteria / Subkriteria')

@section('content')
    <!-- Page Header -->
    <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('kriteria.index') }}" class="text-blue-600 hover:text-blue-700 transition-colors">
                        <span class="material-symbols-outlined text-[24px]">arrow_back</span>
                    </a>
                    <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Subkriteria:
                        {{ $kriteria->nama_kriteria }}
                    </h1>
                </div>
                <p class="text-[#4c739a] text-sm sm:text-base font-normal">Manage sub-criteria and their specific values
                    for this criteria. These values are used for detailed assessment.</p>
            </div>
            <a href="{{ route('kriteria.subkriteria.create', $kriteria->id) }}"
                class="group flex items-center gap-2 h-11 px-5 rounded-xl bg-primary hover:bg-primary/90 hover:shadow-xl hover:shadow-primary/30 text-white text-sm font-bold shadow-lg transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                Add New Subkriteria
            </a>
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

    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
        <div
            class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="font-semibold text-[#0d141b] dark:text-white">Subkriteria List</h3>
            <span
                class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-[#4c739a]">{{ $subkriteria->count() }}
                Items</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="border-b border-[#e7edf3] dark:border-slate-700 text-xs uppercase tracking-wider text-[#4c739a]">
                        <th class="px-6 py-4 font-semibold w-1/3 whitespace-nowrap">Name</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Value</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Description</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @forelse($subkriteria as $sub)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="px-6 py-4">
                                <p class="font-medium text-[#0d141b] dark:text-white text-sm">
                                    {{ $sub->nama_subkriteria }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ $sub->nilai }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-[#4c739a]">{{ $sub->keterangan ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('kriteria.subkriteria.edit', [$kriteria->id, $sub->id]) }}"
                                        class="p-1.5 text-[#4c739a] hover:text-primary transition-colors rounded hover:bg-slate-100 dark:hover:bg-slate-700"
                                        title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('kriteria.subkriteria.destroy', [$kriteria->id, $sub->id]) }}"
                                        method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus subkriteria {{ $sub->nama_subkriteria }}?')"
                                            type="button"
                                            class="p-1.5 text-[#4c739a] hover:text-red-500 transition-colors rounded hover:bg-red-50 dark:hover:bg-red-900/20"
                                            title="Delete">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-[#4c739a]">
                                <span class="material-symbols-outlined text-4xl mb-2">list_alt</span>
                                <p>Belum ada subkriteria</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div
            class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800/30 flex justify-between items-center text-sm">
            <span class="text-[#4c739a]">Showing {{ $subkriteria->count() }} items</span>
            {{ $subkriteria->links() }}
        </div>
    </div>
@endsection