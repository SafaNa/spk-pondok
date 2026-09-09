@extends('layouts.guardian')

@section('title', 'Lengkapi Data Santri')
@section('mobile_title', 'Data Santri')

@section('content')

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('guardian.dashboard') }}"
            class="flex h-9 w-9 items-center justify-center rounded-xl bg-white dark:bg-slate-900 border border-[#e7edf3] dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-[20px] text-slate-500">arrow_back</span>
        </a>
        <div>
            <h1 class="text-lg font-black text-[#0d141b] dark:text-white leading-tight">Lengkapi Data Santri</h1>
            <p class="text-xs text-[#4c739a]">{{ $student->name }} &middot; {{ $student->identifier_label }}: {{ $student->nis ?? '-' }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-400">
            <span class="material-symbols-outlined text-[18px] shrink-0">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('guardian.students.update', $student) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Foto --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-5">
            <h2 class="text-sm font-bold text-[#0d141b] dark:text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">photo_camera</span>
                Foto Santri
            </h2>
            <div class="flex items-center gap-5">
                <div class="shrink-0 relative group">
                    <div class="w-24 h-24 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-300 dark:border-slate-600 group-hover:border-primary transition-all duration-300">
                        @if($student->photo)
                            <img id="photo-preview" src="{{ asset('storage/' . $student->photo) }}" alt="Preview" class="w-full h-full object-cover">
                            <div id="photo-placeholder" class="text-center p-3 hidden">
                                <span class="material-symbols-outlined text-2xl text-slate-400 group-hover:text-primary">add_a_photo</span>
                            </div>
                        @else
                            <img id="photo-preview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                            <div id="photo-placeholder" class="text-center p-3">
                                <span class="material-symbols-outlined text-2xl text-slate-400 group-hover:text-primary">add_a_photo</span>
                                <p class="text-xs text-slate-500 mt-1">Upload Foto</p>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="photo" id="photo-input" accept="image/*"
                        class="crop-avatar absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        onchange="previewStudentPhoto(this)">
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Foto Profil Santri</p>
                    <p class="text-xs text-slate-500 mt-1">Klik pada foto untuk memilih gambar, lalu crop sesuai kebutuhan.</p>
                    <p class="text-xs text-slate-400 mt-0.5">Format: JPG, PNG. Maks. 2MB.</p>
                    @error('photo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Data Pribadi --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-5">
            <h2 class="text-sm font-bold text-[#0d141b] dark:text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">person</span>
                Data Pribadi
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Nama --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}"
                        placeholder="Nama lengkap santri"
                        class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30 @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- NIK --}}
                <div>
                    <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">NIK <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input type="text" name="nik" value="{{ old('nik', $student->nik) }}"
                        maxlength="16" placeholder="16 digit NIK"
                        class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30 @error('nik') border-red-400 @enderror">
                    @error('nik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- No. HP --}}
                <div>
                    <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">No. HP / WhatsApp <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                        placeholder="08xxxxxxxxxx"
                        class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30 @error('phone') border-red-400 @enderror">
                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tempat Lahir --}}
                <div>
                    <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Tempat Lahir <span class="text-red-400">*</span></label>
                    <input type="text" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}"
                        placeholder="Contoh: Sumenep"
                        class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30 @error('birth_place') border-red-400 @enderror">
                    @error('birth_place') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Tanggal Lahir <span class="text-red-400">*</span></label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}"
                        class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30 @error('birth_date') border-red-400 @enderror">
                    @error('birth_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Alamat --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-5">
            <h2 class="text-sm font-bold text-[#0d141b] dark:text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">location_on</span>
                Alamat Asal
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Provinsi --}}
                <div>
                    <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Provinsi</label>
                    <select name="province_code" id="province_code"
                        class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30">
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinces as $code => $name)
                            <option value="{{ $code }}" {{ old('province_code', $student->province_code) == $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kabupaten/Kota --}}
                <div>
                    <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Kabupaten / Kota</label>
                    <select name="city_code" id="city_code" {{ $student->province_code ? '' : 'disabled' }}
                        class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30 disabled:opacity-50">
                        <option value="">Pilih Kabupaten/Kota</option>
                    </select>
                </div>

                {{-- Kecamatan --}}
                <div>
                    <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Kecamatan</label>
                    <select name="district_code" id="district_code" {{ $student->city_code ? '' : 'disabled' }}
                        class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30 disabled:opacity-50">
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>

                {{-- Desa/Kelurahan --}}
                <div>
                    <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Desa / Kelurahan</label>
                    <select name="village_code" id="village_code" {{ $student->district_code ? '' : 'disabled' }}
                        class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30 disabled:opacity-50">
                        <option value="">Pilih Desa/Kelurahan</option>
                    </select>
                </div>

                {{-- Alamat Lengkap --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Alamat Lengkap <span class="font-normal text-slate-400">(RT/RW, nama jalan, dll.)</span></label>
                    <textarea name="address" rows="2" placeholder="Contoh: Jl. Mawar No. 12, RT 03/RW 02"
                        class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none @error('address') border-red-400 @enderror">{{ old('address', $student->address) }}</textarea>
                    @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Data Orang Tua --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-[#e7edf3] dark:border-slate-700 shadow-sm p-5">
            <h2 class="text-sm font-bold text-[#0d141b] dark:text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">family_restroom</span>
                Data Orang Tua
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                {{-- Ayah --}}
                <div class="space-y-3">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide border-b border-[#e7edf3] dark:border-slate-700 pb-1.5">Ayah</p>
                    <div>
                        <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Nama Ayah</label>
                        <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}"
                            placeholder="Nama lengkap ayah"
                            class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Pendidikan Terakhir</label>
                        <input type="text" name="father_education" value="{{ old('father_education', $student->father_education) }}"
                            placeholder="Contoh: S1, SMA, dll."
                            class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Pekerjaan</label>
                        <input type="text" name="father_occupation" value="{{ old('father_occupation', $student->father_occupation) }}"
                            placeholder="Contoh: Wiraswasta, PNS, dll."
                            class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                </div>

                {{-- Ibu --}}
                <div class="space-y-3">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide border-b border-[#e7edf3] dark:border-slate-700 pb-1.5">Ibu</p>
                    <div>
                        <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Nama Ibu</label>
                        <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}"
                            placeholder="Nama lengkap ibu"
                            class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Pendidikan Terakhir</label>
                        <input type="text" name="mother_education" value="{{ old('mother_education', $student->mother_education) }}"
                            placeholder="Contoh: S1, SMA, dll."
                            class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#4c739a] mb-1.5">Pekerjaan</label>
                        <input type="text" name="mother_occupation" value="{{ old('mother_occupation', $student->mother_occupation) }}"
                            placeholder="Contoh: Ibu Rumah Tangga, Guru, dll."
                            class="w-full rounded-lg border border-[#e7edf3] dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-[#0d141b] dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex items-center justify-end gap-3 pb-6">
            <a href="{{ route('guardian.dashboard') }}"
                class="px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-semibold transition-colors">
                Batal
            </a>
            <button type="submit"
                class="flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-semibold transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">save</span>
                Simpan Perubahan
            </button>
        </div>
    </form>

@endsection

@push('scripts')
<script>
    // Preview foto (dipanggil via onchange setelah crop selesai)
    function previewStudentPhoto(input) {
        const preview     = document.getElementById('photo-preview');
        const placeholder = document.getElementById('photo-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Region cascading dropdowns
    const oldCity     = "{{ old('city_code', $student->city_code) }}";
    const oldDistrict = "{{ old('district_code', $student->district_code) }}";
    const oldVillage  = "{{ old('village_code', $student->village_code) }}";

    const provinceSelect = document.getElementById('province_code');
    const citySelect     = document.getElementById('city_code');
    const districtSelect = document.getElementById('district_code');
    const villageSelect  = document.getElementById('village_code');

    function fetchOptions(url, selectEl, placeholder, selectedVal = '') {
        fetch(url)
            .then(r => r.json())
            .then(data => {
                selectEl.innerHTML = `<option value="">${placeholder}</option>`;
                Object.entries(data).forEach(([code, name]) => {
                    const opt = document.createElement('option');
                    opt.value = code;
                    opt.textContent = name;
                    if (code === selectedVal) opt.selected = true;
                    selectEl.appendChild(opt);
                });
                selectEl.disabled = false;
            });
    }

    // Load saved values on page load
    if (provinceSelect.value) {
        fetchOptions(`{{ route('guardian.regions.cities') }}?province_code=${provinceSelect.value}`, citySelect, 'Pilih Kabupaten/Kota', oldCity);
    }
    citySelect.addEventListener('change', function () {
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        districtSelect.disabled = true;
        villageSelect.innerHTML  = '<option value="">Pilih Desa/Kelurahan</option>';
        villageSelect.disabled   = true;
        if (this.value) fetchOptions(`{{ route('guardian.regions.districts') }}?city_code=${this.value}`, districtSelect, 'Pilih Kecamatan', oldDistrict);
    });
    districtSelect.addEventListener('change', function () {
        villageSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
        villageSelect.disabled  = true;
        if (this.value) fetchOptions(`{{ route('guardian.regions.villages') }}?district_code=${this.value}`, villageSelect, 'Pilih Desa/Kelurahan', oldVillage);
    });
    provinceSelect.addEventListener('change', function () {
        citySelect.innerHTML     = '<option value="">Pilih Kabupaten/Kota</option>';
        citySelect.disabled      = true;
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        districtSelect.disabled  = true;
        villageSelect.innerHTML  = '<option value="">Pilih Desa/Kelurahan</option>';
        villageSelect.disabled   = true;
        if (this.value) fetchOptions(`{{ route('guardian.regions.cities') }}?province_code=${this.value}`, citySelect, 'Pilih Kabupaten/Kota');
    });

    // Trigger load kota jika ada saved city
    if (oldCity && provinceSelect.value) {
        setTimeout(() => {
            citySelect.dispatchEvent(new Event('change'));
        }, 600);
    }
</script>
@endpush
