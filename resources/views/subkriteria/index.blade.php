@extends('layouts.app')

@section('title', 'Data Subkriteria - ' . $kriteria->nama_kriteria)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    <a href="{{ route('kriteria.index') }}"
                        class="text-[var(--color-primary-500)] hover:text-[var(--color-primary-600)]">
                        <i class="fas fa-arrow-left mr-2"></i>
                    </a>
                    Subkriteria - {{ $kriteria->nama_kriteria }}
                </h1>
                <p class="mt-1 text-gray-600">Kelola subkriteria untuk kriteria {{ $kriteria->nama_kriteria }}</p>
            </div>
            <a href="{{ route('kriteria.subkriteria.create', $kriteria->id) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-[var(--gradient-from)] to-[var(--gradient-to)] hover:from-[var(--color-primary-600)] hover:to-[var(--color-primary-700)] focus:outline-none shadow-sm transition-all duration-200 mt-4 md:mt-0">
                <i class="fas fa-plus mr-2"></i> Tambah Subkriteria
            </a>
        </div>

        <!-- Alert -->
        @if(session('success'))
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Card -->
        <div class="glass-card rounded-2xl p-6 shadow-xl">
            <div class="overflow-x-auto">
                @if($subkriteria->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Subkriteria
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nilai
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keterangan
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($subkriteria as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_subkriteria }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $item->nilai }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $item->keterangan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('kriteria.subkriteria.edit', ['kriteria' => $kriteria->id, 'subkriteria' => $item->id]) }}"
                                                class="text-[var(--color-primary-600)] hover:text-[var(--color-primary-900)] p-1.5 rounded-full hover:bg-[var(--color-primary-50)]">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form
                                                action="{{ route('kriteria.subkriteria.destroy', ['kriteria' => $kriteria->id, 'subkriteria' => $item->id]) }}"
                                                method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 p-1.5 rounded-full hover:bg-red-50">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-inbox text-4xl"></i>
                        </div>
                        <p class="text-gray-500">Belum ada data subkriteria untuk kriteria ini.</p>
                        <div class="mt-6">
                            <a href="{{ route('kriteria.subkriteria.create', $kriteria->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 focus:outline-none shadow-sm transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i> Tambah Subkriteria
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            @if($subkriteria->hasPages())
                <div class="mt-6">
                    {{ $subkriteria->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Back to Kriteria Button -->
    <div class="mt-6">
        <a href="{{ route('kriteria.index') }}"
            class="inline-flex items-center text-sm font-medium text-[var(--color-primary-600)] hover:text-[var(--color-primary-800)]">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Kriteria
        </a>
    </div>
@endsection