@extends('layouts.app')

@section('content')
    <div class="p-6">
        {{-- Header with Gradient --}}
        <div class="mb-8 rounded-2xl bg-gradient-to-br from-blue-50 via-indigo-50/50 to-purple-50/30 p-8 dark:from-slate-800 dark:via-slate-800/80 dark:to-slate-800/50">
            <div class="flex items-start justify-between">
                <div>
                    <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-primary backdrop-blur-sm dark:bg-slate-700/80">
                        <span class="material-symbols-outlined text-[16px]">verified_user</span>
                        Sistem Perizinan Pulang
                    </div>
                    <h1 class="font-outfit text-3xl font-bold text-gray-900 dark:text-white">Dashboard Perizinan</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Kelola validasi kepulangan santri dengan sistem terintegrasi</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('licenses.create') }}"
                        class="group flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-gray-200 transition-all hover:shadow-md hover:ring-gray-300 dark:bg-slate-800 dark:text-gray-200 dark:ring-slate-700 dark:hover:ring-slate-600">
                        <span class="material-symbols-outlined text-[22px] transition-transform group-hover:scale-110">person_add</span>
                        Izin Individu
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="mb-8 grid gap-6 md:grid-cols-3">


            <div class="group relative overflow-hidden rounded-2xl p-6 shadow-lg transition-all hover:shadow-xl"
                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full" style="background: rgba(255, 255, 255, 0.1);"></div>
                <div class="relative">
                    <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-xl backdrop-blur-sm" style="background: rgba(255, 255, 255, 0.2);">
                        <span class="material-symbols-outlined text-[28px]">check_circle</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $recentLicenses->where('status', 'approved')->count() }}</div>
                    <div class="text-sm" style="color: rgba(255, 255, 255, 0.9);">Disetujui (Terbaru)</div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl p-6 shadow-lg transition-all hover:shadow-xl"
                style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); color: white; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full" style="background: rgba(255, 255, 255, 0.1);"></div>
                <div class="relative">
                    <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-xl backdrop-blur-sm" style="background: rgba(255, 255, 255, 0.2);">
                        <span class="material-symbols-outlined text-[28px]">pending</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $recentLicenses->where('status', 'pending')->count() }}</div>
                    <div class="text-sm" style="color: rgba(255, 255, 255, 0.9);">Pending</div>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl p-6 shadow-lg transition-all hover:shadow-xl"
                style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); color: white; box-shadow: 0 10px 15px -3px rgba(168, 85, 247, 0.3);">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full" style="background: rgba(255, 255, 255, 0.1);"></div>
                <div class="relative">
                    <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-xl backdrop-blur-sm" style="background: rgba(255, 255, 255, 0.2);">
                        <span class="material-symbols-outlined text-[28px]">person</span>
                    </div>
                    <div class="text-3xl font-bold">{{ $recentLicenses->count() }}</div>
                    <div class="text-sm" style="color: rgba(255, 255, 255, 0.9);">Total Perizinan</div>
                </div>
            </div>
        </div>

        {{-- Recent Licenses --}}
        <div>
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Perizinan Terbaru</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">10 perizinan terakhir yang dicatat</p>
                </div>
            </div>
            
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-slate-700 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">Santri</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">Keterangan</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">Periode</th>

                            <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($recentLicenses as $license)
                            <tr class="group transition-colors hover:bg-gray-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $initials = strtoupper(substr($license->student->name, 0, 1) . (str_contains($license->student->name, ' ') ? substr($license->student->name, strpos($license->student->name, ' ') + 1, 1) : substr($license->student->name, 1, 1)));
                                            $colors = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                                            $colorIndex = crc32($license->student->id) % count($colors);
                                            $color = $colors[$colorIndex];
                                        @endphp
                                        @if ($license->student->photo)
                                            <button type="button"
                                                @click="$store.imageModal.open('{{ asset('storage/' . $license->student->photo) }}', '{{ $license->student->name }}')"
                                                class="shrink-0 rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                                                <img src="{{ asset('storage/' . $license->student->photo) }}"
                                                    alt="{{ $license->student->name }}"
                                                    class="h-10 w-10 rounded-full object-cover ring-2 ring-white transition-all group-hover:ring-primary dark:ring-slate-800">
                                            </button>
                                        @else
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 font-bold text-sm ring-1 ring-{{ $color }}-600/20">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $license->student->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $license->student->room->name ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ Str::limit($license->description ?? '-', 40) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-600 dark:text-gray-300">
                                        {{ $license->start_date->format('d M') }} - {{ $license->end_date->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $license->start_date->diffInDays($license->end_date) + 1 }} hari
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold 
                                            {{ $license->status === 'approved' 
                                                ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30' 
                                                : ($license->status === 'rejected' 
                                                    ? 'bg-red-50 text-red-700 ring-1 ring-red-600/10 dark:bg-red-400/10 dark:text-red-400 dark:ring-red-400/30' 
                                                    : 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-500 dark:ring-amber-400/20') }}">
                                            {{ ucfirst($license->status) }}
                                        </span>
                                        <a href="{{ route('licenses.edit', $license->id) }}" 
                                            class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-primary dark:hover:bg-slate-700 dark:hover:text-blue-400 transition-colors"
                                            title="Edit Izin">
                                            <span class="material-symbols-outlined text-[20px]">edit_square</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-slate-700">
                                            <span class="material-symbols-outlined text-[32px] text-gray-400">inbox</span>
                                        </div>
                                        <p class="font-medium text-gray-500 dark:text-gray-400">Belum ada data perizinan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
