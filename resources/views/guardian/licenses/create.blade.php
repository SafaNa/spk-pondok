@extends('layouts.guardian')

@section('title', 'Pengajuan Izin')
@section('mobile_title', 'Pengajuan Izin')

@section('content')

    <div>

        <div class="rounded-2xl p-5 border border-blue-100 mb-6"
            style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
            <h1 class="text-xl font-black text-[#0d141b] dark:text-white mb-0.5">Pengajuan Izin</h1>
            <p class="text-sm text-[#4c739a]">Isi formulir berikut untuk mengajukan izin pulang.</p>
        </div>

        @if(session('success'))
            <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if($students->isEmpty())
            <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">info</span>
                Tidak ada santri yang terdaftar untuk akun ini. Hubungi admin pondok.
            </div>
        @else

        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-6">
            <form action="{{ route('guardian.licenses.store') }}" method="POST" class="space-y-5">
                @csrf

                @if($activeYear)
                    <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">
                @else
                    <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl text-sm">
                        Tidak ada tahun ajaran aktif. Hubungi admin pondok.
                    </div>
                @endif

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Santri <span class="text-red-500">*</span></label>
                    <select name="student_id" required
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        <option value="" disabled {{ old('student_id') ? '' : 'selected' }}>-- Pilih Santri --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') === $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->nis ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required
                            class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        @error('start_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Kembali <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}" required
                            class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        @error('end_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Alasan Izin <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" required
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all resize-none"
                        placeholder="Tuliskan alasan izin dengan jelas...">{{ old('description') }}</textarea>
                    @error('description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Kasus Darurat --}}
                <div class="rounded-xl border border-red-200 bg-red-50 dark:bg-red-950/30 dark:border-red-900 p-4">
                    <label class="flex cursor-pointer items-start gap-3">
                        <input type="checkbox" name="is_emergency" value="1" id="is_emergency"
                            {{ old('is_emergency') ? 'checked' : '' }}
                            class="mt-0.5 h-4 w-4 shrink-0 rounded border-red-300 text-red-600 focus:ring-red-500">
                        <div>
                            <span class="block text-sm font-bold text-red-900 dark:text-red-200">Tandai sebagai Kasus Darurat</span>
                            <span class="block text-xs text-red-700 dark:text-red-400 mt-0.5">Centang jika izin ini bersifat mendesak (sakit keras, musibah keluarga, dll). Pengajuan darurat akan mendapat prioritas validasi.</span>
                        </div>
                    </label>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('guardian.dashboard') }}"
                        class="flex-1 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 text-sm font-semibold text-center hover:bg-slate-50 transition-all">
                        Batal
                    </a>
                    <button type="submit" @if(!$activeYear) disabled @endif
                        class="flex-[2] py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>

        @endif
    </div>

@endsection
