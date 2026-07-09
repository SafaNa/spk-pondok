@extends('layouts.app')

@section('title', 'Detail Santri')
@section('breadcrumb', 'Detail')
@section('breadcrumb_parent', 'Santri')
@section('breadcrumb_parent_route', 'students.index')
@section('mobile_title', 'Detail Santri')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('admin.students.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Data Santri</span>
        </a>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">

            {{-- Header --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-t-3xl px-6 py-6 sm:px-8 sm:py-8 border-b border-blue-200 dark:border-blue-800">
                <div class="flex flex-col sm:flex-row items-start justify-between gap-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                        {{-- Foto / Avatar --}}
                        @php
                            $initials = strtoupper(substr($student->name, 0, 1) . (str_contains($student->name, ' ') ? substr($student->name, strpos($student->name, ' ') + 1, 1) : substr($student->name, 1, 1)));
                        @endphp
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}"
                                class="h-20 w-20 rounded-2xl object-cover ring-4 ring-white dark:ring-slate-700 shadow-md">
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-primary/10 text-primary text-2xl font-bold shadow-sm border border-primary/20">
                                {{ $initials }}
                            </div>
                        @endif
                        <div>
                            <h1 class="text-2xl font-bold mb-1 text-slate-900 dark:text-white tracking-tight">{{ $student->name }}</h1>
                            <p class="text-slate-500 text-sm">NIS: {{ $student->nis }}</p>
                            <div class="mt-2">
                                @if($student->status == 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Aktif</span>
                                @elseif($student->status == 'inactive')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">Nonaktif</span>
                                @elseif($student->status == 'graduated')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Lulus</span>
                                @elseif($student->status == 'dropped_out')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">Keluar</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.students.edit', $student->id) }}"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                            Ubah
                        </a>
                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST">
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
            <div class="p-6 sm:p-10 flex flex-col gap-8">

                {{-- SECTION: Data Identitas --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">person</span>
                        Data Identitas
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">id_card</span> NIS
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->nis }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">badge</span> NIK
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->nik ?? '-' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">{{ $student->gender == 'male' ? 'male' : 'female' }}</span> Jenis Kelamin
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">cake</span> Tempat, Tanggal Lahir
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">
                                {{ $student->birth_place }}, {{ $student->birth_date ? $student->birth_date->isoFormat('D MMMM Y') : '-' }}
                            </div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">phone_iphone</span> No. HP / Kontak
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->phone ?? '-' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">login</span> Tanggal Masuk
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">
                                {{ $student->entry_date ? $student->entry_date->isoFormat('D MMMM Y') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                {{-- SECTION: Tempat Tinggal --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">home</span>
                        Tempat Tinggal
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">domain</span> Rayon
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->rayon?->name ?? '-' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">meeting_room</span> Kamar
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->room?->name ?? '-' }}</div>
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">location_on</span> Alamat
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">
                                {{ $student->address ?? '-' }}
                                @if($student->village && $student->district && $student->city && $student->province)
                                    <br><span class="text-sm text-slate-500">
                                        {{ $student->village->name }}, {{ $student->district->name }}, {{ $student->city->name }}, {{ $student->province->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                {{-- SECTION: Pendidikan --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">school</span>
                        Pendidikan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">menu_book</span> Pendidikan Formal
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->formalEducation?->name ?? '-' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">auto_stories</span> Pendidikan Diniyah
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->religiousEducation?->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                {{-- SECTION: Data Orang Tua --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">supervisor_account</span>
                        Data Orang Tua
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Ayah --}}
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 space-y-3">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Ayah</p>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Nama</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->father_name ?? '-' }}</div>
                            </div>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Pendidikan Terakhir</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->father_education ?? '-' }}</div>
                            </div>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Pekerjaan</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->father_occupation ?? '-' }}</div>
                            </div>
                        </div>

                        {{-- Ibu --}}
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 space-y-3">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Ibu</p>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Nama</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->mother_name ?? '-' }}</div>
                            </div>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Pendidikan Terakhir</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->mother_education ?? '-' }}</div>
                            </div>
                            <div class="space-y-1">
                                <div class="text-slate-500 text-xs font-semibold">Pekerjaan</div>
                                <div class="text-slate-900 dark:text-white font-medium text-sm">{{ $student->mother_occupation ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                {{-- SECTION: Sistem --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">info</span>
                        Informasi Sistem
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">calendar_add_on</span> Tanggal Didaftarkan
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->created_at->isoFormat('D MMMM Y, H:mm') }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">update</span> Terakhir Diperbarui
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $student->updated_at->isoFormat('D MMMM Y, H:mm') }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
