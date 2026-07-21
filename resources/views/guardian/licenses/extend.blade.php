@extends('layouts.guardian')

@section('title', 'Perpanjangan Izin')
@section('mobile_title', 'Perpanjangan Izin')

@section('content')
<div>
    {{-- Header --}}
    <div class="rounded-2xl p-5 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex items-center gap-3 mb-1">
            <a href="{{ route('guardian.licenses.index') }}"
                class="flex h-8 w-8 items-center justify-center rounded-full bg-white/70 hover:bg-white text-slate-500 hover:text-primary transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            </a>
            <h1 class="text-xl font-black text-[#0d141b] dark:text-white">Ajukan Perpanjangan Izin</h1>
        </div>
        <p class="text-sm text-[#4c739a] ml-11">Perpanjangan izin untuk <strong>{{ $license->student?->name }}</strong></p>
    </div>

    @if(session('success'))
        <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-[18px]">error</span>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Info Izin Sekarang --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-4 mb-5">
        <p class="text-xs font-semibold text-[#4c739a] uppercase tracking-wide mb-3">Info Izin Saat Ini</p>
        <div class="flex flex-wrap gap-4">
            <div>
                <p class="text-xs text-slate-400">Tanggal Mulai</p>
                <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $license->start_date->locale('id')->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Tanggal Kembali (saat ini)</p>
                <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $license->end_date->locale('id')->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Maks. Perpanjangan</p>
                <p class="text-sm font-bold text-orange-600">{{ $license->end_date->copy()->addDays(3)->locale('id')->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>

    @if($requiresInPerson)
        {{-- ===== WAJIB DATANG KE PONDOK ===== --}}
        <div class="rounded-2xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-700/50 overflow-hidden">
            <div class="flex items-start gap-4 px-5 py-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 dark:bg-red-800 text-red-600 dark:text-red-300">
                    <span class="material-symbols-outlined text-[26px]">home_pin</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-red-800 dark:text-red-200 text-base mb-1">Perpanjangan Harus ke Pondok</h3>
                    <p class="text-sm text-red-700 dark:text-red-300 mb-3">
                        Karena lokasi santri berada di kecamatan yang dekat dengan pondok
                        (<strong>Guluk-guluk, Ganding, Pragaan, Lenteng, atau Bluto</strong>),
                        pengajuan perpanjangan izin <strong>tidak dapat dilakukan secara online</strong>.
                    </p>
                    <div class="rounded-xl bg-white dark:bg-slate-800 border border-red-200 dark:border-red-700 px-4 py-3">
                        <p class="text-sm font-semibold text-red-800 dark:text-red-200 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">info</span>
                            Cara mengajukan perpanjangan:
                        </p>
                        <ul class="mt-2 text-sm text-red-700 dark:text-red-300 space-y-1 list-disc list-inside">
                            <li>Wali / santri datang langsung ke pondok</li>
                            <li>Temui pengurus / petugas izin</li>
                            <li>Pengurus akan memproses perpanjangan di sistem</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    @elseif($activeExtension)
        {{-- ===== ADA PERPANJANGAN PENDING ===== --}}
        <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-700/50 overflow-hidden mb-5">
            <div class="flex items-start gap-4 px-5 py-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-800 text-amber-600 dark:text-amber-300">
                    <span class="material-symbols-outlined text-[26px]">schedule</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-amber-800 dark:text-amber-200 text-base mb-1">Pengajuan Sedang Diproses</h3>
                    <p class="text-sm text-amber-700 dark:text-amber-300">
                        Ada pengajuan perpanjangan yang sedang menunggu persetujuan pengurus.
                        Tanggal kembali baru yang diminta:
                        <strong>{{ $activeExtension->requested_new_end_date->locale('id')->translatedFormat('d F Y') }}</strong>.
                    </p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-2">
                        Santri boleh tetap di rumah sambil menunggu keputusan.
                    </p>
                </div>
            </div>
        </div>

    @else
        {{-- ===== FORM PERPANJANGAN ONLINE ===== --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-6">
            <form action="{{ route('guardian.licenses.extend.store', $license) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Info waktu tersisa --}}
                <div class="rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 px-4 py-3 text-sm text-blue-700 dark:text-blue-300 flex items-start gap-2">
                    <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">info</span>
                    <span>
                        Perpanjangan <strong>maksimal 3 hari</strong> dari tanggal kembali saat ini. Dan tanggal kembali bisa berubah menyesuaikan kebutuhan dan persetujuan pengurus.
                    </span>
                </div>

                {{-- Tanggal Kembali Baru --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Tanggal Kembali Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="requested_new_end_date" required
                        min="{{ $license->end_date->copy()->addDay()->format('Y-m-d') }}"
                        max="{{ $license->end_date->copy()->addDays(3)->format('Y-m-d') }}"
                        value="{{ old('requested_new_end_date') }}"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    <p class="text-xs text-slate-400">
                        Antara {{ $license->end_date->copy()->addDay()->locale('id')->translatedFormat('d F Y') }}
                        s.d. <span class="text-orange-600 font-semibold">{{ $license->end_date->copy()->addDays(3)->locale('id')->translatedFormat('d F Y') }}</span>
                    </p>
                    @error('requested_new_end_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Keterangan --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Alasan Perpanjangan <span class="text-slate-400 text-xs font-normal">(opsional)</span>
                    </label>
                    <textarea name="notes" rows="2"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all resize-none"
                        placeholder="Contoh: Ananda masih dalam kondisi sakit, belum bisa pulang...">{{ old('notes') }}</textarea>
                </div>

                {{-- Upload Bukti --}}
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Bukti / Dokumen Pendukung <span class="text-red-500">*</span>
                        <span class="text-slate-400 text-xs font-normal">(wajib)</span>
                    </label>
                    <div class="relative">
                        <input type="file" name="attachment" id="attachment" required
                            accept=".jpg,.jpeg,.png,.pdf"
                            class="hidden"
                            onchange="previewExt(this)">
                        <label for="attachment"
                            class="flex items-center gap-3 w-full px-4 py-3 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-sm cursor-pointer hover:border-primary hover:text-primary hover:bg-primary/5 transition-all">
                            <span class="material-symbols-outlined text-[22px]">upload_file</span>
                            <span id="extFileLabel">Pilih foto atau PDF (maks. 5MB)</span>
                        </label>
                    </div>
                    <div id="extPreview" class="hidden mt-2">
                        <img id="extPreviewImg" src="" alt="Preview"
                            class="max-h-40 rounded-xl border border-slate-200 dark:border-slate-700 object-contain">
                    </div>
                    @error('attachment')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('guardian.licenses.index') }}"
                        class="flex-1 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 text-sm font-semibold text-center hover:bg-slate-50 transition-all">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-[2] py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md transition-all">
                        Kirim Pengajuan Perpanjangan
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- ===== RIWAYAT PERPANJANGAN ===== --}}
    @if($extensions->isNotEmpty())
        <div class="mt-6 bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7edf3] dark:border-slate-700">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-primary">history</span>
                    Riwayat Perpanjangan ({{ $extensions->count() }}x)
                </h3>
            </div>
            <div class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                @foreach($extensions as $ext)
                    <div class="px-5 py-4 flex flex-wrap items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <span class="text-sm font-semibold text-slate-800 dark:text-white">
                                    → {{ $ext->requested_new_end_date->locale('id')->translatedFormat('d F Y') }}
                                </span>
                                @if($ext->status === 'pending')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">Menunggu</span>
                                @elseif($ext->status === 'approved')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Disetujui</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">Ditolak</span>
                                @endif
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $ext->source === 'guardian' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $ext->source === 'guardian' ? 'Via Wali' : 'Via Pengurus' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400">Diajukan {{ $ext->requested_at->locale('id')->translatedFormat('d M Y, H:i') }}</p>
                            @if($ext->notes)
                                <p class="text-xs text-slate-500 mt-1">Catatan: {{ $ext->notes }}</p>
                            @endif
                            @if($ext->admin_notes)
                                <p class="text-xs text-slate-500 mt-1 italic">Pengurus: {{ $ext->admin_notes }}</p>
                            @endif
                        </div>
                        @if($ext->attachment)
                            <a href="{{ Storage::url($ext->attachment) }}" data-fslightbox="bukti_ext_{{ $ext->id }}"
                                class="flex items-center gap-1 text-xs text-primary font-semibold hover:underline shrink-0">
                                <span class="material-symbols-outlined text-[15px]">attach_file</span>
                                Lihat Bukti
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
function previewExt(input) {
    var label   = document.getElementById('extFileLabel');
    var preview = document.getElementById('extPreview');
    var img     = document.getElementById('extPreviewImg');
    if (input.files && input.files[0]) {
        var file = input.files[0];
        label.textContent = file.name;
        if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    }
}
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fslightbox/index.min.js"></script>
@endpush
