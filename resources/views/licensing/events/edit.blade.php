@extends('layouts.app')

@section('content')
    <div class="p-6">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('licensing-events.index') }}"
                class="mb-4 inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Kembali ke Daftar Event
            </a>
            <h1 class="font-outfit text-2xl font-bold text-gray-800 dark:text-white">Edit Event Liburan</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui informasi event liburan yang sudah ada</p>
        </div>

        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-200 dark:bg-slate-800 dark:ring-slate-700">
            <form action="{{ route('licensing-events.update', $licensing_event) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid gap-6">
                    {{-- Name --}}
                    <div>
                        <label for="name"
                            class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <span class="material-symbols-outlined text-[18px] text-primary">event</span>
                            Nama Event <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $licensing_event->name) }}" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm transition-all focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dates --}}
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="start_date"
                                class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span class="material-symbols-outlined text-[18px] text-green-600">calendar_today</span>
                                Mulai Libur <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', $licensing_event->start_date->format('Y-m-d')) }}" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm transition-all focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                            @error('start_date')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date"
                                class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span class="material-symbols-outlined text-[18px] text-red-600">event_available</span>
                                Akhir Libur <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date', $licensing_event->end_date->format('Y-m-d')) }}" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm transition-all focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                            @error('end_date')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Is Active --}}
                    <div class="rounded-xl bg-blue-50 p-5 ring-1 ring-blue-200 dark:bg-blue-900/10 dark:ring-blue-800">
                        <label class="flex cursor-pointer items-center gap-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $licensing_event->is_active) ? 'checked' : '' }}
                                class="h-5 w-5 rounded border-blue-300 text-blue-600 transition-all focus:ring-2 focus:ring-blue-500/20 dark:border-blue-600 dark:bg-slate-700 dark:ring-offset-slate-800">
                            <div class="flex-1">
                                <div class="font-semibold text-blue-800 dark:text-blue-300">Event Aktif</div>
                                <div class="text-sm text-blue-700 dark:text-blue-400">Event dapat divalidasi dan diproses
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 border-t border-gray-200 pt-6 dark:border-slate-700">
                    <a href="{{ route('licensing-events.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-gray-300 transition-all hover:bg-gray-50 dark:bg-slate-700 dark:text-gray-200 dark:ring-slate-600 dark:hover:bg-slate-600">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-primary to-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-primary/30 transition-all hover:shadow-xl hover:shadow-primary/40">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection