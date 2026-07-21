@extends('layouts.app')

@section('title', 'Dashboard')
@section('mobile_title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="rounded-2xl p-5 sm:p-6 mb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-4" style="background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 60%, #ede9fe 100%); border: 1px solid #bfdbfe;">
        <div>
            <h1 class="text-[#1e3a5f] text-lg sm:text-xl font-black tracking-tight mb-1">Dashboard Pengurus Perizinan</h1>
            <p class="text-[#3b5f8a] text-sm font-normal max-w-2xl">
                Kelola seluruh sistem validasi izin dan kepulangan santri secara terpusat, monitor proses persetujuan lintas departemen, serta atur hak akses pengguna.
            </p>
        </div>
        <form method="GET" action="{{ route('admin.dashboard') }}" class="shrink-0">
            <select name="academic_year_id" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-sm font-semibold text-[#0d141b] bg-white border border-[#bfdbfe] rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-sm">
                @foreach($allAcademicYears as $year)
                    <option value="{{ $year->id }}" {{ $activeYear->id === $year->id ? 'selected' : '' }}>
                        Tahun Ajaran {{ $year->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">

        {{-- Jumlah Santri --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-blue-500 shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <span class="material-symbols-outlined text-[20px]">groups</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($totalStudents) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Jumlah Santri</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Total Santri Aktif</p>
            </div>
        </div>

        {{-- Kepulangan --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-indigo-500 shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <span class="material-symbols-outlined text-[20px]">home</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($kepulangan) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Izin Berjalan</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Hari ini</p>
            </div>
        </div>

        {{-- Disetujui --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-emerald-500 shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($izinDisetujui) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Izin Disetujui</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Telah disetujui</p>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-amber-500 shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <span class="material-symbols-outlined text-[20px]">schedule</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($izinPending) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Izin Dipending</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Menunggu Validasi</p>
            </div>
        </div>

        {{-- Ditolak --}}
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-red-500 shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                <span class="material-symbols-outlined text-[20px]">cancel</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($izinDitolak) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Izin Ditolak</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Telah ditolak</p>
            </div>
        </div>

        {{-- Kasus Darurat --}}
        <div class="bg-red-50 rounded-xl border border-red-200 border-l-4 border-l-red-600 shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600">
                <span class="material-symbols-outlined text-[20px]">warning</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-red-900 leading-none">{{ number_format($kasusDarurat) }}</p>
                <p class="text-[11px] font-semibold text-red-900 leading-tight mt-0.5">Kasus Darurat</p>
                <p class="text-[10px] text-red-700 leading-tight">Butuh respon cepat</p>
            </div>
        </div>
    </div>

    {{-- Perpanjangan Izin KPI Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-blue-500 shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <span class="material-symbols-outlined text-[20px]">assignment</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($extTotal) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Total Perpanjangan</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Semua Pengajuan</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-emerald-500 shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($extApproved) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Perpanjangan Disetujui</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Telah disetujui</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-amber-500 shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <span class="material-symbols-outlined text-[20px]">schedule</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($extPending) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Perpanjangan Pending</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Menunggu Validasi</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#e7edf3] border-l-4 border-l-red-500 shadow-sm p-3 flex items-center gap-3 hover:shadow-md transition-shadow">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                <span class="material-symbols-outlined text-[20px]">cancel</span>
            </div>
            <div class="min-w-0">
                <p class="text-lg font-black text-[#0d141b] leading-none">{{ number_format($extRejected) }}</p>
                <p class="text-[11px] font-semibold text-[#0d141b] leading-tight mt-0.5">Perpanjangan Ditolak</p>
                <p class="text-[10px] text-[#4c739a] leading-tight">Telah ditolak</p>
            </div>
        </div>
    </div>

    {{-- Chart Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Top 10 Santri Paling Banyak Izin</h3>
            <div id="chartTopLicenses" class="min-h-[300px]"></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Top 10 Santri Paling Banyak Melanggar</h3>
            <div id="chartTopStudentViolations" class="min-h-[300px]"></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Tren Pengajuan Izin ({{ $activeYear->name }})</h3>
            <div id="chartLicenseTrend" class="min-h-[300px]"></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Tren Pelanggaran ({{ $activeYear->name }})</h3>
            <div id="chartViolationTrend" class="min-h-[300px]"></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Kategori Pelanggaran</h3>
            <div id="chartViolationCat" class="min-h-[300px]"></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Top 5 Rayon Pelanggaran Terbanyak</h3>
            <div id="chartTopRayons" class="min-h-[300px]"></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[#e7edf3] p-5 lg:col-span-2">
            <h3 class="text-sm font-bold text-[#0d141b] mb-4">Sebaran Santri per Rayon</h3>
            <div id="chartDemographics" class="min-h-[300px]"></div>
        </div>
    </div>

    {{-- Bottom Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Pengajuan Izin Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-[#e7edf3] overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-[#e7edf3]">
                <h3 class="text-sm font-bold text-[#0d141b]">Pengajuan Izin Terbaru</h3>
                <a href="{{ route('admin.licenses.index') }}"
                    class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                    Lihat Semua
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[#f8fafc] border-b border-[#e7edf3]">
                            <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Nama Santri</th>
                            <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Alasan</th>
                            <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase whitespace-nowrap">Tanggal Pengajuan</th>
                            <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e7edf3]">
                        @forelse($recentLicenses as $license)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3 text-sm font-semibold text-[#0d141b] whitespace-nowrap">{{ $license->student?->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm text-[#4c739a] max-w-[160px] truncate">{{ $license->description ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm text-[#4c739a] whitespace-nowrap">{{ $license->created_at->format('d M Y H.i') }}</td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    @if($license->is_emergency && $license->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-violet-100 text-violet-700 border border-violet-200">Darurat</span>
                                    @elseif($license->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">Dipending</span>
                                    @elseif($license->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Disetujui</span>
                                    @elseif($license->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-pink-100 text-pink-700 border border-pink-200">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-[#4c739a]">
                                    <span class="material-symbols-outlined text-3xl block mb-2 text-slate-300">assignment</span>
                                    Belum ada pengajuan izin.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Peringatan & Notifikasi --}}
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-[#e7edf3] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7edf3]">
                <h3 class="text-sm font-bold text-[#0d141b]">Peringatan & Notifikasi</h3>
            </div>
            <ul class="divide-y divide-[#e7edf3]">

                {{-- Poin kepulangan hampir habis --}}
                @foreach($quotaWarnings as $warn)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="material-symbols-outlined text-[20px] text-amber-500 shrink-0">notifications</span>
                        <span class="text-sm text-[#0d141b]">
                            <span class="font-semibold">{{ $warn->name }}</span>
                            <span class="text-[#4c739a]"> - Poin Kepulangan Hampir Habis ({{ $warn->used_count }}/{{ $warn->max_leaves }})</span>
                        </span>
                    </li>
                @endforeach

                {{-- Kasus darurat per santri --}}
                @foreach($recentLicenses->where('is_emergency', true)->where('status', 'pending')->take(3) as $darurat)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="material-symbols-outlined text-[20px] text-red-500 shrink-0">emergency</span>
                        <span class="text-sm text-[#0d141b]">
                            <span class="font-semibold">{{ $darurat->student?->name ?? '-' }}</span>
                            <span class="text-[#4c739a]"> - Pengajuan Darurat ({{ $darurat->description ?? 'Darurat' }})</span>
                        </span>
                    </li>
                @endforeach

                {{-- Santri dengan pelanggaran aktif --}}
                @foreach($violationNotifs as $student)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="material-symbols-outlined text-[20px] text-red-500 shrink-0">report</span>
                        <span class="text-sm text-[#0d141b]">
                            <span class="font-semibold">{{ $student->name }}</span>
                            <span class="text-[#4c739a]"> - Memiliki Pelanggaran Aktif</span>
                        </span>
                    </li>
                @endforeach

                {{-- Total izin pending --}}
                @if($izinPending > 0)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="material-symbols-outlined text-[20px] text-amber-500 shrink-0">warning</span>
                        <span class="text-sm text-[#0d141b]">
                            <span class="font-semibold">{{ $izinPending }}</span>
                            <span class="text-[#4c739a]"> Pengajuan Izin Menunggu Validasi</span>
                        </span>
                    </li>
                @endif

                @if($quotaWarnings->isEmpty() && $recentLicenses->where('is_emergency', true)->where('status', 'pending')->isEmpty() && $violationNotifs->isEmpty() && $izinPending === 0)
                    <li class="flex flex-col items-center justify-center px-5 py-10 text-center">
                        <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">notifications_off</span>
                        <p class="text-sm text-[#4c739a]">Tidak ada peringatan saat ini.</p>
                    </li>
                @endif

            </ul>

            @if($izinPending > 0 || $kasusDarurat > 0)
                <div class="px-5 py-3 border-t border-[#e7edf3]">
                    <a href="{{ route('admin.licenses.index') }}"
                        class="flex items-center justify-center gap-2 w-full py-2 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary text-sm font-semibold transition-colors">
                        <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                        Proses Validasi Sekarang
                    </a>
                </div>
            @endif
        </div>



@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartData = @json($chartData ?? []);
        if (Object.keys(chartData).length === 0) return;
        
        // Common Options
        const commonOptions = {
            chart: {
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f97316', '#64748b'],
            dataLabels: { enabled: false },
            tooltip: { theme: 'light' }
        };



        // 2. Tren Pengajuan Izin (Area)
        if (document.querySelector("#chartLicenseTrend")) {
            new ApexCharts(document.querySelector("#chartLicenseTrend"), {
                ...commonOptions,
                chart: { type: 'area', height: 320 },
                series: [{ name: 'Jumlah Izin', data: chartData.licenseTrend.series }],
                xaxis: { categories: chartData.licenseTrend.labels },
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#0ea5e9'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } }
            }).render();
        }

        // 3. Top Santri Izin (Bar)
        if (document.querySelector("#chartTopLicenses")) {
            new ApexCharts(document.querySelector("#chartTopLicenses"), {
                ...commonOptions,
                chart: { type: 'bar', height: 320 },
                series: [{ name: 'Total Izin', data: chartData.topStudentLicenses.series }],
                xaxis: { categories: chartData.topStudentLicenses.labels },
                colors: ['#0ea5e9'],
                plotOptions: { bar: { borderRadius: 4, horizontal: true } }
            }).render();
        }
        
        // Top Santri Melanggar (Bar)
        if (document.querySelector("#chartTopStudentViolations")) {
            new ApexCharts(document.querySelector("#chartTopStudentViolations"), {
                ...commonOptions,
                chart: { type: 'bar', height: 320 },
                series: [{ name: 'Total Pelanggaran', data: chartData.topStudentViolations.series }],
                xaxis: { categories: chartData.topStudentViolations.labels },
                colors: ['#ef4444'],
                plotOptions: { bar: { borderRadius: 4, horizontal: true } }
            }).render();
        }

        // 4. Kategori Pelanggaran (Doughnut)
        if (document.querySelector("#chartViolationCat")) {
            new ApexCharts(document.querySelector("#chartViolationCat"), {
                ...commonOptions,
                chart: { type: 'donut', height: 320 },
                series: chartData.violationCat.series,
                labels: chartData.violationCat.labels,
                colors: ['#3b82f6', '#f59e0b', '#ef4444'],
            }).render();
        }

        // 5. Tren Pelanggaran (Area)
        if (document.querySelector("#chartViolationTrend")) {
            new ApexCharts(document.querySelector("#chartViolationTrend"), {
                ...commonOptions,
                chart: { type: 'area', height: 320 },
                series: [{ name: 'Pelanggaran', data: chartData.violationTrend.series }],
                xaxis: { categories: chartData.violationTrend.labels },
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#ef4444'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } }
            }).render();
        }

        // 6. Top 5 Rayon Pelanggaran (Horizontal Bar)
        if (document.querySelector("#chartTopRayons")) {
            new ApexCharts(document.querySelector("#chartTopRayons"), {
                ...commonOptions,
                chart: { type: 'bar', height: 320 },
                series: [{ name: 'Pelanggaran', data: chartData.topRayons.series }],
                xaxis: { categories: chartData.topRayons.labels },
                colors: ['#f43f5e'],
                plotOptions: { bar: { borderRadius: 4, horizontal: true } }
            }).render();
        }



        // 8. Sebaran Santri per Rayon (Bar)
        if (document.querySelector("#chartDemographics")) {
            new ApexCharts(document.querySelector("#chartDemographics"), {
                ...commonOptions,
                chart: { type: 'bar', height: 320 },
                series: [{ name: 'Santri Aktif', data: chartData.demographics.series }],
                xaxis: { categories: chartData.demographics.labels },
                colors: ['#0ea5e9'],
                plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } }
            }).render();
        }
    });
</script>
@endpush
