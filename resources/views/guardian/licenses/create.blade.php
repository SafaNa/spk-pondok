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

    {{-- Info Batasan --}}
    @if($activeYear && ($activeYear->max_leave_days || $activeYear->max_leaves))
        <div class="mb-5 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-xl text-sm space-y-1">
            <p class="font-semibold flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">info</span>Ketentuan Pengajuan Izin (TA {{ $activeYear->name }})</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs ml-1">
                @if($activeYear->max_leaves)
                    <li>Izin yang disetujui maksimal <strong>{{ $activeYear->max_leaves }} kali</strong> per tahun ajaran.</li>
                @endif
                @if($activeYear->max_leave_days)
                    <li>Setelah kembali ke pondok, harus menunggu <strong>{{ $activeYear->max_leave_days }} hari</strong> sebelum bisa mengajukan izin lagi.</li>
                @endif
                <li>Tidak dapat mengajukan izin baru jika masih ada izin yang aktif atau menunggu persetujuan.</li>
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 p-6">
        <form action="{{ route('guardian.licenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Santri --}}
            @if($students->count() === 1)
                @php $singleStudent = $students->first(); $isBlocked = $blockedStudentIds->contains($singleStudent->id); @endphp
                <input type="hidden" name="student_id" value="{{ $singleStudent->id }}">
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Santri</label>
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl border-2 {{ $isBlocked ? 'border-amber-200 bg-amber-50 dark:bg-amber-900/20' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800' }}">
                        @php
                            $colors = ['blue','pink','amber','rose','indigo','green','purple','cyan','orange','teal'];
                            $color  = $colors[crc32($singleStudent->id) % count($colors)];
                            $parts  = explode(' ', trim($singleStudent->name));
                            $initials = strtoupper(substr($parts[0],0,1).(isset($parts[1])?substr($parts[1],0,1):''));
                        @endphp
                        @if($singleStudent->photo)
                            <img src="{{ asset('storage/'.$singleStudent->photo) }}" class="w-9 h-9 rounded-full object-cover shrink-0">
                        @else
                            <div class="flex w-9 h-9 shrink-0 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 text-sm font-bold">{{ $initials }}</div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">{{ $singleStudent->name }}</p>
                            <p class="text-xs text-[#4c739a]">{{ $singleStudent->identifier_label }}: {{ $singleStudent->nis ?? '-' }}</p>
                        </div>
                        @if($isBlocked)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600 bg-amber-100 px-2 py-1 rounded-full shrink-0">
                                <span class="material-symbols-outlined text-[13px]">warning</span> Izin Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full shrink-0">
                                <span class="material-symbols-outlined text-[13px]">check_circle</span> Siap
                            </span>
                        @endif
                    </div>
                    @if($isBlocked)
                        <p class="text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-[14px]">warning</span>
                            Santri ini masih memiliki izin aktif. Pengajuan baru tidak dapat dilakukan.
                        </p>
                    @endif
                </div>
            @else
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Santri <span class="text-red-500">*</span>
                    </label>
                    <select name="student_id" required
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        <option value="" disabled {{ old('student_id') ? '' : 'selected' }}>-- Pilih Santri --</option>
                        @foreach($students as $student)
                            @php $isBlocked = $blockedStudentIds->contains($student->id); @endphp
                            <option value="{{ $student->id }}"
                                {{ old('student_id') === $student->id ? 'selected' : '' }}
                                {{ $isBlocked ? 'disabled' : '' }}>
                                {{ $student->name }} ({{ $student->nis ?? '-' }}){{ $isBlocked ? ' — izin aktif' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    @if($blockedStudentIds->isNotEmpty())
                        <p class="text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-[14px]">warning</span>
                            Santri bertanda <em>"izin aktif"</em> tidak dapat dipilih karena masih memiliki izin yang sedang berjalan.
                        </p>
                    @endif
                </div>
            @endif

            {{-- Filter Kategori --}}
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kategori Kepulangan</label>
                <select id="leaveCategorySelect"
                    class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    <option value="">-- Semua Kategori Kepulangan --</option>
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
                    Alasan Kepulangan <span class="text-red-500">*</span>
                </label>
                <select name="leave_reason_id" id="leaveReasonSelect" required
                    class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    <option value="">-- Pilih Alasan Kepulangan --</option>
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
                        onchange="applyDateLogic()"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    @error('start_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label id="end_date_label" class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Tanggal Kembali <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="end_date" id="end_date"
                        value="{{ old('end_date', date('Y-m-d')) }}" required
                        min="{{ date('Y-m-d') }}"
                        onchange="calcDuration()"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
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
                    placeholder="Keterangan tambahan jika diperlukan...">{{ old('description') }}</textarea>
            </div>

            {{-- Upload Bukti --}}
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Foto / Dokumen Pendukung <span class="text-red-500">*</span>
                    <span class="text-slate-400 text-xs font-normal ml-1">(wajib, maks. 5 file @ 5MB)</span>
                </label>
                <div class="relative">
                    <input type="file" name="attachments[]" id="attachment" required multiple
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="hidden"
                        onchange="previewAttachments(this)">
                    <label for="attachment" id="uploadZone"
                        class="flex flex-col items-center justify-center gap-2 w-full px-4 py-5 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-sm cursor-pointer hover:border-primary hover:text-primary hover:bg-primary/5 transition-all">
                        <span class="material-symbols-outlined text-[32px]">upload_file</span>
                        <span id="attachmentLabel" class="font-medium">Upload Foto atau Dokumen</span>
                        <span class="text-xs text-slate-400">JPG, PNG, PDF &bull; Maks. 5 file &bull; 5MB per file</span>
                    </label>
                </div>
                {{-- Preview Grid --}}
                <div id="previewGrid" class="hidden grid grid-cols-3 sm:grid-cols-5 gap-2 mt-2"></div>
                @error('attachments')   <p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                @error('attachments.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
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
                    class="flex-1 min-h-[44px] py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 text-sm font-semibold text-center hover:bg-slate-50 transition-all flex items-center justify-center">
                    Batal
                </a>
                <button type="submit" @if(!$activeYear) disabled @endif
                    class="flex-[2] min-h-[44px] py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed">
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
        select.innerHTML = '<option value="">-- Pilih Alasan Kepulangan --</option>';
        return;
    }
    fetch('/guardian/leave-categories/' + categoryId + '/reasons')
        .then(function(r) { return r.json(); })
        .then(function(reasons) {
            if (!reasons.length) {
                select.innerHTML = '<option value="">-- Tidak ada rincian tersedia --</option>';
                return;
            }
            var html = '<option value="">-- Pilih Alasan Kepulangan --</option>';
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
        'duration_days'     => $c->duration_days,
    ];
}));

function formatDate(d) {
    var y = d.getFullYear();
    var m = String(d.getMonth() + 1).padStart(2, '0');
    var day = String(d.getDate()).padStart(2, '0');
    return y + '-' + m + '-' + day;
}

function setEndDateLocked(locked) {
    var el = document.getElementById('end_date');
    var label = document.getElementById('end_date_label');
    if (locked) {
        el.readOnly = true;
        el.tabIndex = -1;
        el.classList.add('bg-slate-100', 'cursor-not-allowed');
        el.classList.remove('bg-slate-50', 'dark:bg-slate-800', 'focus:border-primary', 'focus:ring-4', 'focus:ring-primary/10');
        label.innerHTML = 'Tanggal Kembali <span class="text-xs font-normal text-slate-400">(otomatis)</span>';
    } else {
        el.readOnly = false;
        el.tabIndex = 0;
        el.classList.remove('bg-slate-100', 'cursor-not-allowed');
        el.classList.add('bg-slate-50', 'dark:bg-slate-800', 'focus:border-primary', 'focus:ring-4', 'focus:ring-primary/10');
        label.innerHTML = 'Tanggal Kembali <span class="text-red-500">*</span>';
    }
}

function applyDateLogic() {
    var catId = document.getElementById('leaveCategorySelect').value;
    var startVal = document.getElementById('start_date').value;
    var endInput = document.getElementById('end_date');
    var cat = catId ? categoriesData[catId] : null;

    if (cat && cat.is_fixed_duration && cat.duration_days) {
        // Auto-hitung end_date, kunci input
        setEndDateLocked(true);
        endInput.removeAttribute('max');
        endInput.removeAttribute('min');
        if (startVal) {
            var startDate = new Date(startVal);
            var endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + (cat.duration_days - 1));
            endInput.value = formatDate(endDate);
        }
    } else {
        // User bebas pilih end_date
        setEndDateLocked(false);
        if (startVal) {
            endInput.min = startVal;
            endInput.removeAttribute('max');
            if (endInput.value < startVal) endInput.value = startVal;
        }
    }
    calcDuration();
}

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
    var cat   = catId ? categoriesData[catId] : null;

    if (!start || !end) { info.classList.add('hidden'); return; }

    var diff = Math.ceil((new Date(end) - new Date(start)) / 86400000) + 1;
    if (diff <= 0) {
        info.classList.remove('hidden');
        info.innerHTML = '<span class="text-red-600">Tanggal kembali tidak boleh lebih awal dari tanggal mulai.</span>';
        return;
    }

    info.classList.remove('hidden');
    if (cat && cat.is_fixed_duration) {
        info.innerHTML = 'Durasi izin: <strong>' + diff + ' hari</strong> (sesuai ketentuan kategori).';
    } else {
        info.innerHTML = 'Durasi izin: <strong>' + diff + ' hari</strong>.';
    }
}

// --- Stateful file manager ---
var selectedFiles = [];

function previewAttachments(input) {
    // Merge newly picked files into selectedFiles (avoid duplicates by name+size)
    Array.from(input.files).forEach(function(file) {
        var isDupe = selectedFiles.some(function(f) {
            return f.name === file.name && f.size === file.size;
        });
        if (!isDupe) selectedFiles.push(file);
    });
    // Reset the actual input so change event fires again if same file re-added
    input.value = '';
    renderPreviews();
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    renderPreviews();
}

function renderPreviews() {
    var grid  = document.getElementById('previewGrid');
    var label = document.getElementById('attachmentLabel');
    var input = document.getElementById('attachment');
    grid.innerHTML = '';

    // Sync selectedFiles back to the file input via DataTransfer
    var dt = new DataTransfer();
    selectedFiles.forEach(function(f) { dt.items.add(f); });
    input.files = dt.files;

    if (!selectedFiles.length) {
        grid.classList.add('hidden');
        label.textContent = 'Upload Foto atau Dokumen';
        return;
    }

    label.textContent = selectedFiles.length + ' file dipilih';
    grid.classList.remove('hidden');

    selectedFiles.forEach(function(file, idx) {
        var card = document.createElement('div');
        card.className = 'relative rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 aspect-square flex items-center justify-center group';

        if (file.type.startsWith('image/')) {
            var img = document.createElement('img');
            img.className = 'w-full h-full object-cover';
            var reader = new FileReader();
            reader.onload = function(e) { img.src = e.target.result; };
            reader.readAsDataURL(file);
            card.appendChild(img);
        } else {
            var badge = document.createElement('div');
            badge.className = 'flex flex-col items-center gap-1 p-2 text-center';
            badge.innerHTML = '<span class="material-symbols-outlined text-red-500 text-[28px]">picture_as_pdf</span>'
                            + '<span class="text-[10px] text-slate-500 truncate w-full px-1">' + file.name + '</span>';
            card.appendChild(badge);
        }

        // ✕ Remove button
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'absolute top-1 right-1 w-5 h-5 rounded-full bg-red-600 text-white text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow z-10 hover:bg-red-700';
        btn.innerHTML = '&times;';
        btn.title = 'Hapus file ini';
        btn.onclick = (function(i) {
            return function(e) { e.preventDefault(); removeFile(i); };
        })(idx);
        card.appendChild(btn);

        grid.appendChild(card);
    });
}

calcDuration();
</script>
@endsection
