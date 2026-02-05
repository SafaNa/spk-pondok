@extends('layouts.app')

@section('content')
    <div class="p-6">
        {{-- Enhanced Header --}}
        <div class="mb-8 rounded-2xl bg-gradient-to-br from-indigo-50 via-blue-50/50 to-purple-50/30 p-8 dark:from-slate-800 dark:via-slate-800/80 dark:to-slate-800/50">
            <div class="flex items-start justify-between">
                <div>
                    <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-indigo-600 backdrop-blur-sm dark:bg-slate-700/80 dark:text-indigo-400">
                        <span class="material-symbols-outlined text-[16px]">event_note</span>
                        Manajemen Event
                    </div>
                    <h1 class="font-outfit text-3xl font-bold text-gray-900 dark:text-white">Daftar Event Liburan</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kelola event liburan dan libur massal santri</p>
                </div>
                <a href="{{ route('licensing-events.create') }}"
                    class="group flex items-center gap-2 rounded-xl bg-gradient-to-r from-primary to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/30 transition-all hover:shadow-xl hover:shadow-primary/40">
                    <span class="material-symbols-outlined text-[22px] transition-transform group-hover:rotate-90">add</span>
                    Buat Event Baru
                </a>
            </div>
        </div>

        {{-- Stats Summary --}}
        <div class="mb-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400">
                        <span class="material-symbols-outlined text-[28px]">check_circle</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $events->where('is_active', true)->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Event Aktif</div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-50 text-gray-600 dark:bg-gray-500/10 dark:text-gray-400">
                        <span class="material-symbols-outlined text-[28px]">event_busy</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $events->where('is_active', false)->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Non-Aktif</div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                        <span class="material-symbols-outlined text-[28px]">calendar_month</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $events->total() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Event</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50 dark:border-slate-700 dark:from-slate-700/50 dark:to-slate-700/30">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">event</span>
                                    <span>Nama Event</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                                    <span>Periode</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">schedule</span>
                                    <span>Durasi</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">Status</th>
                            <th class="px-6 py-4 text-right font-semibold text-gray-700 dark:text-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($events as $event)
                            <tr class="group transition-colors hover:bg-gray-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-primary to-blue-600 text-white shadow-sm">
                                            <span class="material-symbols-outlined text-[22px]">celebration</span>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $event->name }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ substr($event->id, 0, 8) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 dark:text-white">
                                        {{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $event->start_date->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 ring-1 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20">
                                        <span class="material-symbols-outlined text-[16px]">timelapse</span>
                                        {{ $event->start_date->diffInDays($event->end_date) + 1 }} Hari
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($event->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700 ring-1 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                            <span class="h-2 w-2 animate-pulse rounded-full bg-green-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-50 px-3 py-1.5 text-xs font-medium text-gray-600 ring-1 ring-gray-500/10 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/20">
                                            <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                                            Non-aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('licensing-events.edit', $event) }}"
                                            class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-500/10 dark:hover:text-blue-400"
                                            title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="{{ route('licensing-events.destroy', $event) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400" title="Hapus">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-700 dark:to-slate-600">
                                            <span class="material-symbols-outlined text-[40px] text-gray-400">event_busy</span>
                                        </div>
                                        <p class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Belum Ada Event</p>
                                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Buat event liburan pertama Anda</p>
                                        <a href="{{ route('licensing-events.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600">
                                            <span class="material-symbols-outlined text-[20px]">add</span>
                                            Buat Event
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($events->hasPages())
                <div class="border-t border-gray-100 bg-gray-50/50 px-6 py-4 dark:border-slate-700 dark:bg-slate-700/30">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
