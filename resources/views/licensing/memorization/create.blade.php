@extends('layouts.app')

@section('title', 'Catat Hafalan Santri')
@section('breadcrumb', 'Catat Hafalan')
@section('breadcrumb_parent', 'Hafalan Santri')
@section('breadcrumb_parent_route', 'admin.memorization.index')
@section('mobile_title', 'Catat Hafalan Santri')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Main Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            {{-- Header --}}
            <div class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-4 py-6 sm:px-6 sm:py-8 border-b border-primary/10">
            <a href="{{ route('admin.memorization.index') }}"
                class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors group mb-6">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                <span class="text-sm font-medium">Kembali ke Riwayat Hafalan</span>
            </a>

            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                    <span class="material-symbols-outlined text-[32px]">history_edu</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Catat Hafalan Santri</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Pilih santri, jenjang, dan jumlah hari hafalan. Sistem akan membuat daftar checklist item secara otomatis.</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form id="hafalan-form" action="{{ route('admin.memorization.store') }}" method="POST" class="p-6 sm:p-10">
            @csrf
            <input type="hidden" id="items-data" value="[]">

            <div class="grid grid-cols-1 gap-8" id="form-fields">
                {{-- Santri --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Pilih Santri
                    </h3>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                            Santri <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">school</span>
                            </div>
                            <select name="student_id" required id="student_id" style="background-image: none;"
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                <option value="">-- Pilih Santri --</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}" data-info="({{ $student->rayon?->name }} - {{ $student->room?->name }})" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('student_id')
                            <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[16px]">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Detail Hafalan --}}
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="material-symbols-outlined text-primary">menu_book</span>
                            Ketentuan Hafalan
                        </h3>
                        <p id="no-license-info" class="hidden mt-2 text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">info</span>
                            Santri ini belum memiliki pengajuan izin. Isi jumlah hari hafalan secara manual.
                        </p>
                        <div id="pending-memorization-warning" class="hidden mt-3 p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 flex items-start gap-2">
                            <span class="material-symbols-outlined text-red-500 text-[18px] mt-0.5">block</span>
                            <p class="text-xs text-red-600 dark:text-red-400 font-medium">Santri ini masih memiliki hafalan yang belum selesai. Selesaikan terlebih dahulu sebelum mencatat yang baru.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Pilih Jenjang --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Jenjang <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">grade</span>
                                </div>
                                <select name="education_level" id="education_level" required style="background-image: none;" onchange="loadPreview()"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="MTS" {{ old('education_level') == 'MTS' ? 'selected' : '' }}>MTs Sederajat</option>
                                    <option value="MA" {{ old('education_level') == 'MA' ? 'selected' : '' }}>MA Sederajat</option>
                                    <option value="PT" {{ old('education_level') == 'PT' ? 'selected' : '' }}>PT Sederajat</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('education_level')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Jumlah Hari --}}
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Jumlah Hari Hafalan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">today</span>
                                </div>
                                <input type="number" name="days" id="days" value="{{ old('days') }}" min="1" max="365" required onchange="loadPreview()" oninput="debouncePreview()"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200"
                                    placeholder="Contoh: 3">
                            </div>
                            @error('days')
                                <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[16px]">error</span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Catatan <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">notes</span>
                                </div>
                                <textarea name="notes" rows="3"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 resize-none"
                                    placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                {{-- Checklist Preview (sebelum simpan) --}}
                <div id="checklist-preview-section" class="hidden mt-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2 mb-4">
                        <span class="material-symbols-outlined text-primary">format_list_bulleted</span>
                        Preview Item Hafalan
                        <span id="preview-badge" class="ml-auto text-xs font-semibold bg-primary/10 text-primary px-2 py-0.5 rounded-full"></span>
                    </h3>
                    <div id="preview-loading" class="hidden items-center gap-2 text-slate-400 text-sm py-4">
                        <span class="material-symbols-outlined animate-spin text-[18px]">autorenew</span>
                        Memuat...
                    </div>
                    <div id="preview-empty" class="hidden text-sm text-slate-400 italic py-4">
                        Tidak ada item hafalan untuk kombinasi jenjang dan hari ini.
                    </div>
                    <ul id="preview-list" class="space-y-2"></ul>
                </div>

                {{-- Actions --}}
                <div id="form-actions" class="flex flex-col sm:flex-row gap-4 mt-8 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('admin.memorization.index') }}"
                        class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                        Batal
                    </a>
                    <button type="button" id="save-btn" onclick="submitHafalan()"
                        class="order-1 sm:order-2 flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        Simpan Data Hafalan
                    </button>
                </div>
            </div>
        </form>

        {{-- Checklist Aktif (di luar form-fields, agar tidak ikut hidden) --}}
        <div id="checklist-active-section" class="hidden p-6 sm:p-10">
            <div class="flex items-center gap-3 mb-4 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                <span class="material-symbols-outlined text-green-600 text-[22px]">check_circle</span>
                <div>
                    <p class="text-sm font-bold text-green-700 dark:text-green-400">Data hafalan berhasil disimpan!</p>
                    <p class="text-xs text-green-600 dark:text-green-500">Centang item yang sudah diselesaikan santri.</p>
                </div>
                <a id="link-to-detail" href="#" class="ml-auto text-xs text-primary hover:underline font-semibold">Lihat detail →</a>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2 mb-4">
                <span class="material-symbols-outlined text-primary">checklist</span>
                Item Hafalan
                <span id="active-badge" class="ml-auto text-xs font-semibold bg-primary/10 text-primary px-2 py-0.5 rounded-full"></span>
            </h3>
            <ul id="checklist-active-list" class="space-y-2"></ul>
            <div class="mt-6 flex gap-4">
                <a href="{{ route('admin.memorization.index') }}"
                    class="flex-1 px-6 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                    Kembali ke Daftar
                </a>
                <button onclick="resetForm()"
                    class="flex-1 px-6 py-3 rounded-xl bg-primary/10 text-primary font-bold text-sm hover:bg-primary/20 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Catat Hafalan Baru
                </button>
            </div>
        </div>
    </div>
</div>

    @push('scripts')
        <script>
            let previewTimer = null;
            let previewChecked = {};
            function debouncePreview() { clearTimeout(previewTimer); previewTimer = setTimeout(loadPreview, 500); }

            function togglePreview(typeId) {
                previewChecked[typeId] = !previewChecked[typeId];
                const circle = document.getElementById('prev-check-' + typeId);
                const li     = document.getElementById('prev-' + typeId);
                if (previewChecked[typeId]) {
                    circle.innerHTML = '<span class="material-symbols-outlined text-white text-[13px]">check</span>';
                    circle.className = 'flex-shrink-0 w-5 h-5 rounded-full border-2 border-primary bg-primary flex items-center justify-center transition-all';
                    li.classList.add('bg-primary/5', 'border-primary/30');
                    li.classList.remove('bg-white', 'dark:bg-slate-800', 'border-slate-200', 'dark:border-slate-700');
                } else {
                    circle.innerHTML = '';
                    circle.className = 'flex-shrink-0 w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center transition-all';
                    li.classList.remove('bg-primary/5', 'border-primary/30');
                    li.classList.add('bg-white', 'dark:bg-slate-800', 'border-slate-200', 'dark:border-slate-700');
                }
            }

            const STUDENT_INFO_URL  = '{{ route("admin.memorization.student-info", ":id") }}';
            const PREVIEW_ITEMS_URL = '{{ route("admin.memorization.preview-items") }}';
            const STORE_URL         = '{{ route("admin.memorization.store") }}?ajax=1';
            const SHOW_URL          = '{{ url("admin/memorization") }}/';
            const CSRF              = '{{ csrf_token() }}';

            $(document).ready(function() {
                $('#student_id').select2({
                    placeholder: '-- Pilih Santri --',
                    allowClear: true,
                    width: '100%',
                    templateResult: formatStudent,
                    templateSelection: formatStudent
                });

                function formatStudent(student) {
                    if (!student.id) return student.text;
                    return $('<span>' + student.text + ' <span class="text-slate-400 text-xs font-normal ml-1">' + ($(student.element).data('info') || '') + '</span></span>');
                }

                $('#student_id').on('change', function() {
                    const studentId = $(this).val();
                    if (!studentId) return;
                    fetch(STUDENT_INFO_URL.replace(':id', studentId))
                        .then(r => r.json())
                        .then(data => {
                            if (data.education_level) document.getElementById('education_level').value = data.education_level;
                            if (data.days)            document.getElementById('days').value = data.days;

                            // Info: tidak ada izin pending
                            const noLicenseInfo = document.getElementById('no-license-info');
                            if (!data.license_found) {
                                noLicenseInfo.classList.remove('hidden');
                                noLicenseInfo.classList.add('flex');
                            } else {
                                noLicenseInfo.classList.add('hidden');
                                noLicenseInfo.classList.remove('flex');
                            }

                            // Warning: sudah ada hafalan belum selesai
                            const pendingWarning = document.getElementById('pending-memorization-warning');
                            const saveBtn        = document.getElementById('save-btn');
                            if (data.has_pending) {
                                pendingWarning.classList.remove('hidden');
                                pendingWarning.classList.add('flex');
                                saveBtn.disabled = true;
                                saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            } else {
                                pendingWarning.classList.add('hidden');
                                pendingWarning.classList.remove('flex');
                                saveBtn.disabled = false;
                                saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                            }

                            loadPreview();
                        });
                });

            });

            function submitHafalan() {
                const btn  = document.getElementById('save-btn');
                const form = document.getElementById('hafalan-form');
                btn.disabled = true;
                btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">autorenew</span> Menyimpan...';

                const data = new FormData(form);
                Object.entries(previewChecked).forEach(([id, checked]) => {
                    if (checked) data.append('pre_checked[]', id);
                });

                fetch(STORE_URL, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: data,
                })
                .then(async r => {
                    const json = await r.json();
                    if (!r.ok) throw json;
                    return json;
                })
                .then(json => {
                    document.getElementById('hafalan-form').classList.add('hidden');
                    document.getElementById('checklist-active-section').classList.remove('hidden');
                    document.getElementById('link-to-detail').href = SHOW_URL + json.memorization_id;
                    renderActiveChecklist(json.items);
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerHTML = '<span class="material-symbols-outlined">save</span> Simpan Data Hafalan';
                    const msg = err?.error || err?.message || 'Terjadi kesalahan, coba lagi.';
                    alert(msg);
                });
            }

            function loadPreview() {
                const level   = document.getElementById('education_level').value;
                const days    = document.getElementById('days').value;
                const section = document.getElementById('checklist-preview-section');
                const loading = document.getElementById('preview-loading');
                const empty   = document.getElementById('preview-empty');
                const list    = document.getElementById('preview-list');
                const badge   = document.getElementById('preview-badge');

                if (!level || !days) { section.classList.add('hidden'); return; }

                section.classList.remove('hidden');
                loading.classList.remove('hidden');
                loading.classList.add('flex');
                empty.classList.add('hidden');
                list.innerHTML = '';
                badge.textContent = '';

                fetch(PREVIEW_ITEMS_URL + '?education_level=' + encodeURIComponent(level) + '&days=' + encodeURIComponent(days))
                    .then(r => r.json())
                    .then(data => {
                        loading.classList.add('hidden');
                        loading.classList.remove('flex');
                        const items = data.items;
                        if (!items.length) { empty.classList.remove('hidden'); return; }
                        badge.textContent = items.length + ' item';

                        list.innerHTML = items.map((item, i) =>
                            `<li id="prev-${item.id}" onclick="togglePreview('${item.id}')"
                                class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 cursor-pointer hover:border-primary/40 hover:bg-primary/5 transition-all select-none">
                                <div id="prev-check-${item.id}" class="flex-shrink-0 w-5 h-5 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center transition-all"></div>
                                <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">${item.target_description}</span>
                                <span class="text-xs text-slate-300 dark:text-slate-600">${i+1}</span>
                            </li>`
                        ).join('');
                        previewChecked = {};
                    });
            }

            function renderActiveChecklist(items) {
                const list  = document.getElementById('checklist-active-list');
                const badge = document.getElementById('active-badge');
                badge.textContent = items.length + ' item';
                list.innerHTML = items.map((item, i) => buildChecklistItem(item, i)).join('');
                updateProgress();
            }

            function buildChecklistItem(item, i) {
                const checked = item.is_checked;
                return `<li id="item-${item.id}" class="flex items-center gap-3 p-3.5 rounded-xl border transition-all cursor-pointer ${checked ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700'}"
                        onclick="toggleItem('${item.id}', '${item.toggle_url}')">
                    <div class="flex-shrink-0 w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all ${checked ? 'bg-green-500 border-green-500' : 'border-slate-300 dark:border-slate-600'}">
                        ${checked ? '<span class="material-symbols-outlined text-white text-[14px]">check</span>' : ''}
                    </div>
                    <span class="flex-1 text-sm font-medium ${checked ? 'line-through text-slate-400' : 'text-slate-700 dark:text-slate-300'}">${item.target_description}</span>
                    <span class="text-xs text-slate-400">${i+1}</span>
                </li>`;
            }

            function toggleItem(itemId, url) {
                const li = document.getElementById('item-' + itemId);
                li.style.opacity = '0.5';
                fetch(url, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                })
                .then(r => r.json())
                .then(data => {
                    const list  = document.getElementById('checklist-active-list');
                    const items = Array.from(list.querySelectorAll('li'));
                    const idx   = items.findIndex(el => el.id === 'item-' + itemId);
                    const desc  = li.querySelector('span.flex-1').textContent;
                    const newItem = { id: itemId, is_checked: data.is_checked, target_description: desc, toggle_url: url };
                    li.outerHTML = buildChecklistItem(newItem, idx);
                    updateProgress();
                    if (data.all_checked) {
                        document.getElementById('active-badge').textContent = '✓ Semua Selesai';
                        document.getElementById('active-badge').className = 'ml-auto text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400 px-2 py-0.5 rounded-full';
                    }
                })
                .catch(() => { li.style.opacity = '1'; });
            }

            function updateProgress() {
                const list = document.getElementById('checklist-active-list');
                const total   = list.querySelectorAll('li').length;
                const checked = list.querySelectorAll('[class*="bg-green"]').length;
                document.getElementById('active-badge').textContent = checked + '/' + total + ' selesai';
            }

            function resetForm() {
                document.getElementById('hafalan-form').reset();
                document.getElementById('hafalan-form').classList.remove('hidden');
                document.getElementById('checklist-active-section').classList.add('hidden');
                document.getElementById('checklist-preview-section').classList.add('hidden');
                const noLicenseInfo = document.getElementById('no-license-info');
                noLicenseInfo.classList.add('hidden');
                noLicenseInfo.classList.remove('flex');
                const pendingWarning = document.getElementById('pending-memorization-warning');
                pendingWarning.classList.add('hidden');
                pendingWarning.classList.remove('flex');
                previewChecked = {};
                $('#student_id').val(null).trigger('change');
                const btn = document.getElementById('save-btn');
                btn.disabled = false;
                btn.innerHTML = '<span class="material-symbols-outlined">save</span> Simpan Data Hafalan';
            }
        </script>
        <style>
            .select2-container--default .select2-selection--single { height: 54px !important; border: 1px solid #e2e8f0 !important; border-radius: 0.75rem !important; background-color: #ffffff !important; display: flex !important; align-items: center !important; padding: 0 1rem 0 3rem !important; }
            .dark .select2-container--default .select2-selection--single { background-color: #1e293b !important; border-color: #475569 !important; }
            .select2-container--default.select2-container--focus .select2-selection--single, .select2-container--default.select2-container--open .select2-selection--single { border-color: rgba(99,102,241,.6) !important; box-shadow: 0 0 0 2px rgba(99,102,241,.2) !important; outline: none !important; }
            .select2-container--default .select2-selection--single .select2-selection__rendered { color: #334155 !important; line-height: normal !important; padding: 0 !important; font-size: 1rem !important; }
            .dark .select2-container--default .select2-selection--single .select2-selection__rendered { color: #e2e8f0 !important; }
            .select2-container--default .select2-selection--single .select2-selection__placeholder { color: #94a3b8 !important; }
            .select2-container--default .select2-selection--single .select2-selection__arrow { height: 52px !important; right: .75rem !important; width: 20px !important; }
            .select2-dropdown { border: 1px solid #e2e8f0 !important; border-radius: .75rem !important; box-shadow: 0 10px 15px -3px rgba(0,0,0,.1) !important; margin-top: 4px !important; z-index: 50 !important; }
            .dark .select2-dropdown { background-color: #1e293b !important; border-color: #475569 !important; }
            .select2-search--dropdown .select2-search__field { border: 1px solid #e2e8f0 !important; border-radius: .5rem !important; padding: .6rem 1rem !important; margin: .5rem !important; width: calc(100% - 1rem) !important; outline: none !important; }
            .dark .select2-search--dropdown .select2-search__field { background-color: #0f172a !important; border-color: #334155 !important; color: #cbd5e1 !important; }
            .select2-results__option { padding: .75rem 1rem !important; font-size: .95rem !important; }
            .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable { background-color: rgba(99,102,241,.1) !important; color: #4f46e5 !important; }
            .select2-container--default .select2-results__option--selected { background-color: #e0e7ff !important; color: #4338ca !important; font-weight: 600 !important; }
        </style>
    @endpush
@endsection
