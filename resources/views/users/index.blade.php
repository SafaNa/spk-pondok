@use('Illuminate\Support\Facades\Storage')
@extends('layouts.app')

@section('title', 'Manajemen User')
@section('breadcrumb', 'Manajemen User')

@section('content')
    <div class="rounded-2xl p-4 sm:p-6 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="text-[#0d141b] dark:text-white text-2xl font-black tracking-tight">Manajemen User</h1>
                <p class="text-[#4c739a] text-sm sm:text-base font-normal">Kelola akun Admin dan Pengurus Departemen.</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
                class="group flex items-center gap-2 h-10 px-5 rounded-lg bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md transition-all transform hover:-translate-y-0.5 w-fit">
                <span class="material-symbols-outlined text-[18px] group-hover:rotate-90 transition-transform duration-300">add</span>
                <span class="whitespace-nowrap">Tambah User</span>
            </a>
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

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                        <th class="p-4 w-12"></th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">Nama Lengkap</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">Username</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">Tipe</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase whitespace-nowrap">Departemen</th>
                        <th class="p-4 text-xs font-semibold tracking-wide text-[#4c739a] uppercase text-center whitespace-nowrap w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="p-4">
                                @if($user->photo)
                                    <img src="{{ Storage::url($user->photo) }}" alt="{{ $user->name }}"
                                        class="w-9 h-9 rounded-full object-cover border-2 border-slate-200 dark:border-slate-700">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-primary/10 dark:bg-primary/20 flex items-center justify-center text-primary font-bold text-sm border-2 border-slate-200 dark:border-slate-700">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="p-4 text-sm font-medium text-[#0d141b] dark:text-white">{{ $user->name }}</td>
                            <td class="p-4 text-sm font-mono text-[#4c739a] whitespace-nowrap">{{ $user->username }}</td>
                            <td class="p-4 whitespace-nowrap">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800">
                                        <span class="material-symbols-outlined text-[14px]">admin_panel_settings</span>
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                        <span class="material-symbols-outlined text-[14px]">apartment</span>
                                        Departemen
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 whitespace-nowrap text-sm text-[#4c739a]">
                                {{ $user->department?->name ?? '-' }}
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-[#4c739a] hover:text-primary transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    @if(!$user->isAdmin())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 text-[#4c739a] hover:text-red-600 transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-1.5 text-slate-300 dark:text-slate-600 cursor-not-allowed" title="Admin tidak dapat dihapus">
                                            <span class="material-symbols-outlined text-[20px]">lock</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-[#4c739a]">
                                <span class="material-symbols-outlined text-4xl block mb-2">person_off</span>
                                <p class="text-sm">Belum ada user.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
