@extends('layouts.app-v2-sidebar')

@section('title', 'Kriteria Management - Santri Admin')
@section('mobile_title', 'Kriteria')
@section('breadcrumb', 'Kriteria Management')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div class="max-w-2xl">
            <h1 class="text-3xl font-bold tracking-tight text-[#0d141b] dark:text-white mb-2">Manajemen Kriteria & Bobot
            </h1>
            <p class="text-[#4c739a]">Define the evaluation criteria for Santri selection. Ensure the total weight
                distribution equals 100% for valid SAW calculation.</p>
        </div>
        <a href="{{ route('kriteria-form-v2') }}"
            class="flex items-center gap-2 bg-primary hover:bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-all active:scale-95 whitespace-nowrap">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Add New Kriteria
        </a>
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

    <!-- Weight Allocation Progress Bar -->
    @php
        $remaining = 100 - $totalBobot;
        $isComplete = $totalBobot == 100;
        $isOver = $totalBobot > 100;
    @endphp
    <div
        class="bg-white dark:bg-slate-900 rounded-xl border {{ $isComplete ? 'border-green-200 dark:border-green-900/30' : ($isOver ? 'border-red-200 dark:border-red-900/30' : 'border-orange-200 dark:border-orange-900/30') }} p-5 shadow-sm relative overflow-hidden">
        <div
            class="absolute top-0 left-0 w-1 h-full {{ $isComplete ? 'bg-green-500' : ($isOver ? 'bg-red-500' : 'bg-orange-500') }}">
        </div>
        <div class="flex flex-col gap-3 relative z-10">
            <div class="flex justify-between items-end">
                <div>
                    <p class="text-sm font-semibold text-[#0d141b] dark:text-white flex items-center gap-2">
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
                    class="text-2xl font-bold {{ $isComplete ? 'text-green-600' : ($isOver ? 'text-red-600' : 'text-[#0d141b] dark:text-white') }} tabular-nums">
                    {{ $totalBobot }}%</p>
            </div>
            <div class="h-3 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full {{ $isComplete ? 'bg-green-500' : ($isOver ? 'bg-red-500' : 'bg-orange-500') }} rounded-full transition-all duration-500 ease-out"
                    style="width: {{ min(100, $totalBobot) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Layout Grid: Table & Subkriteria Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Left Column: Criteria Table -->
        <div class="lg:col-span-7 flex flex-col gap-6">
            <div
                class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="font-semibold text-[#0d141b] dark:text-white">Criteria List</h3>
                    <span
                        class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-[#4c739a]">{{ $kriteria->count() }}
                        Items</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-[#e7edf3] dark:border-slate-700 text-xs uppercase tracking-wider text-[#4c739a]">
                                <th class="px-6 py-4 font-semibold w-1/3">Criteria Name</th>
                                <th class="px-6 py-4 font-semibold">Type</th>
                                <th class="px-6 py-4 font-semibold">Weight</th>
                                <th class="px-6 py-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                            @forelse($kriteria as $k)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group"
                                    data-kriteria-id="{{ $k->id }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="size-2 bg-transparent rounded-full"></div>
                                            <p class="font-medium text-[#0d141b] dark:text-white text-sm">
                                                {{ $k->nama_kriteria }}</p>
                                        </div>
                                        <p class="text-xs text-[#4c739a] ml-5 mt-0.5">Code: {{ $k->kode_kriteria }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($k->jenis == 'benefit')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Benefit</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Cost</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm font-semibold text-[#0d141b] dark:text-white">{{ $k->bobot }}%</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('kriteria-edit-v2', $k->id) }}"
                                                class="p-1.5 text-[#4c739a] hover:text-primary transition-colors rounded hover:bg-slate-100 dark:hover:bg-slate-700"
                                                title="Edit">
                                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                            </a>
                                            <form action="{{ route('kriteria.destroy', $k) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-[#4c739a] hover:text-red-500 transition-colors rounded hover:bg-red-50 dark:hover:bg-red-900/20"
                                                    title="Delete">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                            <a href="{{ route('kriteria.subkriteria.index', $k) }}"
                                                class="p-1.5 text-primary bg-white dark:bg-slate-800 shadow-sm border border-[#e7edf3] dark:border-slate-600 rounded hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
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
                    class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800/30 flex justify-between items-center text-sm">
                    <span class="text-[#4c739a]">Showing {{ $kriteria->count() }} of {{ $kriteria->count() }}
                        criteria</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Subkriteria Panel (Static Preview) -->
        <div class="lg:col-span-5">
            <div
                class="sticky top-24 bg-white dark:bg-slate-900 rounded-xl shadow-lg border border-primary/20 dark:border-primary/20 flex flex-col h-[500px] overflow-hidden">
                <div
                    class="px-6 py-5 border-b border-[#e7edf3] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col gap-1">
                    <span class="text-xs font-semibold uppercase tracking-wider text-primary mb-1">Sub-criteria
                        Preview</span>
                    <h3 class="text-xl font-bold text-[#0d141b] dark:text-white">Select a Criteria</h3>
                    <p class="text-sm text-[#4c739a]">Click the arrow button on a criteria to manage its subkriteria</p>
                </div>
                <div class="flex-1 flex items-center justify-center text-[#4c739a]">
                    <div class="text-center">
                        <span class="material-symbols-outlined text-5xl mb-3">list_alt</span>
                        <p>Select a criteria from the table</p>
                        <p class="text-xs mt-1">to view and manage its subkriteria</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal Removed -->

    <!-- Edit Modal -->
    <!-- Edit Modal Removed -->

    <script>
        // Scripts removed as modals are no longer used
    </script>
    </script>
@endsection