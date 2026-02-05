@extends('layouts.app')

@section('title', 'Tambah Ketentuan Hafalan')
@section('breadcrumb', 'Tambah')
@section('breadcrumb_parent', 'Ketentuan Hafalan')
@section('breadcrumb_parent_route', 'memorization-types.index')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto max-w-2xl pb-10">
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            <div
                class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-6 py-6 border-b border-primary/10">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">Tambah Ketentuan Hafalan</h1>
            </div>

            <form action="{{ route('memorization-types.store') }}" method="POST" class="p-6 sm:p-8 flex flex-col gap-6">
                @csrf

                {{-- Education Level --}}
                <div class="flex flex-col gap-2">
                    <label for="education_level" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Jenjang
                        Pendidikan</label>
                    <select name="education_level" id="education_level"
                        class="rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-primary focus:border-primary">
                        <option value="" disabled selected>Pilih Jenjang</option>
                        <option value="MTS">MTS</option>
                        <option value="MA">MA</option>
                        <option value="PT">PT</option>
                    </select>
                    @error('education_level')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Day --}}
                <div class="flex flex-col gap-2">
                    <label for="day" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Hari ke-</label>
                    <input type="number" name="day" id="day" min="1" max="100"
                        class="rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-primary focus:border-primary"
                        placeholder="Contoh: 1">
                    @error('day')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="flex flex-col gap-2">
                    <label for="target_description" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Target
                        / Isi Hafalan</label>
                    <textarea name="target_description" id="target_description" rows="4"
                        class="rounded-xl border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-primary focus:border-primary"
                        placeholder="Masukkan detail hafalan atau doa..."></textarea>
                    @error('target_description')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 mt-4">
                    <a href="{{ route('memorization-types.index') }}"
                        class="px-5 py-2.5 rounded-xl text-slate-600 dark:text-slate-400 font-medium hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-primary text-white font-medium hover:bg-primary-600 transition-colors shadow-lg shadow-primary/25">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection