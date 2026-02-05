@extends('layouts.app')

@section('title', 'Ketentuan Hafalan')
@section('breadcrumb', 'Ketentuan Hafalan')
@section('breadcrumb_parent', 'Data Master')
@section('breadcrumb_parent_route', 'dashboard')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Header --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            <div
                class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-6 py-6 sm:px-8 sm:py-8 border-b border-primary/10 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-md border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">menu_book</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Ketentuan Hafalan
                            Kepulangan</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Persyaratan hafalan untuk perizinan
                            pulang santri</p>
                    </div>
                </div>
                <a href="{{ route('memorization-types.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-medium hover:bg-primary-600 transition-all hover:shadow-lg shadow-primary/25">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    <span>Tambah</span>
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div
                class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm mb-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div
                class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm mb-2">
                <span class="material-symbols-outlined text-[20px]">error</span>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Content --}}
        @php
            $levelColors = [
                'MTS' => [
                    'accent' => 'emerald',
                    'bg' => 'bg-emerald-50 dark:bg-emerald-950/20',
                    'border' => 'border-emerald-200 dark:border-emerald-800',
                    'text' => 'text-emerald-700 dark:text-emerald-300',
                    'badge' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200',
                    'dot' => 'bg-emerald-500',
                ],
                'MA' => [
                    'accent' => 'blue',
                    'bg' => 'bg-blue-50 dark:bg-blue-950/20',
                    'border' => 'border-blue-200 dark:border-blue-800',
                    'text' => 'text-blue-700 dark:text-blue-300',
                    'badge' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-200',
                    'dot' => 'bg-blue-500',
                ],
                'PT' => [
                    'accent' => 'purple',
                    'bg' => 'bg-purple-50 dark:bg-purple-950/20',
                    'border' => 'border-purple-200 dark:border-purple-800',
                    'text' => 'text-purple-700 dark:text-purple-300',
                    'badge' => 'bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-200',
                    'dot' => 'bg-purple-500',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
            @foreach($types as $level => $levelTypes)
                @php
                    $colors = $levelColors[$level] ?? [
                        'accent' => 'slate',
                        'bg' => 'bg-slate-50 dark:bg-slate-900/20',
                        'border' => 'border-slate-200 dark:border-slate-700',
                        'text' => 'text-slate-700 dark:text-slate-300',
                        'badge' => 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200',
                        'dot' => 'bg-slate-500',
                    ];
                    $groupedByDay = $levelTypes->groupBy('day');
                @endphp

                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden hover:shadow-lg transition-shadow">
                    {{-- Header --}}
                    <div class="{{ $colors['bg'] }} {{ $colors['border'] }} border-b px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-10 w-10 rounded-xl {{ $colors['badge'] }} flex items-center justify-center font-bold text-base">
                                {{ $level }}
                            </div>
                            <div>
                                <h2 class="font-bold text-slate-900 dark:text-white text-base">{{ $level }} Sederajat</h2>
                                <p class="text-xs {{ $colors['text'] }} font-medium">{{ $groupedByDay->count() }} tingkat hari
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-5">
                        <div class="space-y-6">
                            @foreach($groupedByDay as $day => $items)
                                <div>
                                    {{-- Day Badge --}}
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="h-2 w-2 rounded-full {{ $colors['dot'] }}"></div>
                                        <span class="text-xs font-bold {{ $colors['text'] }} uppercase tracking-wide">Hari
                                            ke-{{ $day }}</span>
                                        <div class="flex-1 h-px bg-slate-200 dark:bg-slate-700"></div>
                                    </div>

                                    {{-- Items --}}
                                    <div class="space-y-1">
                                        @foreach($items as $item)
                                            <div
                                                class="group relative flex items-start gap-1 py-1 px-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                                <span
                                                    class="material-symbols-outlined text-slate-400 text-[16px] mt-0.5 shrink-0">check_small</span>
                                                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed flex-1">
                                                    {{ $item->target_description }}
                                                </p>

                                                {{-- Actions --}}
                                                <div
                                                    class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity ml-2">
                                                    <a href="{{ route('memorization-types.edit', $item->id) }}"
                                                        class="h-7 w-7 flex items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors"
                                                        title="Edit">
                                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                                    </a>
                                                    <div x-data>
                                                        <form action="{{ route('memorization-types.destroy', $item->id) }}"
                                                            method="POST" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus ketentuan ini?')"
                                                                class="h-7 w-7 flex items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors"
                                                                title="Hapus">
                                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection