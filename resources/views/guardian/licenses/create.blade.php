@extends('layouts.guardian')

@section('title', 'Pengajuan Izin')
@section('mobile_title', 'Pengajuan Izin')

@section('content')
<div>
    {{-- Header --}}
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

    @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($students->isEmpty())
        <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">info</span>
            Tidak ada santri yang terdaftar untuk akun ini. Hubungi admin pondok.
        </div>
    @else

    @if(!$activeYear)
        <div class="mb-5 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">warning</span>
            Tidak ada tahun ajaran aktif. Pengajuan izin tidak dapat dilakukan saat ini.
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-6">
        <form action="{{ route('guardian.licenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Santri --}}
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Santri <span class="text-red-500">*</span>
                </label>
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

            {{-- Filter Kategori --}}
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Filter Kategori</label>
                <select id="leaveCategorySelect"
                    class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('leave_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                            @if($cat->is_fixed_duration && $cat->duration_days)
                                (maks. {{ $cat->duration_days }} hari)
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Rincian Alasan --}}
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Rincian Alasan <span class="text-red-500">*</span>
                </label>
                <select name="leave_reason_id" id="leaveReasonSelect" required
                    class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    <option value="">-- Pilih Kategori terlebih dahulu --</option>
                </select>
                @error('leave_reason_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Tanggal --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date"
                        value="{{ old('start_date', date('Y-m-d')) }}" required
                        min="{{ date('Y-m-d') }}"
                        onchange="calcDuration()"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    @error('start_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Tanggal Kembali <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="end_date" id="end_date"
                        value="{{ old('end_date', date('Y-m-d')) }}" required readonly tabindex="-1"
                        min="{{ date('Y-m-d') }}"
                        onchange="calcDuration()"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-300 text-sm cursor-not-allowed focus:outline-none">
                    @error('end_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Durasi info --}}
            <div id="durasiInfo" class="hidden rounded-xl border border-blue-100 bg-blue-50/50 p-3 text-sm text-blue-700 dark:border-blue-900/30 dark:bg-blue-900/10 dark:text-blue-300"></div>

            {{-- Keterangan --}}
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Keterangan <span class="text-slate-400 text-xs font-normal">(opsional)</span>
                </label>
                <textarea name="description" rows="2"
                    class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all resize-none"
                    placeholder="Informasi tambahan jika diperlukan...">{{ old('description') }}</textarea>
            </div>

            {{-- Upload Bukti --}}
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Foto / Dokumen Pendukung <span class="text-red-500">*</span> <span class="text-slate-400 text-xs font-normal">(wajib)</span>
                </label>
                <div class="relative">
                    <input type="file" name="attachment" id="attachment" required
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="hidden"
                        onchange="previewAttachment(this)">
                    <label for="attachment"
                        class="flex items-center gap-3 w-full px-4 py-3 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-sm cursor-pointer hover:border-primary hover:text-primary hover:bg-primary/5 transition-all">
                        <span class="material-symbols-outlined text-[22px]">upload_file</span>
                        <span id="attachmentLabel">Pilih foto atau PDF (maks. 5MB)</span>
                    </label>
                </div>
                <div id="attachmentPreview" class="hidden mt-2">
                    <img id="previewImg" src="" alt="Preview"
                        class="max-h-40 rounded-xl border border-slate-200 dark:border-slate-700 object-contain">
                </div>
                @error('attachment')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Kasus Darurat --}}
            <div class="rounded-xl border border-red-200 bg-red-50 dark:bg-red-950/30 dark:border-red-900 p-4">
                <label class="flex cursor-pointer items-start gap-3">
                    <input type="checkbox" name="is_emergency" value="1" id="is_emergency"
                        {{ old('is_emergency') ? 'checked' : '' }}
                        class="mt-0.5 h-4 w-4 shrink-0 rounded border-red-300 text-red-600 focus:ring-red-500">
                    <div>
                        <span class="block text-sm font-bold text-red-900 dark:text-red-200">Tandai sebagai Kasus Darurat</span>
                        <span class="block text-xs text-red-700 dark:text-red-400 mt-0.5">Centang jika izin ini bersifat mendesak (sakit keras, musibah keluarga, dll).</span>
                    </div>
                </label>
            </div>

            {{-- Actions --}}
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

<script>
var oldReasonId = '{{ old('leave_reason_id') }}';

function loadReasons(categoryId) {
    var select = document.getElementById('leaveReasonSelect');
    if (!categoryId) {
        select.innerHTML = '<option value="">-- Pilih Kategori terlebih dahulu --</option>';
        return;
    }
    fetch('/guardian/leave-categories/' + categoryId + '/reasons')
        .then(function(r) { return r.json(); })
        .then(function(reasons) {
            if (!reasons.length) {
                select.innerHTML = '<option value="">-- Tidak ada rincian tersedia --</option>';
                return;
            }
            var html = '<option value="">-- Pilih Rincian Alasan --</option>';
            reasons.forEach(function(r) {
                var sel = (oldReasonId && oldReasonId == r.id) ? ' selected' : '';
                html += '<option value="' + r.id + '"' + sel + '>' + r.reason + '</option>';
            });
            select.innerHTML = html;
            oldReasonId = '';
        });
}

var categoriesData = @json($categories->keyBy('id')->map(function($c) {
    return [
        'is_fixed_duration' => $c->is_fixed_duration,
        'duration_days' => $c->duration_days
    ];
}));

function applyDateLogic() {
    var catId = document.getElementById('leaveCategorySelect').value;
    var startDateInput = document.getElementById('start_date');
    var endDateInput = document.getElementById('end_date');
    
    // Always allow end date to be editable now, just constrained
    endDateInput.readOnly = false;
    endDateInput.classList.remove('bg-slate-100', 'dark:bg-slate-700', 'cursor-not-allowed');

    if (!catId) {
        endDateInput.removeAttribute('max');
        return;
    }
    
    var cat = categoriesData[catId];
    if (!cat) return;
    
    if (startDateInput.value) {
        var startDate = new Date(startDateInput.value);
        var minDateStr = startDateInput.value;
        endDateInput.min = minDateStr;
        
        if (cat.is_fixed_duration && cat.duration_days) {
            // Calculate max date
            var maxDate = new Date(startDate);
            maxDate.setDate(maxDate.getDate() + (cat.duration_days - 1));
            
            var y = maxDate.getFullYear();
            var m = String(maxDate.getMonth() + 1).padStart(2, '0');
            var d = String(maxDate.getDate()).padStart(2, '0');
            var maxDateStr = y + '-' + m + '-' + d;
            
            endDateInput.max = maxDateStr;
            
            // Constrain current value if it's out of bounds
            if (endDateInput.value > maxDateStr) {
                endDateInput.value = maxDateStr;
            } else if (endDateInput.value < minDateStr) {
                endDateInput.value = minDateStr;
            }
        } else {
            endDateInput.removeAttribute('max');
            if (endDateInput.value < minDateStr) {
                endDateInput.value = minDateStr;
            }
        }
    }
    calcDuration();
}

document.getElementById('start_date').addEventListener('change', applyDateLogic);

document.getElementById('leaveCategorySelect').addEventListener('change', function() {
    loadReasons(this.value);
    applyDateLogic();
});

// Restore on validation error
var initialCat = document.getElementById('leaveCategorySelect').value;
if (initialCat) {
    loadReasons(initialCat);
    applyDateLogic();
}

function calcDuration() {
    var start = document.getElementById('start_date').value;
    var end   = document.getElementById('end_date').value;
    var info  = document.getElementById('durasiInfo');
    var catId = document.getElementById('leaveCategorySelect').value;
    var cat = catId ? categoriesData[catId] : null;

    if (!catId) {
        info.classList.add('hidden');
        return;
    }

    if (start && end) {
        var diff = Math.ceil((new Date(end) - new Date(start)) / 86400000) + 1;
        if (diff > 0) {
            info.classList.remove('hidden');
            if (cat && !cat.is_fixed_duration) {
                info.innerHTML = 'Durasi izin: <strong>' + diff + ' hari</strong>. <span class="text-[12px] italic opacity-80">(Jumlah hari akan disesuaikan berdasarkan kebutuhan dan persetujuan pengurus)</span>';
            } else {
                info.innerHTML = 'Durasi izin: Maksimal <strong>' + diff + ' hari</strong>';
            }
        } else {
            info.classList.remove('hidden');
            info.innerHTML = 'Tanggal kembali tidak boleh lebih awal dari tanggal mulai.';
        }
    } else {
        info.classList.add('hidden');
    }
}

function previewAttachment(input) {
    var label   = document.getElementById('attachmentLabel');
    var preview = document.getElementById('attachmentPreview');
    var img     = document.getElementById('previewImg');
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

calcDuration();
</script>
@endsection
