@extends('layouts.app')

@section('title', 'Edit Wali')
@section('breadcrumb', 'Edit Wali')
@section('breadcrumb_parent', 'Data Wali')
@section('breadcrumb_parent_route', 'admin.guardians.index')
@section('mobile_title', 'Edit Wali')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">

        {{-- Back Button --}}
        <a href="{{ route('admin.guardians.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group mb-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Data Wali</span>
        </a>

        {{-- Main Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">

            {{-- Header --}}
            <div class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-6 py-6 sm:px-8 sm:py-8 border-b border-primary/10">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary shrink-0">
                        @if($guardian->avatar)
                            <img src="{{ asset('storage/' . $guardian->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                        @else
                            <span class="material-symbols-outlined text-[32px]">person</span>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Edit Data Wali</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Perbarui profil wali dan daftar santri yang diwalikan.</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.guardians.update', $guardian) }}" method="POST" class="p-6 sm:p-10 flex flex-col gap-10">
                @csrf
                @method('PUT')

                {{-- Profil Wali --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">badge</span>
                        Profil Wali
                    </h3>

                    {{-- Nama & Hubungan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Nama Lengkap <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                                <input type="text" name="name" value="{{ old('name', $guardian->name) }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                            </div>
                            @error('name')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Hubungan <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">family_restroom</span>
                                </div>
                                <select name="relationship" required style="background-image: none;"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium appearance-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                    <option value="father"        {{ old('relationship', $guardian->relationship) == 'father'        ? 'selected' : '' }}>Ayah</option>
                                    <option value="mother"        {{ old('relationship', $guardian->relationship) == 'mother'        ? 'selected' : '' }}>Ibu</option>
                                    <option value="sibling"       {{ old('relationship', $guardian->relationship) == 'sibling'       ? 'selected' : '' }}>Saudara Kandung</option>
                                    <option value="uncle"         {{ old('relationship', $guardian->relationship) == 'uncle'         ? 'selected' : '' }}>Paman</option>
                                    <option value="aunt"          {{ old('relationship', $guardian->relationship) == 'aunt'          ? 'selected' : '' }}>Bibi</option>
                                    <option value="nephew_niece"  {{ old('relationship', $guardian->relationship) == 'nephew_niece'  ? 'selected' : '' }}>Keponakan</option>
                                    <option value="grandfather"   {{ old('relationship', $guardian->relationship) == 'grandfather'   ? 'selected' : '' }}>Kakek</option>
                                    <option value="grandmother"   {{ old('relationship', $guardian->relationship) == 'grandmother'   ? 'selected' : '' }}>Nenek</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </div>
                            </div>
                            @error('relationship')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Username & Password --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Username <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">alternate_email</span>
                                </div>
                                <input type="text" name="username" value="{{ old('username', $guardian->username) }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                            </div>
                            @error('username')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Password Baru</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">key</span>
                                </div>
                                <input type="password" name="password" id="password"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Kosongkan jika tidak diubah">
                            </div>
                            <x-password-strength input-id="password" />
                            @error('password')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- HP & Email --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">No. HP / WhatsApp</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">phone</span>
                                </div>
                                <input type="text" name="phone" value="{{ old('phone', $guardian->phone) }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="08xx-xxxx-xxxx">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">email</span>
                                </div>
                                <input type="email" name="email" value="{{ old('email', $guardian->email) }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                            </div>
                        </div>
                    </div>

                    {{-- NIK & Pekerjaan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">NIK</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">id_card</span>
                                </div>
                                <input type="text" name="nik" value="{{ old('nik', $guardian->nik) }}" maxlength="16"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Pekerjaan</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">work</span>
                                </div>
                                <input type="text" name="job" value="{{ old('job', $guardian->job) }}"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                            </div>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Alamat</label>
                        <div class="relative group">
                            <div class="absolute top-3.5 left-4 text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">home_pin</span>
                            </div>
                            <textarea name="address" rows="3"
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                placeholder="Alamat lengkap wali">{{ old('address', $guardian->address) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Pilih Santri --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">group</span>
                        Santri yang Diwalikan
                    </h3>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Pilih Santri</label>

                        <div x-data="guardianStudentPicker()" @click.outside="open = false" class="relative">

                            {{-- Hidden inputs --}}
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="student_ids[]" :value="id">
                            </template>

                            {{-- Trigger --}}
                            <div @click="open = !open"
                                class="min-h-[52px] w-full px-4 py-2 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 cursor-pointer flex flex-wrap gap-2 items-center transition-all duration-200"
                                :class="open ? 'border-primary ring-4 ring-primary/10 bg-white dark:bg-slate-900' : 'hover:border-slate-300'">
                                <template x-if="selected.length === 0">
                                    <span class="text-slate-400 text-sm font-medium">Cari dan pilih santri...</span>
                                </template>
                                <template x-for="s in selectedObjects" :key="s.id">
                                    <span class="inline-flex items-center gap-1 pl-2.5 pr-1.5 py-1 rounded-lg bg-primary/10 text-primary text-sm font-semibold">
                                        <span x-text="s.name"></span>
                                        <button type="button" @click.stop="remove(s.id)"
                                            class="flex items-center justify-center h-4 w-4 rounded-md hover:bg-primary/20 transition-colors">
                                            <span class="material-symbols-outlined text-[13px]">close</span>
                                        </button>
                                    </span>
                                </template>
                                <span class="ml-auto pl-2 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''">
                                    <span class="material-symbols-outlined text-[20px]">expand_more</span>
                                </span>
                            </div>

                            {{-- Dropdown --}}
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute left-0 right-0 mt-1 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xl overflow-hidden z-30">
                                <div class="p-2.5 border-b border-slate-100 dark:border-slate-800">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-slate-400">
                                            <span x-show="!loading" class="material-symbols-outlined text-[18px]">search</span>
                                            <svg x-show="loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" x-model="search" @input="doSearch()" @click.stop
                                            placeholder="Ketik nama atau NIS / NIM santri..."
                                            class="w-full pl-9 pr-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm text-slate-800 dark:text-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                                    </div>
                                </div>
                                <div class="max-h-56 overflow-y-auto">
                                    <template x-if="!search.trim()">
                                        <p class="px-4 py-6 text-center text-sm text-slate-400">Ketik untuk mencari santri...</p>
                                    </template>
                                    <template x-if="search.trim() && !loading && results.length === 0">
                                        <p class="px-4 py-6 text-center text-sm text-slate-400">Santri tidak ditemukan.</p>
                                    </template>
                                    <template x-for="s in results" :key="s.id">
                                        <div @click.stop="toggle(s)"
                                            class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
                                            :class="isSelected(s.id) ? 'bg-primary/5' : ''">
                                            <div class="flex h-5 w-5 items-center justify-center rounded-md border-2 transition-all shrink-0"
                                                :class="isSelected(s.id) ? 'bg-primary border-primary' : 'border-slate-300'">
                                                <span x-show="isSelected(s.id)" class="material-symbols-outlined text-white text-[13px] leading-none">check</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-slate-800 dark:text-white truncate" x-text="s.name"></p>
                                                <p class="text-xs text-slate-400" x-text="s.nis + (s.room ? ' · ' + s.room : '')"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div class="px-4 py-2.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                                    <span class="text-xs text-slate-400" x-text="selected.length + ' santri dipilih'"></span>
                                    <button type="button" @click="open = false" class="text-xs font-semibold text-primary hover:underline">Selesai</button>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm text-slate-400 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">info</span>
                            Kosongkan untuk melepas semua hubungan santri dari wali ini.
                        </p>
                        @error('student_ids')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-4 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('admin.guardians.index') }}"
                        class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="order-1 sm:order-2 flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">update</span>
                        Perbarui Data Wali
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const _guardianSearchUrl    = @json(route('admin.guardians.search-students'));
    const _guardianInitObjects  = {!! $selectedStudentsJson !!};
    const _guardianInitSelected = @json(old('student_ids', $guardian->students->pluck('id')->toArray()));

    document.addEventListener('alpine:init', () => {
        Alpine.data('guardianStudentPicker', () => ({
            open: false,
            search: '',
            loading: false,
            results: [],
            selected: [..._guardianInitSelected],
            selectedObjects: [..._guardianInitObjects],
            debounceTimer: null,

            doSearch() {
                clearTimeout(this.debounceTimer);
                const q = this.search.trim();
                if (!q) { this.results = []; return; }
                this.debounceTimer = setTimeout(async () => {
                    this.loading = true;
                    try {
                        const res = await fetch(_guardianSearchUrl + '?q=' + encodeURIComponent(q) + '&guardian_id={{ $guardian->id }}');
                        this.results = await res.json();
                    } finally {
                        this.loading = false;
                    }
                }, 350);
            },

            toggle(student) {
                const idx = this.selected.indexOf(student.id);
                if (idx > -1) {
                    this.selected.splice(idx, 1);
                    this.selectedObjects = this.selectedObjects.filter(s => s.id !== student.id);
                } else {
                    this.selected.push(student.id);
                    this.selectedObjects.push(student);
                }
            },

            isSelected(id) { return this.selected.includes(id); },

            remove(id) {
                this.selected = this.selected.filter(s => s !== id);
                this.selectedObjects = this.selectedObjects.filter(s => s.id !== id);
            }
        }));
    });
</script>
@endpush
