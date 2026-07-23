@extends('layouts.app')

@section('title', 'Detail Wali Santri')
@section('breadcrumb', 'Detail')
@section('breadcrumb_parent', 'Data Wali')
@section('breadcrumb_parent_route', 'admin.guardians.index')
@section('mobile_title', 'Detail Wali')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Back Button --}}
        <a href="{{ route('admin.guardians.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Data Wali</span>
        </a>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">

            {{-- Header --}}
            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-t-3xl px-6 py-6 sm:px-8 sm:py-8 border-b border-amber-200 dark:border-amber-800">
                <div class="flex flex-col sm:flex-row items-start justify-between gap-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                        {{-- Foto / Avatar --}}
                        @php
                            $initials = strtoupper(substr($guardian->name, 0, 1) . (str_contains($guardian->name, ' ') ? substr($guardian->name, strpos($guardian->name, ' ') + 1, 1) : substr($guardian->name, 1, 1)));
                        @endphp
                        @if($guardian->avatar)
                            <img src="{{ asset('storage/' . $guardian->avatar) }}" alt="{{ $guardian->name }}"
                                class="h-20 w-20 rounded-2xl object-cover ring-4 ring-white dark:ring-slate-700 shadow-md">
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-amber-500/10 text-amber-600 text-2xl font-bold shadow-sm border border-amber-500/20">
                                {{ $initials }}
                            </div>
                        @endif
                        <div>
                            <h1 class="text-2xl font-bold mb-1 text-slate-900 dark:text-white tracking-tight">{{ $guardian->name }}</h1>
                            <p class="text-slate-500 text-sm">Username: {{ $guardian->username }}</p>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                @php
                                    $relMap = [
                                        'father' => ['label' => 'Ayah', 'color' => 'blue'],
                                        'mother' => ['label' => 'Ibu', 'color' => 'pink'],
                                        'sibling' => ['label' => 'Saudara', 'color' => 'emerald'],
                                        'uncle' => ['label' => 'Paman', 'color' => 'cyan'],
                                        'aunt' => ['label' => 'Bibi', 'color' => 'rose'],
                                        'nephew_niece' => ['label' => 'Keponakan', 'color' => 'teal'],
                                        'grandfather' => ['label' => 'Kakek', 'color' => 'slate'],
                                        'grandmother' => ['label' => 'Nenek', 'color' => 'gray'],
                                        'guardian' => ['label' => 'Wali', 'color' => 'amber']
                                    ];
                                    $rel = $relMap[$guardian->relationship] ?? ['label'=>ucfirst($guardian->relationship),'color'=>'slate'];
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                    bg-{{ $rel['color'] }}-100 text-{{ $rel['color'] }}-700 border border-{{ $rel['color'] }}-200">
                                    {{ $rel['label'] }}
                                </span>
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $guardian->students->count() }} Santri Diwalikan
                                </span>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->isAdmin())
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.guardians.edit', $guardian->id) }}"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                            Ubah Data Wali
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6 sm:p-10 flex flex-col gap-8">

                {{-- SECTION: Data Kontak --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">contact_phone</span>
                        Data Kontak & Profil
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">call</span> No. HP / WhatsApp
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $guardian->phone ?? '-' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">mail</span> Email
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $guardian->email ?? '-' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">badge</span> NIK
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $guardian->nik ?? '-' }}</div>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">work</span> Pekerjaan
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $guardian->job ?? '-' }}</div>
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold uppercase tracking-wide">
                                <span class="material-symbols-outlined text-[16px]">home_pin</span> Alamat Lengkap
                            </div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $guardian->address ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 dark:border-slate-800">

                {{-- SECTION: Santri Diwalikan --}}
                <div>
                    <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">child_care</span>
                        Daftar Santri Yang Diwalikan
                    </h2>
                    
                    @if($guardian->students->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($guardian->students as $student)
                                <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 flex flex-col gap-3">
                                    <div class="flex items-center gap-3">
                                        @if($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-lg">
                                                {{ strtoupper(substr($student->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-bold text-slate-900 dark:text-white">{{ $student->name }}</h3>
                                            <p class="text-xs text-slate-500 font-mono">{{ $student->nis }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900 p-2 rounded border border-slate-100 dark:border-slate-700">
                                        <span class="material-symbols-outlined text-[16px]">domain</span>
                                        {{ $student->rayon?->name ?? 'Belum ada rayon' }} &bull; {{ $student->room?->name ?? 'Belum ada kamar' }}
                                    </div>
                                    <a href="{{ route('admin.students.show', $student) }}" class="text-center w-full block text-xs font-bold text-primary hover:text-blue-600 bg-primary/5 hover:bg-primary/10 py-2 rounded-lg transition-colors">
                                        Lihat Profil Santri
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-8 flex flex-col items-center justify-center text-center">
                            <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">person_off</span>
                            <p class="text-slate-500 font-medium">Belum ada santri yang dihubungkan dengan wali ini.</p>
                            <a href="{{ route('admin.guardians.edit', $guardian->id) }}" class="mt-3 text-sm text-primary font-bold hover:underline">
                                Tambahkan Santri Sekarang
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
