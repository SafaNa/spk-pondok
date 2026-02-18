@extends('layouts.app')

@section('title', 'Pembayaran SPP Pondok')
@section('breadcrumb', 'Pembayaran SPP')

@section('content')
    <div class="flex flex-col gap-6">
        {{-- Header --}}
        <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
            style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                <div class="flex flex-col gap-2">
                    <h2 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Pembayaran SPP Pondok</h2>
                    <p class="text-[#4c739a] text-sm sm:text-base font-normal">Kelola data pembayaran dan tagihan SPP santri
                    </p>
                    @if(isset($currentContextYear) && $currentContextYear->spp_amount > 0)
                        <div
                            class="flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800 self-start">
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 tracking-wide">SPP Tahun Ajaran
                                {{ $currentContextYear->name }}</span>
                            <span class="text-sm font-black text-blue-700 dark:text-blue-300">Rp
                                {{ number_format($currentContextYear->spp_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- Filter Tahun Ajaran --}}
                    <form action="{{ route('spp-payments.index') }}" method="GET">
                        <div class="relative">
                            <select name="academic_year_id" onchange="this.form.submit()" style="background-image: none;"
                                class="h-11 appearance-none pl-4 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-bold text-slate-600 dark:text-slate-300 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all cursor-pointer">
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                        T.A. {{ $year->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-500">
                                <span class="material-symbols-outlined text-[20px]">filter_list</span>
                            </div>
                        </div>
                    </form>

                    <a href="{{ route('spp-payments.create') }}"
                        class="group flex items-center justify-center gap-2 rounded-xl px-5 h-11 bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-lg hover:shadow-xl hover:shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-200">
                        <span
                            class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform duration-300">add</span>
                        <span>Catat Pembayaran</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-700 overflow-hidden">
            <div
                class="px-6 py-4 border-b border-[#e7edf3] dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="font-semibold text-[#0d141b] dark:text-white">Riwayat Pembayaran</h3>
                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-[#4c739a]">
                    {{ $payments->total() }} Transaksi
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/50 dark:bg-slate-800/20 text-xs uppercase tracking-wider text-[#4c739a] border-b border-[#e7edf3] dark:border-slate-700">
                            <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                            <th class="px-6 py-4 font-semibold">Santri</th>
                            <th class="px-6 py-4 font-semibold">Tahun Ajaran</th>
                            <th class="px-6 py-4 font-semibold text-center">Tahap</th>
                            <th class="px-6 py-4 font-semibold">Tanggal Bayar</th>
                            <th class="px-6 py-4 font-semibold">Batas Waktu</th>
                            <th class="px-6 py-4 font-semibold">Nominal</th>
                            <th class="px-6 py-4 font-semibold text-red-500">Denda</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                        @forelse($payments as $payment)
                            @php
                                $initials = strtoupper(substr($payment->student->name, 0, 1) . (str_contains($payment->student->name, ' ') ? substr($payment->student->name, strpos($payment->student->name, ' ') + 1, 1) : substr($payment->student->name, 1, 1)));
                                $colors = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                                $colorIndex = crc32($payment->student->id) % count($colors);
                                $color = $colors[$colorIndex];
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                <td class="px-6 py-4 text-center text-[#4c739a]">
                                    {{ $loop->iteration + $payments->firstItem() - 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($payment->student->photo)
                                            <button
                                                @click="$store.imageModal.open('{{ asset('storage/' . $payment->student->photo) }}', '{{ $payment->student->name }}')"
                                                class="shrink-0 focus:outline-none focus:ring-2 focus:ring-primary rounded-full">
                                                <img src="{{ asset('storage/' . $payment->student->photo) }}"
                                                    alt="{{ $payment->student->name }}"
                                                    class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-slate-800 hover:scale-110 transition-transform cursor-zoom-in">
                                            </button>
                                        @else
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 font-bold text-xs ring-1 ring-{{ $color }}-600/20">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-gray-800 dark:text-white">
                                                {{ $payment->student->name }}
                                            </div>
                                            <div class="text-xs text-[#4c739a]">{{ $payment->student->rayon?->name }} -
                                                {{ $payment->student->room?->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#4c739a]">{{ $payment->academicYear->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">
                                        Tahap {{ $payment->stage ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[#4c739a]">
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->isoFormat('D MMMM Y') }}
                                </td>
                                <td class="px-6 py-4 text-[#4c739a]">
                                    @if($payment->deadline)
                                        {{ \Carbon\Carbon::parse($payment->deadline)->isoFormat('D MMM Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-semibold text-[#0d141b] dark:text-white">Rp
                                    {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-red-500">
                                    @if($payment->late_fee > 0)
                                        Rp {{ number_format($payment->late_fee, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($payment->status == 'paid')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">Lunas</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-100">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div
                                        class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('spp-payments.edit', $payment->id) }}"
                                            class="p-2 text-[#4c739a] hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-colors"
                                            title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="{{ route('spp-payments.destroy', $payment->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                @click.prevent="$store.deleteModal.open($el.closest('form'), 'Hapus pembayaran ini?')"
                                                class="p-2 text-[#4c739a] hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors"
                                                title="Hapus">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-[#4c739a]">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                            <span class="material-symbols-outlined text-3xl opacity-50">payments</span>
                                        </div>
                                        <p class="font-medium text-lg text-[#0d141b] dark:text-white mb-1">Belum ada data
                                            pembayaran</p>
                                        <p class="text-sm mb-4">Silakan catat pembayaran baru untuk memulai</p>
                                        <a href="{{ route('spp-payments.create') }}"
                                            class="text-primary hover:underline text-sm font-medium">Tambah Pembayaran</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-[#e7edf3] dark:border-slate-700">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
@endsection