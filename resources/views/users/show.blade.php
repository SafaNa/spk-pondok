@use('Illuminate\Support\Facades\Storage')
@extends('layouts.app')

@section('title', 'Detail User')
@section('breadcrumb', 'Manajemen User / Detail')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800">Detail Profil User</h2>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition-colors">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                <div class="w-full md:w-1/3 flex flex-col items-center justify-center p-6 border border-gray-100 rounded-xl bg-gray-50">
                    @if($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" alt="{{ $user->name }}" class="w-48 h-48 rounded-full object-cover border-4 border-white shadow-md mb-4">
                    @else
                        @php
                            $avatarColors = [
                                ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                                ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                                ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200'],
                            ];
                            $colorIndex = abs(crc32($user->name)) % count($avatarColors);
                            $color = $avatarColors[$colorIndex];
                        @endphp
                        <div class="w-48 h-48 rounded-full {{ $color['bg'] }} flex items-center justify-center {{ $color['text'] }} font-bold text-5xl border-4 {{ $color['border'] }} shadow-md mb-4">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <h3 class="text-xl font-bold text-gray-900 text-center">{{ $user->name }}</h3>
                    <p class="text-gray-500 text-center">{{ $user->username }}</p>
                    
                    <div class="mt-4">
                        @if($user->isAdmin())
                            <span class="px-4 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-700 border border-blue-200">Administrator</span>
                        @elseif($user->isLicensingOfficer() || ($user->department && $user->department->acronym === 'PERIZINAN'))
                            <span class="px-4 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700 border border-green-200">Pengurus Perizinan</span>
                        @else
                            <span class="px-4 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-700 border border-purple-200">Pengurus Departemen</span>
                        @endif
                    </div>
                </div>

                <div class="w-full md:w-2/3">
                    <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">Informasi Akun</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Username</p>
                            <p class="font-medium text-gray-900">{{ $user->username }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Email</p>
                            <p class="font-medium text-gray-900">{{ $user->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Departemen / Unit</p>
                            <p class="font-medium text-gray-900">{{ $user->department?->name ?? 'Semua Departemen (Admin)' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Bergabung Sejak</p>
                            <p class="font-medium text-gray-900">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}" class="px-5 py-2 rounded-lg bg-yellow-400 hover:bg-yellow-500 text-white text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">edit</span> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
