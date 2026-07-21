@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Riwayat Hafalan Santri</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Daftar pencatatan hafalan santri sebagai syarat perizinan pulang.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.memorization.create') }}"
                class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Catat Hafalan
            </a>
        </div>
    </div>

    {{-- Filter and Search Form --}}
    <form action="{{ route('admin.memorization.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap items-center gap-2 w-full 2xl:w-auto mb-4">
        <div class="w-full sm:w-auto flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIS/Nama..." class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm truncate" />
        </div>
        <div class="w-full sm:w-auto">
            <select name="education_level" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm truncate" onchange="this.form.submit()">
                <option value="">Semua Jenjang</option>
                <option value="MTS" {{ request('education_level') == 'MTS' ? 'selected' : '' }}>MTs Sederajat</option>
                <option value="MA" {{ request('education_level') == 'MA' ? 'selected' : '' }}>MA Sederajat</option>
                <option value="PT" {{ request('education_level') == 'PT' ? 'selected' : '' }}>PT Sederajat</option>
            </select>
        </div>
        <div class="w-full sm:w-auto">
            <select name="status" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-sm truncate" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Selesai</option>
            </select>
        </div>
        <div class="w-full sm:w-auto">
            <button type="submit" class="w-full sm:w-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Cari
            </button>
        </div>
    </form>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase font-semibold tracking-wider">
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Santri</th>
                        <th class="px-6 py-4">Jenjang</th>
                        <th class="px-6 py-4 text-center">Hari</th>
                        <th class="px-6 py-4 text-center">Progress</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Telah Dipakai</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($memorizations as $mem)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                {{ $mem->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $initials = strtoupper(substr($mem->student->name, 0, 1) . (str_contains($mem->student->name, ' ') ? substr($mem->student->name, strpos($mem->student->name, ' ') + 1, 1) : substr($mem->student->name, 1, 1)));
                                        $colors = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                                        $color = $colors[crc32($mem->student->id) % count($colors)];
                                    @endphp
                                    <div class="flex h-10 w-10 items-center justify-center shrink-0">
                                        @if ($mem->student->photo)
                                            <button @click="$store.imageModal.open('{{ asset('storage/' . $mem->student->photo) }}', '{{ $mem->student->name }}')" class="shrink-0 focus:outline-none focus:ring-2 focus:ring-primary rounded-full">
                                                <img src="{{ asset('storage/' . $mem->student->photo) }}" alt="{{ $mem->student->name }}" class="h-10 w-10 rounded-full object-cover ring-1 ring-slate-200 hover:scale-110 transition-transform cursor-zoom-in">
                                            </button>
                                        @else
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 font-bold text-xs ring-1 ring-{{ $color }}-600/20">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ $mem->student->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $mem->student->nis }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $levelLabel = ['MTS' => 'MTs Sederajat', 'MA' => 'MA Sederajat', 'PT' => 'PT Sederajat'];
                                    $levelBg = ['MTS' => 'bg-emerald-100 text-emerald-700', 'MA' => 'bg-blue-100 text-blue-700', 'PT' => 'bg-violet-100 text-violet-700'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $levelBg[$mem->education_level] ?? '' }}">
                                    {{ $levelLabel[$mem->education_level] ?? $mem->education_level }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-semibold text-slate-700 dark:text-slate-300">
                                {{ $mem->days }} hari
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $total   = $mem->items->count();
                                    $checked = $mem->items->where('is_checked', true)->count();
                                    $pct     = $total > 0 ? round(($checked / $total) * 100) : 0;
                                @endphp
                                <div class="text-xs font-medium text-slate-500 mb-1">{{ $checked }}/{{ $total }}</div>
                                <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary rounded-full" style="width:{{ $pct }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($mem->status == 'completed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                                        <span class="material-symbols-outlined text-[14px]">pending</span>
                                        Belum Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($mem->is_used)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300" title="Hafalan ini sudah digunakan untuk izin pulang">
                                        <span class="material-symbols-outlined text-[14px]">lock</span>
                                        Ya
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400" title="Hafalan ini belum dipakai dan bisa digunakan untuk izin">
                                        <span class="material-symbols-outlined text-[14px]">lock_open</span>
                                        Belum
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.memorization.show', $mem->id) }}"
                                        class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-primary hover:bg-primary/10 dark:hover:bg-primary/20 flex items-center justify-center transition-colors"
                                        title="Lihat Detail">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
                                    <form action="{{ route('admin.memorization.destroy', $mem->id) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data hafalan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/20 flex items-center justify-center transition-colors tooltip"
                                            title="Hapus">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">history_edu</span>
                                <p>Tidak ada data riwayat hafalan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($memorizations->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $memorizations->links() }}
            </div>
        @endif
    </div>
@endsection