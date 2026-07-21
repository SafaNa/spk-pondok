@use('Illuminate\Support\Facades\Storage')
@extends('layouts.app')

@section('title', 'Manajemen User')
@section('breadcrumb', 'Manajemen User')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div class="flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ !request('role') ? 'bg-blue-600 text-white shadow-sm hover:bg-blue-700' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">Semua User</a>
            <a href="{{ route('admin.users.index', ['role' => 'perizinan']) }}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ request('role') == 'perizinan' ? 'bg-blue-600 text-white shadow-sm hover:bg-blue-700' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">Pengurus Perizinan</a>
            <a href="{{ route('admin.users.index', ['role' => 'departemen']) }}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ request('role') == 'departemen' ? 'bg-blue-600 text-white shadow-sm hover:bg-blue-700' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">Departemen</a>
        </div>
        <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white text-sm font-medium shadow-sm transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah User
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse border-hidden">
                <thead>
                    <tr class="bg-gray-50 text-gray-700">
                        <th class="border border-gray-200 p-3 text-center font-bold w-12">No</th>
                        <th class="border border-gray-200 p-3 text-center font-bold">Foto</th>
                        <th class="border border-gray-200 p-3 text-left font-bold">Nama Lengkap</th>
                        <th class="border border-gray-200 p-3 text-left font-bold">Username</th>
                        <th class="border border-gray-200 p-3 text-left font-bold">Email</th>
                        <th class="border border-gray-200 p-3 text-center font-bold">Role</th>
                        <th class="border border-gray-200 p-3 text-center font-bold">Departemen</th>
                        <th class="border border-gray-200 p-3 text-center font-bold">No HP</th>
                        <th class="border border-gray-200 p-3 text-center font-bold">Status</th>
                        <th class="border border-gray-200 p-3 text-center font-bold w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php
                        $avatarColors = [
                            ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                            ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' => 'border-orange-200'],
                            ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                            ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200'],
                            ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
                            ['bg' => 'bg-teal-100', 'text' => 'text-teal-700', 'border' => 'border-teal-200'],
                            ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200'],
                            ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                            ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200'],
                            ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'border' => 'border-violet-200'],
                            ['bg' => 'bg-fuchsia-100', 'text' => 'text-fuchsia-700', 'border' => 'border-fuchsia-200'],
                            ['bg' => 'bg-pink-100', 'text' => 'text-pink-700', 'border' => 'border-pink-200'],
                            ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'border' => 'border-rose-200'],
                        ];
                    @endphp
                    @forelse($users as $user)
                        <tr class="bg-white hover:bg-gray-50">
                            <td class="border border-gray-200 p-3 text-center font-bold text-gray-800">
                                {{ method_exists($users, 'firstItem') ? $users->firstItem() + $loop->index : $loop->iteration }}
                            </td>
                            <td class="border border-gray-200 p-3 text-center">
                                <div class="flex justify-center">
                                    @if($user->photo)
                                        <img src="{{ Storage::url($user->photo) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    @else
                                        @php
                                            $colorIndex = abs(crc32($user->name)) % count($avatarColors);
                                            $color = $avatarColors[$colorIndex];
                                        @endphp
                                        <div class="w-10 h-10 rounded-full {{ $color['bg'] }} flex items-center justify-center {{ $color['text'] }} font-bold text-sm border {{ $color['border'] }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="border border-gray-200 p-3 text-left text-gray-800 font-medium">{{ $user->name }}</td>
                            <td class="border border-gray-200 p-3 text-left text-gray-600">{{ $user->username }}</td>
                            <td class="border border-gray-200 p-3 text-left text-gray-600">{{ $user->email ?? $user->username . '@latee.sch.id' }}</td>
                            <td class="border border-gray-200 p-3 text-center">
                                @if($user->isLicensingOfficer() || ($user->department && $user->department->acronym === 'PERIZINAN'))
                                    <span class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Pengurus Perizinan</span>
                                @elseif($user->isAdmin())
                                    <span class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">Admin</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">Departemen</span>
                                @endif
                            </td>
                            <td class="border border-gray-200 p-3 text-center text-gray-600">
                                {{ $user->department?->name ?? 'Semua' }}
                            </td>
                            <td class="border border-gray-200 p-3 text-center text-gray-600">
                                081234567890
                            </td>
                            <td class="border border-gray-200 p-3 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Aktif</span>
                            </td>
                            <td class="border border-gray-200 p-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="#" class="w-8 h-8 rounded flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white transition-colors" title="Lihat">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="w-8 h-8 rounded flex items-center justify-center bg-yellow-400 hover:bg-yellow-500 text-white transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>
                                    @if(!$user->isAdmin())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded flex items-center justify-center bg-red-600 hover:bg-red-700 text-white transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" disabled class="w-8 h-8 rounded flex items-center justify-center bg-red-300 text-white cursor-not-allowed" title="Admin tidak dapat dihapus">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="border border-gray-200 p-8 text-center text-gray-500">
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
