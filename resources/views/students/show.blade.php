@extends('layouts.app')

@section('title', 'Detail Santri')
@section('breadcrumb', 'Detail')
@section('breadcrumb_parent', 'Santri')
@section('breadcrumb_parent_route', 'students.index')
@section('mobile_title', 'Detail Santri')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('students.index') }}"
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
                            <p class="text-slate-500 text-base max-w-xl">Informasi lengkap tentang biodata santri</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('students.edit', $student->id) }}"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                            Ubah
                        </a>
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button @click.prevent="$store.deleteModal.open($el.closest('form'), 'Yakin ingin menghapus santri {{ $student->name }}?')" type="button"
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
                        <div class="text-slate-900 dark:text-white font-medium">{{ $student->nis }}</div>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">badge</span>
                            Nama Lengkap
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">{{ $student->name }}</div>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">
                                {{ $student->gender == 'male' ? 'male' : 'female' }}
                            </span>
                            Jenis Kelamin
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">
                            {{ $student->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </div>

                    {{-- Status --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">verified</span>
                            Status
                        </div>
                        <div>
                            @if ($student->status == 'active')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Aktif</span>
                            @elseif($student->status == 'inactive')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Nonaktif</span>
                            @elseif($student->status == 'graduated')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Lulus</span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">{{ ucfirst($student->status) }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Tempat, Tanggal Lahir --}}
                    <div class="space-y-2 md:col-span-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">cake</span>
                            Tempat, Tanggal Lahir
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">{{ $student->birth_place }},
                            {{ $student->birth_date ? $student->birth_date->isoFormat('D MMMM Y') : '-' }}</div>
                    </div>

                    {{-- Alamat --}}
                    <div class="space-y-2 md:col-span-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">home</span>
                            Alamat
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">
                            {{ $student->address ?? '-' }}
                            @if($student->village && $student->district && $student->city && $student->province)
                                <br>
                                <span class="text-sm text-slate-500">
                                    {{ $student->village->name }}, {{ $student->district->name }}, {{ $student->city->name }}, {{ $student->province->name }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Nama Orang Tua --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">supervisor_account</span>
                            Nama Orang Tua/Wali
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">{{ $student->father_name ?? '-' }}</div>
                    </div>

                    {{-- No HP Orang Tua --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">phone_iphone</span>
                            No. HP Orang Tua/Wali
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">{{ $student->phone ?? '-' }}</div>
                    </div>

                    {{-- Tanggal Didaftarkan --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">calendar_add_on</span>
                            Tanggal Didaftarkan
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">
                            {{ $student->created_at->isoFormat('D MMMM Y H:mm') }}</div>
                    </div>

                    {{-- Terakhir Diperbarui --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-slate-500 text-sm font-semibold">
                            <span class="material-symbols-outlined text-[18px]">update</span>
                            Terakhir Diperbarui
                        </div>
                        <div class="text-slate-900 dark:text-white font-medium">
                            {{ $student->updated_at->isoFormat('D MMMM Y H:mm') }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
