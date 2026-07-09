@extends('layouts.app')

@section('title', 'Manajemen User')
@section('breadcrumb', 'Manajemen User')

@section('content')
    <!-- Page Heading -->
    <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Manajemen User</h1>
                <p class="text-[#4c739a] text-sm sm:text-base font-normal">Kelola akun Petugas Perizinan dan Pengurus Departemen.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Tab Container --}}
    <div x-data="{ tab: '{{ session('active_tab', 'perizinan') }}' }"
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">

        {{-- Tab Header --}}
        <div class="flex items-center justify-between border-b border-[#e7edf3] dark:border-slate-700 px-4">
            <div class="flex">
                <button @click="tab = 'perizinan'"
                    :class="tab === 'perizinan' ? 'border-b-2 border-primary text-primary font-semibold' : 'text-[#4c739a] hover:text-[#0d141b] dark:hover:text-white'"
                    class="flex items-center gap-2 px-4 py-4 text-sm transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-[18px]">verified_user</span>
                    Petugas Perizinan
                    <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-medium"
                        :class="tab === 'perizinan' ? 'bg-primary/10 text-primary' : 'bg-slate-100 dark:bg-slate-700 text-[#4c739a]'">
                        {{ $licensingOfficers->count() }}
                    </span>
                </button>
                <button @click="tab = 'departemen'"
                    :class="tab === 'departemen' ? 'border-b-2 border-primary text-primary font-semibold' : 'text-[#4c739a] hover:text-[#0d141b] dark:hover:text-white'"
                    class="flex items-center gap-2 px-4 py-4 text-sm transition-colors whitespace-nowrap">
                    <span class="material-symbols-outlined text-[18px]">apartment</span>
                    Pengurus Departemen
                    <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-medium"
                        :class="tab === 'departemen' ? 'bg-primary/10 text-primary' : 'bg-slate-100 dark:bg-slate-700 text-[#4c739a]'">
                        {{ $departmentOfficers->count() }}
                    </span>
                </button>
            </div>

            {{-- Tombol Tambah (hanya muncul di tab Perizinan) --}}
            <div x-show="tab === 'perizinan'" x-cloak>
                <a href="{{ route('admin.users.create') }}"
                    class="group flex items-center gap-2 h-9 px-4 rounded-lg bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md transition-all transform hover:-translate-y-0.5">
                    <span class="material-symbols-outlined text-[18px] group-hover:rotate-90 transition-transform duration-300">add</span>
                    <span class="whitespace-nowrap">Tambah</span>
                </a>
            </div>
        </div>

        {{-- TAB: Petugas Perizinan --}}
        <div x-show="tab === 'perizinan'" x-cloak>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">Nama Lengkap</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">Email</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase text-center whitespace-nowrap w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @forelse($licensingOfficers as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white whitespace-nowrap">{{ $user->name }}</td>
                            <td class="p-4 text-sm text-[#4c739a] whitespace-nowrap">{{ $user->email }}</td>
                            <td class="p-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 text-[#4c739a] hover:text-red-600 transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-[#4c739a]">
                                <span class="material-symbols-outlined text-4xl block mb-2">person_off</span>
                                <p class="text-sm">Belum ada Petugas Perizinan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TAB: Pengurus Departemen --}}
        <div x-show="tab === 'departemen'" x-cloak>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">Nama Lengkap</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">Email</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">Departemen</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase text-center whitespace-nowrap w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @forelse($departmentOfficers as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white whitespace-nowrap">{{ $user->name }}</td>
                            <td class="p-4 text-sm text-[#4c739a] whitespace-nowrap">{{ $user->email }}</td>
                            <td class="p-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                    {{ $user->department?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <div class="flex items-center justify-center sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-[#4c739a]">
                                <span class="material-symbols-outlined text-4xl block mb-2">person_off</span>
                                <p class="text-sm">Belum ada Pengurus Departemen.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
