@extends('layouts.app')

@section('title', 'Detail Hafalan Santri')
@section('breadcrumb', 'Detail Hafalan')
@section('breadcrumb_parent', 'Hafalan Santri')
@section('breadcrumb_parent_route', 'admin.memorization.index')
@section('mobile_title', 'Detail Hafalan')

@section('content')
    @php
        $levelColors = [
            'MTS' => ['bg' => 'bg-emerald-500', 'light' => 'bg-emerald-50 dark:bg-emerald-500/10', 'text' => 'text-emerald-700 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-500/30', 'dot' => 'bg-emerald-500'],
            'MA'  => ['bg' => 'bg-blue-500',    'light' => 'bg-blue-50 dark:bg-blue-500/10',       'text' => 'text-blue-700 dark:text-blue-400',       'border' => 'border-blue-200 dark:border-blue-500/30',   'dot' => 'bg-blue-500'],
            'PT'  => ['bg' => 'bg-violet-500',  'light' => 'bg-violet-50 dark:bg-violet-500/10',   'text' => 'text-violet-700 dark:text-violet-400',   'border' => 'border-violet-200 dark:border-violet-500/30','dot' => 'bg-violet-500'],
        ];
        $color = $levelColors[$memorization->education_level] ?? $levelColors['MTS'];
        $progress = $totalItems > 0 ? round(($checkedItems / $totalItems) * 100) : 0;
    @endphp

    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.memorization.index') }}"
                class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors group">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                <span class="text-sm font-medium">Kembali ke Riwayat Hafalan</span>
            </a>
            <div class="flex gap-2">
                <form action="{{ route('admin.memorization.destroy', $memorization->id) }}" method="POST"
                    onsubmit="return confirm('Hapus data hafalan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 text-sm font-medium hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 rounded-xl p-4 flex items-center gap-3">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">check_circle</span>
                <p class="text-sm text-emerald-700 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Header Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-6 py-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl {{ $color['bg'] }} flex items-center justify-center text-white font-bold text-lg shadow-sm flex-shrink-0">
                        {{ $memorization->education_level }}
                    </div>
                    <div class="flex-1">
                        <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $memorization->student->name }}</h1>
                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-[14px]">badge</span>
                                {{ $memorization->student->nis }}
                            </span>
                            <span class="text-slate-300 dark:text-slate-600">•</span>
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-[14px]">domain</span>
                                {{ $memorization->student->rayon->name ?? '-' }}
                            </span>
                            <span class="text-slate-300 dark:text-slate-600">•</span>
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 dark:text-slate-400">
                                <span class="material-symbols-outlined text-[14px]">meeting_room</span>
                                {{ $memorization->student->room->name ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        @if($memorization->status === 'completed')
                            <span id="status-badge" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                Selesai
                            </span>
                        @else
                            <span id="status-badge" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                                <span class="material-symbols-outlined text-[16px]">pending</span>
                                Belum Selesai
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-y sm:divide-y-0 divide-slate-100 dark:divide-slate-700">
                <div class="px-5 py-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Jenjang</p>
                    <p class="font-semibold text-slate-700 dark:text-slate-300">
                        {{ ['MTS' => 'MTs Sederajat', 'MA' => 'MA Sederajat', 'PT' => 'PT Sederajat'][$memorization->education_level] ?? $memorization->education_level }}
                    </p>
                </div>
                <div class="px-5 py-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Jumlah Hari</p>
                    <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $memorization->days }} hari</p>
                </div>
                <div class="px-5 py-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Tahun Ajaran</p>
                    <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $memorization->academicYear->name }}</p>
                </div>
                <div class="px-5 py-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Tanggal Catat</p>
                    <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $memorization->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Progress Hafalan</p>
                    <p class="text-sm font-bold text-primary" id="progress-text">{{ $checkedItems }} / {{ $totalItems }} item</p>
                </div>
                <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div id="progress-bar" class="h-full bg-primary rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                </div>
            </div>

            @if($memorization->notes)
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Catatan</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $memorization->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Checklist Items --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">checklist</span>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Daftar Item Hafalan</h2>
                <span class="ml-auto text-xs text-slate-400">Centang item yang sudah diselesaikan santri</span>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                @foreach($itemsByDay as $day => $items)
                    {{-- Day Header --}}
                    <div class="px-6 py-3 bg-slate-50 dark:bg-slate-900/40 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full {{ $color['dot'] }}"></div>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">Hari Ke-{{ $day }}</p>
                    </div>

                    {{-- Items --}}
                    @foreach($items as $item)
                        <div class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors cursor-pointer memorization-item-row"
                             data-item-id="{{ $item->id }}"
                             data-toggle-url="{{ route('admin.memorization-items.toggle', $item->id) }}">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 rounded-full border-2 {{ $item->is_checked ? 'bg-primary border-primary' : 'border-slate-300 dark:border-slate-600' }} flex items-center justify-center transition-all duration-200 item-circle">
                                    @if($item->is_checked)
                                        <span class="material-symbols-outlined text-[14px] text-white">check</span>
                                    @endif
                                </div>
                            </div>
                            <p class="flex-1 text-sm {{ $item->is_checked ? 'line-through text-slate-400 dark:text-slate-500' : 'text-slate-700 dark:text-slate-300' }} transition-all duration-200 item-text">
                                {{ $item->memorizationType->target_description }}
                            </p>
                            <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 text-slate-300">
                                <span class="material-symbols-outlined text-[18px]">touch_app</span>
                            </div>
                        </div>
                    @endforeach
                @endforeach

                @if($itemsByDay->isEmpty())
                    <div class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                        <span class="material-symbols-outlined text-4xl mb-2 text-slate-300 block">list_alt</span>
                        <p>Tidak ada item hafalan yang ditemukan untuk jenjang ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let totalItems   = {{ $totalItems }};
                let checkedItems = {{ $checkedItems }};

                function updateProgress() {
                    const pct = totalItems > 0 ? Math.round((checkedItems / totalItems) * 100) : 0;
                    $('#progress-bar').css('width', pct + '%');
                    $('#progress-text').text(checkedItems + ' / ' + totalItems + ' item');
                }

                function updateStatusBadge(status) {
                    const badge = $('#status-badge');
                    if (status === 'completed') {
                        badge.html('<span class="material-symbols-outlined text-[16px]">check_circle</span> Selesai')
                             .removeClass('bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400')
                             .addClass('bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400');
                    } else {
                        badge.html('<span class="material-symbols-outlined text-[16px]">pending</span> Belum Selesai')
                             .removeClass('bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400')
                             .addClass('bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400');
                    }
                }

                $('.memorization-item-row').on('click', function() {
                    const $row   = $(this);
                    const itemId = $row.data('item-id');
                    const url    = $row.data('toggle-url');
                    const $circle = $row.find('.item-circle');
                    const $text   = $row.find('.item-text');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            if (res.is_checked) {
                                checkedItems++;
                                $circle.addClass('bg-primary border-primary').html('<span class="material-symbols-outlined text-[14px] text-white">check</span>');
                                $text.addClass('line-through text-slate-400 dark:text-slate-500').removeClass('text-slate-700 dark:text-slate-300');
                            } else {
                                checkedItems--;
                                $circle.removeClass('bg-primary border-primary').html('');
                                $text.removeClass('line-through text-slate-400 dark:text-slate-500').addClass('text-slate-700 dark:text-slate-300');
                            }
                            updateProgress();
                            updateStatusBadge(res.status);
                        },
                        error: function() {
                            alert('Gagal memperbarui. Silakan coba lagi.');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
