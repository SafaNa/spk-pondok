@extends('layouts.app')

@section('title', 'Data Wali')
@section('breadcrumb', 'Data Wali')
@section('mobile_title', 'Data Wali')

@section('content')

    <div class="rounded-2xl p-5 sm:p-6 border border-blue-100 mb-2"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-black text-[#0d141b] dark:text-white mb-0.5">Data Wali Santri</h1>
                <p class="text-sm text-[#4c739a]">Kelola akun wali dan hubungan mereka dengan santri.</p>
            </div>
            <a href="{{ route('admin.guardians.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md transition-all shrink-0">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                Tambah Wali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Nama Wali</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Username</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Hubungan</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">No. HP</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase text-center">Santri</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @forelse($guardians as $guardian)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    @php
                                        $initials = strtoupper(substr($guardian->name, 0, 1) . (str_contains($guardian->name, ' ') ? substr($guardian->name, strpos($guardian->name, ' ') + 1, 1) : substr($guardian->name, 1, 1)));
                                        $colors = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                                        $color = $colors[crc32($guardian->id) % count($colors)];
                                    @endphp
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 font-bold text-xs shrink-0 ring-1 ring-{{ $color }}-600/20">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-[#0d141b] dark:text-white">{{ $guardian->name }}</p>
                                        @if($guardian->email)
                                            <p class="text-xs text-[#4c739a]">{{ $guardian->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm text-[#4c739a] font-mono">&#64;{{ $guardian->username }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $relMap = ['father'=>['label'=>'Ayah','color'=>'blue'], 'mother'=>['label'=>'Ibu','color'=>'pink'], 'guardian'=>['label'=>'Wali','color'=>'amber'], 'sibling'=>['label'=>'Saudara','color'=>'green']];
                                    $rel = $relMap[$guardian->relationship] ?? ['label'=>ucfirst($guardian->relationship),'color'=>'slate'];
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                    bg-{{ $rel['color'] }}-100 text-{{ $rel['color'] }}-700 border border-{{ $rel['color'] }}-200">
                                    {{ $rel['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm text-[#4c739a]">{{ $guardian->phone ?? '-' }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center h-7 w-7 rounded-full text-xs font-bold
                                    {{ $guardian->students_count > 0 ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $guardian->students_count }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.guardians.edit', $guardian) }}"
                                        class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-primary border border-primary/20 hover:bg-primary/5 transition-colors">
                                        <span class="material-symbols-outlined text-[15px]">edit</span>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.guardians.destroy', $guardian) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Hapus data wali {{ addslashes($guardian->name) }}? Semua hubungan dengan santri akan ikut dihapus.')"
                                            class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 border border-red-200 hover:bg-red-50 transition-colors">
                                            <span class="material-symbols-outlined text-[15px]">delete</span>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <span class="material-symbols-outlined text-4xl text-slate-300 block mb-2">family_restroom</span>
                                <p class="text-sm text-[#4c739a] mb-3">Belum ada data wali yang terdaftar.</p>
                                <a href="{{ route('admin.guardians.create') }}"
                                    class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:underline">
                                    <span class="material-symbols-outlined text-[16px]">add</span>
                                    Tambah wali pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guardians->hasPages())
            <div class="px-5 py-4 border-t border-[#e7edf3] dark:border-slate-700">
                {{ $guardians->links() }}
            </div>
        @endif
    </div>

@endsection
