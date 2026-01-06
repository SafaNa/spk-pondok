@extends('layouts.app')

@section('title', 'Kriteria Management - Santri Admin')
@section('mobile_title', 'Kriteria')
@section('breadcrumb', 'Kriteria Management')

@section('content')
    <!-- Page Header -->
    <div
        class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Manajemen Kriteria & Bobot
                </h1>
                <p class="text-[#4c739a] text-sm sm:text-base font-normal">Define the evaluation criteria for Santri
                    selection. Ensure the total weight
                    distribution equals 100% for valid SAW calculation.</p>
            </div>
            <a href="{{ route('kriteria.create') }}"
                class="group flex items-center gap-2 h-11 px-5 rounded-xl bg-primary hover:bg-primary/90 hover:shadow-xl hover:shadow-primary/30 text-white text-sm font-bold shadow-lg transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                Add New Kriteria
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div
            class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div
            class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Weight Allocation Progress Bar -->
    @php
        $remaining = 100 - $totalBobot;
        $isComplete = $totalBobot == 100;
        $isOver = $totalBobot > 100;
    @endphp
    <div
        class="bg-white rounded-xl border {{ $isComplete ? 'border-green-200' : ($isOver ? 'border-red-200' : 'border-orange-200') }} p-5 shadow-sm relative overflow-hidden">
        <div
            class="absolute top-0 left-0 w-1 h-full {{ $isComplete ? 'bg-green-500' : ($isOver ? 'bg-red-500' : 'bg-orange-500') }}">
        </div>
        <div class="flex flex-col gap-3 relative z-10">
            <div class="flex justify-between items-end">
                <div>
                    <p class="text-sm font-semibold text-[#0d141b] flex items-center gap-2">
                        <span
                            class="material-symbols-outlined {{ $isComplete ? 'text-green-500' : ($isOver ? 'text-red-500' : 'text-orange-500') }}">
                            {{ $isComplete ? 'check_circle' : ($isOver ? 'error' : 'warning') }}
                        </span>
                        Total Weight Allocation
                    </p>
                    <p class="text-xs text-[#4c739a] mt-1">
                        @if($isComplete)
                            ✓ Weight allocation complete
                        @elseif($isOver)
                            ⚠ Weight exceeds 100% by {{ $totalBobot - 100 }}%
                        @else
                            Target: 100% ({{ $remaining }}% remaining to be allocated)
                        @endif
                    </p>
                </div>
                <p
                    class="text-2xl font-bold {{ $isComplete ? 'text-green-600' : ($isOver ? 'text-red-600' : 'text-[#0d141b]') }} tabular-nums">
                    {{ $totalBobot }}%
                </p>
            </div>
            <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full {{ $isComplete ? 'bg-green-500' : ($isOver ? 'bg-red-500' : 'bg-orange-500') }} rounded-full transition-all duration-500 ease-out"
                    style="width: {{ min(100, $totalBobot) }}%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-12 flex flex-col gap-6">
            <div
                class="bg-white rounded-xl shadow-sm border border-[#e7edf3] overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-[#e7edf3] flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-semibold text-[#0d141b]">Criteria List</h3>
                    <span
                        class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 text-[#4c739a]">{{ $kriteria->count() }}
                        Items</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-[#e7edf3] text-xs uppercase tracking-wider text-[#4c739a]">
                                <th class="px-6 py-4 font-semibold w-1/3 whitespace-nowrap">Criteria Name</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Type</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Weight</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap text-center">Subcriteria</th>
                                <th class="px-6 py-4 font-semibold text-right whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e7edf3]">
                            @forelse($kriteria as $k)
                                <tr class="hover:bg-slate-50 transition-colors group"
                                    data-kriteria-id="{{ $k->id }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="size-2 bg-transparent rounded-full"></div>
                                            <p class="font-medium text-[#0d141b] text-sm">
                                                {{ $k->nama_kriteria }}
                                            </p>
                                        </div>
                                        <p class="text-xs text-[#4c739a] ml-5 mt-0.5">Code: {{ $k->kode_kriteria }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($k->jenis == 'benefit')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Benefit</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cost</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm font-semibold text-[#0d141b]">{{ $k->bobot }}%</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $k->subkriteria->count() }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="flex items-center justify-end gap-2">
                                            <a href="{{ route('kriteria.edit', $k->id) }}"
                                                class="p-1.5 text-[#4c739a] hover:text-primary transition-colors rounded hover:bg-slate-100"
                                                title="Edit">
                                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                            </a>
                                            <form action="{{ route('kriteria.destroy', $k->id) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus kriteria {{ $k->nama_kriteria }}?')"
                                                    type="button"
                                                    class="p-1.5 text-[#4c739a] hover:text-red-500 transition-colors rounded hover:bg-red-50"
                                                    title="Delete">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                            <a href="{{ route('kriteria.subkriteria.index', $k) }}"
                                                class="p-1.5 text-[#4c739a] hover:text-primary transition-colors rounded hover:bg-slate-100"
                                                title="Manage Subkriteria">
                                                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-[#4c739a]">
                                        <span class="material-symbols-outlined text-4xl mb-2">tune</span>
                                        <p>Belum ada kriteria</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div
                    class="px-6 py-4 border-t border-[#e7edf3] bg-slate-50 flex justify-between items-center text-sm">
                    <span class="text-[#4c739a]">Showing {{ $kriteria->count() }} of {{ $kriteria->count() }}
                        criteria</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Scripts removed as modals are no longer used
    </script>
    </script>
@endsection