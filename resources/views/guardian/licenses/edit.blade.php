@extends('layouts.guardian')

@section('title', 'Pengajuan Izin')
@section('mobile_title', 'Pengajuan Izin')

@section('content')
<div>
    {{-- Header --}}
    <div class="rounded-2xl p-5 border border-blue-100 mb-6"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <h1 class="text-xl font-black text-[#0d141b] dark:text-white mb-0.5">Edit Pengajuan Izin</h1>
        <p class="text-sm text-[#4c739a]">Ubah formulir berikut untuk mengedit pengajuan izin pulang yang masih berstatus menunggu.</p>
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
        <form action="{{ route('guardian.licenses.update', $license->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Santri --}}
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Santri <span class="text-red-500">*</span>
                </label>
                <select name="student_id" required
                    class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    <option value="" disabled {{ old('student_id', $license->student_id) ? '' : 'selected' }}>-- Pilih Santri --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', $license->student_id) == $student->id ? 'selected' : '' }}>
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
                        <option value="{{ $cat->id }}" {{ old('leave_category_id', $license->leave_category_id) == $cat->id ? 'selected' : '' }}>
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
                        value="{{ old('start_date', $license->start_date->format('Y-m-d')) }}" required
                        onchange="calcDuration()"
                        class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white text-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                    @error('start_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Tanggal Kembali <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="end_date" id="end_date"
                        value="{{ old('end_date', $license->end_date->format('Y-m-d')) }}" required readonly tabindex="-1"
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
                    placeholder="Informasi tambahan jika diperlukan...">{{ old('description', $license->description) }}</textarea>
            </div>

            {{-- Upload Bukti --}}
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Foto / Dokumen Pendukung
                    <span class="text-slate-400 text-xs font-normal ml-1">(maks. 5 file @ 5MB)</span>
                </label>

                {{-- Existing attachments --}}
                @php $existingFiles = is_array($license->attachment) ? $license->attachment : array_filter([$license->attachment]); @endphp
                @if(count($existingFiles) > 0)
                <div class="mb-2" id="existingFilesContainer">
                    <p class="text-xs text-slate-500 mb-1.5">File saat ini:</p>
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        @foreach($existingFiles as $filePath)
                            @php $isImage = in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp']); @endphp
                            <div class="relative rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 aspect-square flex items-center justify-center group existing-file-card" data-path="{{ $filePath }}">
                                <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="w-full h-full block">
                                    @if($isImage)
                                        <img src="{{ asset('storage/' . $filePath) }}" class="w-full h-full object-cover" alt="Lampiran">
                                    @else
                                        <div class="flex flex-col items-center gap-1 p-2 text-center h-full justify-center">
                                            <span class="material-symbols-outlined text-red-500 text-[28px]">picture_as_pdf</span>
                                            <span class="text-[10px] text-slate-500 break-all line-clamp-2">PDF</span>
                                        </div>
                                    @endif
                                </a>
                                {{-- Delete Button --}}
                                <button type="button" onclick="removeExistingFile('{{ $filePath }}', this)"
                                    class="absolute top-1 right-1 w-6 h-6 rounded-full bg-red-600 text-white text-[10px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow z-10 hover:bg-red-700" title="Hapus file ini">
                                    <span class="material-symbols-outlined text-[14px]">close</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- New upload --}}
                <div class="relative mt-2">
                    <input type="file" name="attachments[]" id="attachment" multiple
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="hidden"
                        onchange="previewAttachments(this)">
                    <label for="attachment" id="uploadZone"
                        class="flex flex-col items-center justify-center gap-2 w-full px-4 py-4 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-sm cursor-pointer hover:border-primary hover:text-primary hover:bg-primary/5 transition-all">
                        <span class="material-symbols-outlined text-[28px]">upload_file</span>
                        <span id="attachmentLabel" class="font-medium">Tambahkan file baru (opsional)</span>
                        <span class="text-xs text-slate-400">JPG, PNG, PDF &bull; Total maks. 5 file bersama file lama</span>
                    </label>
                </div>
                {{-- Container for hidden inputs to delete old files --}}
                <div id="deletedFilesContainer"></div>
                
                {{-- New file preview grid --}}
                <div id="previewGrid" class="hidden grid grid-cols-3 sm:grid-cols-5 gap-2 mt-2"></div>
                @error('attachments')   <p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                @error('attachments.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Kasus Darurat --}}
            <div class="rounded-xl border border-red-200 bg-red-50 dark:bg-red-950/30 dark:border-red-900 p-4">
                <label class="flex cursor-pointer items-start gap-3">
                    <input type="checkbox" name="is_emergency" value="1" id="is_emergency"
                        {{ old('is_emergency', $license->is_emergency) ? 'checked' : '' }}
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @endif
</div>

<script>
var oldReasonId = '{{ old('leave_reason_id', $license->leave_reason_id) }}';

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
    
    // For guardian, end date is always read-only
    endDateInput.readOnly = true;
    endDateInput.classList.add('bg-slate-100', 'dark:bg-slate-700', 'cursor-not-allowed');

    if (!catId) {
        startDateInput.readOnly = false;
        startDateInput.classList.remove('bg-slate-100', 'dark:bg-slate-700', 'cursor-not-allowed');
        return;
    }
    
    var cat = categoriesData[catId];
    if (!cat) return;
    
    var duration = 1; // Default 1 day for guardian if not fixed
    
    if (cat.is_fixed_duration) {
        startDateInput.readOnly = true;
        startDateInput.classList.add('bg-slate-100', 'dark:bg-slate-700', 'cursor-not-allowed');
        
        // Reset to today
        var today = new Date();
        var ty = today.getFullYear();
        var tm = String(today.getMonth() + 1).padStart(2, '0');
        var td = String(today.getDate()).padStart(2, '0');
        startDateInput.value = ty + '-' + tm + '-' + td;
        
        if (cat.duration_days) {
            duration = cat.duration_days;
        }
    } else {
        startDateInput.readOnly = false;
        startDateInput.classList.remove('bg-slate-100', 'dark:bg-slate-700', 'cursor-not-allowed');
    }
    
    if (startDateInput.value) {
        var startDate = new Date(startDateInput.value);
        startDate.setDate(startDate.getDate() + (duration - 1));
        
        var y = startDate.getFullYear();
        var m = String(startDate.getMonth() + 1).padStart(2, '0');
        var d = String(startDate.getDate()).padStart(2, '0');
        
        endDateInput.value = y + '-' + m + '-' + d;
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
    if (start && end) {
        var diff = Math.ceil((new Date(end) - new Date(start)) / 86400000) + 1;
        if (diff > 0) {
            info.classList.remove('hidden');
            info.textContent = 'Durasi izin: ' + diff + ' hari';
        } else {
            info.classList.remove('hidden');
            info.textContent = 'Tanggal kembali tidak boleh lebih awal dari tanggal mulai.';
        }
    } else {
        info.classList.add('hidden');
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

function removeExistingFile(filePath, btnElement) {
    // Hide the UI card
    var card = btnElement.closest('.existing-file-card');
    card.classList.add('hidden');
    
    // Add hidden input so backend knows to delete it
    var container = document.getElementById('deletedFilesContainer');
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'delete_attachments[]';
    input.value = filePath;
    container.appendChild(input);
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
        label.textContent = 'Tambahkan file baru (opsional)';
        return;
    }

    label.textContent = selectedFiles.length + ' file baru ditambahkan';
    grid.classList.remove('hidden');

    selectedFiles.forEach(function(file, idx) {
        var card = document.createElement('div');
        card.className = 'relative rounded-lg overflow-hidden border border-primary/40 bg-slate-100 dark:bg-slate-800 aspect-square flex items-center justify-center group';

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
        btn.className = 'absolute top-1 right-1 w-6 h-6 rounded-full bg-red-600 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow z-10 hover:bg-red-700';
        btn.innerHTML = '<span class="material-symbols-outlined text-[14px]">close</span>';
        btn.title = 'Batal upload file ini';
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
