@extends('layouts.app')

@section('title', 'Tambah Kategori Kepulangan')
@section('breadcrumb', 'Tambah')
@section('breadcrumb_parent', 'Kategori Kepulangan')
@section('breadcrumb_parent_route', 'admin.leave-categories.index')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            <div class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-6 py-6 sm:px-8 sm:py-8 border-b border-primary/10">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">add_circle</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold mb-2 text-slate-900 dark:text-white tracking-tight">Tambah Kategori Kepulangan</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Tambahkan kategori baru beserta rincian alasan kepulangan.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.leave-categories.store') }}" method="POST"
                class="p-6 sm:p-10 flex flex-col gap-10">
                @csrf

                {{-- Info Kategori --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">category</span>
                        Informasi Kategori
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Nama Kategori <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">label</span>
                                </div>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                    placeholder="Contoh: Kesehatan">
                            </div>
                            @error('name')<p class="text-sm text-red-500 flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-[16px]">error</span>{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Urutan Tampil</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined">sort</span>
                                </div>
                                <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                                    class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Durasi Kepulangan</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">schedule</span>
                            </div>
                            <input type="text" name="max_duration" value="{{ old('max_duration') }}"
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                placeholder="Contoh: Maksimal 2 hari 2 malam">
                        </div>
                    </div>

                    <div x-data="{ fixed: {{ old('is_fixed_duration') ? 'true' : 'false' }} }" class="space-y-3">
                        <div class="flex items-start gap-3 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800">
                            <input type="checkbox" name="is_fixed_duration" id="is_fixed_duration" value="1"
                                {{ old('is_fixed_duration') ? 'checked' : '' }}
                                x-model="fixed"
                                class="mt-0.5 h-4 w-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500/20">
                            <label for="is_fixed_duration" class="cursor-pointer">
                                <span class="block font-bold text-blue-800 dark:text-blue-300 text-sm">Durasi sudah ditentukan</span>
                                <span class="block text-xs text-blue-700 dark:text-blue-400 mt-0.5">Centang jika durasi kepulangan sudah ada batas waktu tetap. Kosongkan jika masih menyesuaikan kebutuhan.</span>
                            </label>
                        </div>
                        <div x-show="fixed" x-collapse style="display:none">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Jumlah Hari <span class="text-red-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">timer</span>
                                    </div>
                                    <input type="number" name="duration_days" value="{{ old('duration_days') }}" min="1"
                                        class="w-full pl-12 pr-16 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                        placeholder="Contoh: 2">
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-sm text-slate-400 font-medium pointer-events-none">hari</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Keterangan</label>
                        <div class="relative group">
                            <div class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">info</span>
                            </div>
                            <textarea name="notes" rows="2"
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium resize-none focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                placeholder="Catatan tambahan untuk kategori ini">{{ old('notes') }}</textarea>
                        </div>
                    </div>


                </div>

                {{-- Rincian Alasan --}}
                <div class="space-y-6" x-data="reasonList()">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
                        <span class="material-symbols-outlined text-primary">list</span>
                        Rincian Alasan Kepulangan
                    </h3>

                        <template x-for="(item, index) in reasons" :key="index">
                            <div class="flex items-center gap-3">
                                <div class="relative group flex-[2]">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                        <span class="material-symbols-outlined text-[18px]">arrow_right</span>
                                    </div>
                                    <input type="text" :name="`reasons[${index}][reason]`" x-model="item.reason"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 text-sm font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                        placeholder="Rincian alasan...">
                                </div>
                                <div class="flex-1 flex items-center gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 h-[50px]">
                                    <input type="checkbox" :name="`reasons[${index}][can_skip]`" value="1" x-model="item.can_skip"
                                        class="h-4 w-4 rounded border-amber-300 text-amber-600 focus:ring-amber-500/20 cursor-pointer">
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">Darurat (Skip Validasi)</span>
                                </div>
                                <button type="button" @click="remove(index)"
                                    class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex-shrink-0">
                                    <span class="material-symbols-outlined text-[20px]">close</span>
                                </button>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="add()"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400 hover:border-primary hover:text-primary hover:bg-primary/5 transition-all text-sm font-medium">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Tambah Alasan
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('admin.leave-categories.index') }}"
                        class="flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function reasonList() {
    let oldReasons = @json(old('reasons'));
    let initial = (oldReasons && oldReasons.length > 0) ? oldReasons : [{reason: '', can_skip: false}];
    return {
        reasons: initial,
        add() { this.reasons.push({reason: '', can_skip: false}); },
        remove(i) { if (this.reasons.length > 1) this.reasons.splice(i, 1); }
    }
}
</script>
@endpush
