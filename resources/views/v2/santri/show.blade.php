@extends('layouts.app')

@section('title', 'Detail Santri')
@section('breadcrumb', 'Santri / Detail')
@section('mobile_title', 'Detail Santri')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('santri.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Data Santri</span>
        </a>

        {{-- Main Card --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            {{-- Header --}}
            <div class="bg-blue-50 rounded-t-3xl px-6 py-6 sm:px-8 sm:py-8 border-b border-blue-200">
                <div class="flex flex-col sm:flex-row items-start justify-between gap-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                        <div
                            class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white shadow-sm border border-primary/20 text-primary">
                            <span class="material-symbols-outlined text-[32px]">person</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold mb-2 text-slate-900 tracking-tight">Detail Data Santri</h1>
                            <p class="text-slate-500 text-base max-w-xl">Informasi lengkap tentang biodata santri dan
                                riwayat penilaian</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('santri.edit', $santri->id) }}"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                            Edit
                        </a>
                        <form action="{{ route('santri.destroy', $santri->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus santri {{ $santri->nama }}?')" type="button"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6 sm:p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NIS --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">id_card</span>
                            NIS
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">{{ $santri->nis }}</div>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">badge</span>
                            Nama Lengkap
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">{{ $santri->nama }}</div>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">
                                {{ $santri->jenis_kelamin == 'L' ? 'male' : 'female' }}
                            </span>
                            Jenis Kelamin
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">
                            {{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">verified</span>
                            Status
                        </div>
                        <div>
                            @if ($santri->status == 'aktif')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Aktif</span>
                            @elseif($santri->status == 'non-aktif')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Non-Aktif</span>
                            @elseif($santri->status == 'lulus')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Lulus</span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">{{ ucfirst($santri->status) }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Tempat, Tanggal Lahir --}}
                    <div class="space-y-2 md:col-span-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">cake</span>
                            Tempat, Tanggal Lahir
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">{{ $santri->tempat_lahir }},
                            {{ \Carbon\Carbon::parse($santri->tanggal_lahir)->isoFormat('D MMMM Y') }}</div>
                    </div>

                    {{-- Alamat --}}
                    <div class="space-y-2 md:col-span-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">home</span>
                            Alamat
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">{{ $santri->alamat }}</div>
                    </div>

                    {{-- Nama Orang Tua --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">supervisor_account</span>
                            Nama Orang Tua/Wali
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">{{ $santri->nama_ortu }}</div>
                    </div>

                    {{-- No HP Orang Tua --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">phone_iphone</span>
                            No. HP Orang Tua/Wali
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">{{ $santri->no_hp_ortu }}</div>
                    </div>

                    {{-- Tanggal Didaftarkan --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">calendar_add_on</span>
                            Tanggal Didaftarkan
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">
                            {{ $santri->created_at->isoFormat('D MMMM Y H:mm') }}</div>
                    </div>

                    {{-- Terakhir Diperbarui --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">update</span>
                            Terakhir Diperbarui
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">
                            {{ $santri->updated_at->isoFormat('D MMMM Y H:mm') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Penilaian --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            <div class="bg-blue-50 px-6 py-6 border-b border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Riwayat Penilaian</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            @if($periodeAktif)
                                Penilaian periode: <span class="font-semibold text-primary">{{ $periodeAktif->nama }}</span>
                            @else
                                Belum ada periode aktif
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if ($santri->penilaian->isEmpty())
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-[64px] text-slate-300">description</span>
                        <h3 class="mt-4 text-lg font-medium text-slate-900">Belum ada penilaian</h3>
                        <p class="mt-2 text-sm text-slate-500">Mulai dengan menambahkan penilaian baru untuk santri ini.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('penilaian.create', ['santri_id' => $santri->id]) }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-lg hover:shadow-xl transition-all">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                                Tambah Penilaian
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="pb-3 text-xs font-semibold tracking-wide text-slate-500 uppercase">Kriteria
                                    </th>
                                    <th class="pb-3 text-xs font-semibold tracking-wide text-slate-500 uppercase">
                                        Subkriteria</th>
                                    <th class="pb-3 text-xs font-semibold tracking-wide text-slate-500 uppercase">Nilai</th>
                                    <th class="pb-3 text-xs font-semibold tracking-wide text-slate-500 uppercase">Tanggal
                                    </th>
                                    <th class="pb-3 text-xs font-semibold tracking-wide text-slate-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($santri->penilaian as $penilaian)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-4 text-sm font-medium text-slate-900">
                                            {{ $penilaian->kriteria->nama_kriteria }}</td>
                                        <td class="py-4 text-sm text-slate-500">
                                            {{ $penilaian->subkriteria ? $penilaian->subkriteria->nama_subkriteria : '-' }}
                                        </td>
                                        <td class="py-4 text-sm text-slate-500">{{ $penilaian->nilai }}</td>
                                        <td class="py-4 text-sm text-slate-500">
                                            {{ $penilaian->created_at->isoFormat('D MMMM Y') }}</td>
                                        <td class="py-4 text-sm">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('penilaian.edit', $penilaian->id) }}"
                                                    class="text-primary hover:text-primary/80 font-medium">Edit</a>
                                                <form action="{{ route('penilaian.destroy', $penilaian->id) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus penilaian ini?')" type="button"
                                                        class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
