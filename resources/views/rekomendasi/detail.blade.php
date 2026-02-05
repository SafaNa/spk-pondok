@extends('layouts.app')

@section('title', 'Detail Perhitungan - {{ $santri->nama }}')
@section('mobile_title', 'Detail SAW')
@section('breadcrumb', 'Detail Perhitungan')

@section('content')
    <!-- Page Header with Back Button -->
    <div
        class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 rounded-2xl p-6 border border-primary/20 mb-6">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="flex items-start gap-4">
                <a href="{{ route('perhitungan.hasil') }}"
                    class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-[#e7edf3] dark:border-slate-700 text-[#4c739a] hover:text-primary hover:border-primary/50 hover:shadow-md transition-all">
                    <span
                        class="material-symbols-outlined group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Detail Perhitungan SAW
                    </h1>
                    <p class="text-[#4c739a] text-base font-normal mt-1">{{ $periode->nama ?? 'Periode Aktif' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Santri Summary Card -->
    @php
        $nilai = $perhitungan['nilai_akhir'] ?? 0;
        if ($nilai >= 0.7) {
            $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
            $statusText = 'Direkomendasikan';
            $scoreColor = 'text-green-600';
        } elseif ($nilai >= 0.4) {
            $statusClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
            $statusText = 'Dipertimbangkan';
            $scoreColor = 'text-amber-600';
        } else {
            $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
            $statusText = 'Tidak Direkomendasikan';
            $scoreColor = 'text-red-600';
        }
    @endphp
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="h-16 w-16 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xl font-bold">
                    {{ strtoupper(substr($santri->nama, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-[#0d141b] dark:text-white">{{ $santri->nama }}</h2>
                    <p class="text-[#4c739a]">NIS: {{ $santri->nis }}</p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="text-sm text-[#4c739a]">Nilai Akhir</p>
                    <p class="text-3xl font-black {{ $scoreColor }}">{{ number_format($nilai, 2, ',', '.') }}</p>
                </div>
                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold {{ $statusClass }}">
                    {{ $statusText }}
                </span>
            </div>
        </div>
        @if(!empty($perhitungan['alasan']))
            <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                <p class="text-sm text-[#4c739a] mb-1">Alasan:</p>
                <p class="text-[#0d141b] dark:text-white">{{ $perhitungan['alasan'] }}</p>
            </div>
        @endif
    </div>

    <!-- Detail Perhitungan -->
    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
            <h3 class="font-semibold text-[#0d141b] dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">calculate</span>
                Rincian Perhitungan per Kriteria
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="border-b border-[#e7edf3] dark:border-slate-700 text-xs uppercase tracking-wider text-[#4c739a]">
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Kriteria</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Jenis</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Bobot</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Nilai</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Min/Max</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Normalisasi</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Preferensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @foreach($perhitungan['detail'] ?? [] as $d)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-6 py-4 font-medium text-[#0d141b] dark:text-white">{{ $d['kriteria'] }}</td>
                            <td class="px-6 py-4">
                                @if($d['jenis'] == 'benefit')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Benefit</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Cost</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-[#4c739a]">{{ number_format($d['bobot'], 0) }}%</td>
                            <td class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white">{{ $d['nilai'] }}</td>
                            <td class="px-6 py-4 text-[#4c739a]">
                                @if($d['jenis'] == 'benefit')
                                    Max: {{ $d['max'] }}
                                @else
                                    Min: {{ $d['min'] }}
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-primary">{{ number_format($d['normalisasi'], 4, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-[#0d141b] dark:text-white">
                                {{ number_format($d['total'], 4, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-primary/5 dark:bg-primary/10 border-t-2 border-primary/20">
                        <td colspan="6" class="px-6 py-4 text-right font-bold text-[#0d141b] dark:text-white">Total Nilai
                            Akhir:</td>
                        <td class="px-6 py-4 font-black text-xl {{ $scoreColor }}">{{ number_format($nilai, 4, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Formula Explanation -->
    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6 border border-[#e7edf3] dark:border-slate-700">
        <h4 class="font-semibold text-[#0d141b] dark:text-white mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">info</span>
            Keterangan Rumus SAW
        </h4>
        <div class="space-y-2 text-sm text-[#4c739a]">
            <p><strong>Normalisasi Benefit:</strong> Nilai / Max (semakin tinggi semakin baik)</p>
            <p><strong>Normalisasi Cost:</strong> Min / Nilai (semakin rendah semakin baik)</p>
            <p><strong>Preferensi:</strong> Normalisasi × Bobot Ternormalisasi</p>
            <p><strong>Nilai Akhir:</strong> Σ Preferensi semua kriteria</p>
        </div>
    </div>
@endsection