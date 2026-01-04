@extends('layouts.app')

@section('title', 'Analisis Sensitivitas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="glass-card rounded-2xl p-6 shadow-xl">
        <h1 class="text-2xl font-bold text-gray-900 bg-clip-text text-transparent bg-gradient-to-r from-[var(--gradient-from)] to-[var(--gradient-to)]">
            Analisis Sensitivitas
        </h1>
        <p class="mt-2 text-gray-600">
            Simulasi perubahan bobot kriteria untuk menguji stabilitas hasil keputusan (SAW).
            Periode Aktif: <span class="font-semibold text-[var(--color-primary-600)]">{{ $periode->nama ?? 'Tidak ada' }}</span>
        </p>
    </div>

    @if(!$periode)
        <div class="rounded-md bg-yellow-50 p-4 border border-yellow-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Tidak Ada Periode Aktif</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Silakan aktifkan periode penilaian terlebih dahulu untuk melakukan analisis.</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Simulation Form -->
        <div class="glass-card rounded-2xl p-6 shadow-xl" x-data="{ 
            weights: { 
                @foreach($kriteria as $k) 
                    '{{ $k->id }}': {{ $simulatedWeights[$k->id] ?? $k->bobot }}, 
                @endforeach 
            },
            get totalWeight() {
                return Object.values(this.weights).reduce((a, b) => parseInt(a) + parseInt(b), 0);
            },
            getEffective(id) {
                let w = this.weights[id];
                let total = this.totalWeight;
                return total > 0 ? ((w / total) * 100).toFixed(2) : 0;
            }
        }">
            <form action="{{ route('sensitivity.analyze') }}" method="POST">
                @csrf
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">1. Atur Bobot Simulasi</h2>
                        <p class="text-sm text-gray-500">Sistem akan otomatis menormalisasi jika total &#8800; 100%.</p>
                    </div>
                    <div class="text-sm font-medium px-4 py-3 sm:py-2 rounded-lg transition-colors duration-300 border w-full sm:w-auto"
                        :class="totalWeight == 100 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-blue-50 text-blue-700 border-blue-200'">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                            <span>Total Input: <span x-text="totalWeight" class="font-bold"></span>%</span>
                            <span x-show="totalWeight != 100" class="text-xs bg-white px-2 py-1 sm:py-0.5 rounded border border-blue-100 shadow-sm" x-transition>
                                <span class="hidden sm:inline">&#8594;</span> 
                                <span class="sm:hidden">Running</span> 
                                Dinormalisasi ke 100%
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($kriteria as $k)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:shadow-md transition-shadow duration-200 relative overflow-hidden group">
                            <!-- Background Bar for Effective Weight -->
                            <div class="absolute bottom-0 left-0 h-1 bg-[var(--color-primary-200)] transition-all duration-300"
                                 :style="'width: ' + getEffective('{{ $k->id }}') + '%'"></div>
                            
                            <!-- Header: Name + BIG Effective Weight -->
                            <div class="flex justify-between items-start mb-3">
                                <label class="block text-sm font-bold text-gray-700 w-2/3 leading-tight">{{ $k->nama_kriteria }}</label>
                                <div class="text-right">
                                    <span class="block text-2xl font-black text-[var(--color-primary-600)] leading-none" 
                                          x-text="getEffective('{{ $k->id }}') + '%'"></span>
                                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold">Normalisasi</span>
                                </div>
                            </div>
                            
                            <!-- Middle: Slider + Input Row -->
                            <div class="flex items-center space-x-4 mb-2">
                                <input type="range" 
                                    x-model.number="weights['{{ $k->id }}']" 
                                    name="weights[{{ $k->id }}]" 
                                    min="0" max="100" step="1"
                                    class="w-full h-2 rounded-lg appearance-none cursor-pointer accent-[var(--color-primary-600)] bg-gray-200"
                                    :style="'background: linear-gradient(to right, var(--color-primary-600) 0%, var(--color-primary-600) ' + weights['{{ $k->id }}'] + '%, #e5e7eb ' + weights['{{ $k->id }}'] + '%, #e5e7eb 100%)'">
                                <input type="number" 
                                    x-model.number="weights['{{ $k->id }}']" 
                                    class="w-16 h-8 text-center border-gray-300 rounded-md shadow-sm focus:ring-[var(--color-primary-500)] focus:border-[var(--color-primary-500)] text-sm font-bold text-gray-800"
                                    min="0" max="100">
                            </div>
                            
                            <!-- Footer: Original Only -->
                            <div class="flex justify-end items-center text-xs text-gray-400">
                                <span class="text-[var(--color-primary-500)]">
                                    Bobot Asli: {{ $k->bobot }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-end">
                     <button type="submit"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-[var(--color-primary-600)] hover:bg-[var(--color-primary-700)] focus:outline-none transition-all duration-200">
                        <i class="fas fa-chart-line mr-2"></i> Jalankan Simulasi
                    </button>
                </div>
            </form>
        </div>

        @if(isset($results))
            <!-- Results Section -->
            <div class="glass-card rounded-2xl p-6 shadow-xl animate-fade-in-up">
                <h2 class="text-lg font-bold text-gray-800 mb-6">2. Hasil Perbandingan Peringkat</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Santri</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rank Lama</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rank Baru</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Perubahan</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Simulasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($results as $res)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-[var(--color-primary-50)] rounded-full flex items-center justify-center text-[var(--color-primary-600)] font-bold">
                                                {{ substr($res['santri']->nama, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $res['santri']->nama }}</div>
                                                <div class="text-sm text-gray-500">{{ $res['santri']->nis }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        #{{ $res['original_rank'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">
                                        #{{ $res['new_rank'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        @if($res['rank_change'] > 0)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-arrow-up mr-1 self-center"></i> Naik {{ $res['rank_change'] }}
                                            </span>
                                        @elseif($res['rank_change'] < 0)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-arrow-down mr-1 self-center"></i> Turun {{ abs($res['rank_change']) }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                <i class="fas fa-minus mr-1 self-center"></i> Tetap
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-[var(--color-primary-600)]">
                                        {{ number_format($res['new_score'], 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
