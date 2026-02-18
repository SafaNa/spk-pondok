@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Cek Hafalan Santri Pulang</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Daftar santri yang memiliki izin pulang dan perlu pengecekan
                hafalan.</p>
        </div>
    </div>

    <div
        class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase font-semibold tracking-wider">
                        <th class="px-6 py-4">Santri</th>
                        <th class="px-6 py-4">Tanggal Izin</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-center">Status Hafalan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($licenses as $license)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                                        {{ substr($license->student->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-700 dark:text-slate-200">
                                            {{ $license->student->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $license->student->nis }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600 dark:text-slate-300">
                                    <span
                                        class="font-medium">{{ \Carbon\Carbon::parse($license->start_date)->isoFormat('D MMM Y') }}</span>
                                    <span class="text-slate-400 mx-1">-</span>
                                    <span
                                        class="font-medium">{{ \Carbon\Carbon::parse($license->end_date)->isoFormat('D MMM Y') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-2"
                                    title="{{ $license->description }}">
                                    {{ $license->description }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer memorization-toggle" data-id="{{ $license->id }}"
                                        {{ $license->memorization_check ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/30 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary">
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300 status-text">
                                        {{ $license->memorization_check ? 'Sudah Cek' : 'Belum Cek' }}
                                    </span>
                                </label>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <span class="material-symbols-outlined text-4xl mb-2 text-slate-300">assignment_turned_in</span>
                                <p>Tidak ada data izin pulang yang perlu dicek.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('.memorization-toggle').on('change', function () {
                    const licenseId = $(this).data('id');
                    const isChecked = $(this).is(':checked');
                    const statusText = $(this).siblings('.status-text');

                    // Optimistic UI update
                    statusText.text(isChecked ? 'Sudah Cek' : 'Belum Cek');

                    $.ajax({
                        url: `/memorization/licenses/${licenseId}/update-check`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            memorization_check: isChecked ? 1 : 0
                        },
                        success: function (response) {
                            // Success toast or notification could be added here
                            console.log('Status updated');
                        },
                        error: function (xhr) {
                            // Revert on error
                            $(this).prop('checked', !isChecked);
                            statusText.text(!isChecked ? 'Sudah Cek' : 'Belum Cek');
                            alert('Gagal memperbarui status. Silakan coba lagi.');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection