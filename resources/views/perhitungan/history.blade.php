@extends('layouts.app')

@section('title', 'Riwayat Perhitungan')

@section('content')
    <div class="space-y-8">
        <!-- Page Header & Filter -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md::justify-between gap-4 mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">
                        Riwayat Perhitungan
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Arsip hasil perhitungan SPK Pesantren
                    </p>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <form action="{{ route('perhitungan.history') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    <!-- Filter Periode -->
                    <div class="md:col-span-4 lg:col-span-3">
                        <label for="periode_filter" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                        <select id="periode_filter" name="periode_filter"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[var(--color-primary-500)] focus:ring-[var(--color-primary-500)] sm:text-sm py-2.5">
                            @foreach ($allPeriodes as $p)
                                <option value="{{ $p->id }}" {{ request('periode_filter') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="md:col-span-6 lg:col-span-7">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Santri</label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="block w-full rounded-lg border-gray-300 pl-10 focus:border-[var(--color-primary-500)] focus:ring-[var(--color-primary-500)] sm:text-sm py-2.5"
                                placeholder="Nama atau NIS santri...">
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="md:col-span-2 flex items-center gap-2">
                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors shadow-sm">
                            Filter
                        </button>
                        @if(request()->has('periode_filter') || request()->has('search'))
                            <a href="{{ route('perhitungan.history') }}"
                                class="inline-flex justify-center items-center px-3 py-2.5 border border-gray-300 rounded-lg text-gray-500 bg-white hover:text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors"
                                title="Reset Filter">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- content list -->
        <div class="space-y-10">
            @if($periodesPaginated->isEmpty())
                <div class="bg-white shadow-sm rounded-lg p-12 text-center border border-dashed border-gray-300">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-50 mb-4">
                        <i class="fas fa-history text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak ada riwayat ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-500">Coba sesuaikan filter atau lakukan perhitungan baru.</p>
                </div>
            @else
                @foreach($periodesPaginated as $periode)
                    @php $items = $periode->riwayatHitung; @endphp
                    @if($items->isNotEmpty())
                        <div>
                            <!-- Section Header Style -->
                            <div class="flex items-center justify-between mb-4 border-l-4 border-[var(--color-primary-600)] pl-4">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900">
                                        {{ $periode->nama }}
                                    </h4>
                                    <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">
                                        {{ $items->count() }} Data Santri
                                    </span>
                                </div>
                            </div>

                            <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50/50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">
                                                    No</th>
                                                <th scope="col"
                                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                    Santri</th>
                                                <th scope="col"
                                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                    Nilai Akhir</th>
                                                <th scope="col"
                                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                    Tanggal Hitung</th>
                                                <th scope="col" class="relative px-6 py-4">
                                                    <span class="sr-only">Detail</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($items as $index => $item)
                                                <tr class="hover:bg-gray-50 transition-colors group">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div
                                                                class="h-8 w-8 rounded-full bg-[var(--color-primary-100)] flex items-center justify-center text-[var(--color-primary-600)] font-bold text-[10px] mr-3">
                                                                {{ collect(explode(' ', $item->santri->nama))->map(fn($word) => substr($word, 0, 1))->take(2)->implode('') }}
                                                            </div>
                                                            <div>
                                                                <div
                                                                    class="text-sm font-semibold text-gray-900 group-hover:text-[var(--color-primary-600)] transition-colors">
                                                                    {{ $item->santri->nama }}
                                                                </div>
                                                                <div class="text-xs text-gray-500">
                                                                    NIS: {{ $item->santri->nis }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold {{ $item->nilai_akhir >= 0.7 ? 'bg-green-100 text-green-700' : ($item->nilai_akhir >= 0.4 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                                            {{ number_format($item->nilai_akhir, 3) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <div class="flex items-center text-xs">
                                                            <i class="far fa-clock mr-1.5 text-gray-400"></i>
                                                            {{ $item->created_at->format('d M Y, H:i') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('perhitungan.hasil', ['santri' => $item->santri_id, 'periode_id' => $item->periode_id]) }}"
                                                            class="text-gray-400 hover:text-[var(--color-primary-600)] transition-colors">
                                                            <i class="fas fa-chevron-right"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            <!-- Pagination -->
            @if($periodesPaginated->hasPages())
                <div class="mt-6">
                    {{ $periodesPaginated->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection