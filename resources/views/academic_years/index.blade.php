@extends('layouts.app')

@section('title', 'Tahun Ajaran')
@section('mobile_title', 'Tahun Ajaran')
@section('breadcrumb', 'Tahun Ajaran')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Data Tahun Ajaran</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola data tahun ajaran akademik.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('academic-years.create') }}"
                    class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-5 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-primary/25">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Tambah Tahun Ajaran
                </a>
            </div>
        </div>

        {{-- Content --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($academicYears as $year)
                <div x-data="{ showModal: false }" class="relative">
                    {{-- Card Container --}}
                    <div
                        class="group relative bg-white dark:bg-slate-800 rounded-2xl shadow-sm border transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:hover:shadow-slate-700/30 flex flex-col overflow-hidden {{ $year->status == 'active' ? 'border-primary ring-1 ring-primary/20 shadow-lg shadow-primary/5' : 'border-slate-200 dark:border-slate-700 hover:border-primary/50 dark:hover:border-primary/50' }}">

                        {{-- Active Indicator / Toggle Switch --}}
                        <div class="absolute top-0 right-0 p-4 z-20">
                            <button type="button" @click="showModal = true"
                                class="group/toggle flex items-center gap-3 transition-all cursor-pointer" title="Ubah Status">
                                {{-- Switch --}}
                                <div
                                    class="w-11 h-6 rounded-full p-1 flex items-center transition-colors duration-300 {{ $year->status == 'active' ? 'bg-primary justify-end' : 'bg-slate-300 dark:bg-slate-600 justify-start group-hover/toggle:bg-slate-400' }}">
                                    <div class="bg-white w-4 h-4 rounded-full shadow-sm"></div>
                                </div>
                            </button>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-6 flex-1 flex flex-col items-center justify-center text-center relative z-10 pt-10">
                            <div
                                class="w-20 h-20 rounded-2xl {{ $year->status == 'active' ? 'bg-blue-50 dark:bg-blue-900/20 shadow-blue-500/10' : 'bg-slate-100 dark:bg-slate-700' }} shadow-lg flex items-center justify-center mb-5 transition-colors duration-300 group-hover:scale-110 transform">
                                <span
                                    class="material-symbols-outlined text-[36px] {{ $year->status == 'active' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500' }}">school</span>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-1 tracking-tight">{{ $year->name }}
                            </h3>
                            <p
                                class="text-xs font-semibold uppercase tracking-wider {{ $year->status == 'active' ? 'text-primary' : 'text-slate-500 dark:text-slate-400' }}">
                                {{ $year->status == 'active' ? 'Sedang Berlangsung' : 'Tahun Akademik' }}
                            </p>
                        </div>

                        {{-- Card Footer --}}
                        <div
                            class="px-6 py-4 bg-slate-50/50 dark:bg-slate-700/20 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center backdrop-blur-sm">

                            {{-- Status Label --}}
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $year->status == 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200 dark:bg-slate-800 dark:border-slate-600 dark:text-slate-400' }}">
                                <span
                                    class="w-2 h-2 rounded-full {{ $year->status == 'active' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $year->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                            </span>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2">
                                <a href="{{ route('academic-years.edit', $year->id) }}"
                                    class="group/edit w-9 h-9 p-0 flex items-center justify-center rounded-xl text-slate-400 hover:text-primary hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 relative">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>

                                    {{-- Tooltip --}}
                                    <div
                                        class="absolute bottom-full inset-x-0 mx-auto w-fit mb-2 px-3 py-1.5 bg-slate-800 text-white text-xs font-medium rounded-lg opacity-0 group-hover/edit:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                        Edit
                                    </div>
                                </a>

                                <form action="{{ route('academic-years.destroy', $year->id) }}" method="POST" class="flex"
                                    @submit.prevent="$store.deleteModal.open($el, 'Apakah Anda yakin ingin menghapus tahun ajaran {{ $year->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="group/delete w-9 h-9 p-0 flex items-center justify-center rounded-xl text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 relative">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>

                                        {{-- Tooltip --}}
                                        <div
                                            class="absolute bottom-full inset-x-0 mx-auto w-fit mb-2 px-3 py-1.5 bg-slate-800 text-white text-xs font-medium rounded-lg opacity-0 group-hover/delete:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                            Hapus
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal (Outside Card to fix stacking context/clipping) --}}
                    <div x-show="showModal" x-cloak @keydown.escape.window="showModal = false"
                        class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
                        <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" @click="showModal = false"
                            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm">
                        </div>

                        <div class="flex min-h-full items-center justify-center p-4">
                            <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-y-4" @click.stop
                                class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 dark:border-slate-700">

                                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-purple-500">
                                </div>

                                <div class="p-6">
                                    <div class="flex flex-col items-center justify-center mb-6">
                                        <div
                                            class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center mb-4">
                                            <span class="material-symbols-outlined text-[32px]">published_with_changes</span>
                                        </div>
                                        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white">
                                            Konfirmasi Status
                                        </h3>
                                        <p class="text-sm text-center text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                                            Anda akan mengubah status tahun ajaran <strong>{{ $year->name }}</strong> menjadi
                                            <span
                                                class="font-bold {{ $year->status == 'active' ? 'text-slate-500' : 'text-emerald-500' }}">
                                                {{ $year->status == 'active' ? 'Nonaktif' : 'Aktif' }}
                                            </span>.
                                            <br><span class="text-xs mt-1 block opacity-75">
                                                {{ $year->status == 'active' ? 'Tidak akan ada tahun ajaran aktif.' : 'Tahun ajaran lain akan otomatis dinonaktifkan.' }}
                                            </span>
                                        </p>
                                    </div>

                                    <form action="{{ route('academic-years.toggle-status', $year->id) }}" method="POST"
                                        x-ref="toggleForm{{ $year->id }}">
                                        @csrf
                                        <div class="flex gap-3">
                                            <button @click="showModal = false" type="button"
                                                class="flex-1 px-4 py-2.5 rounded-xl border-2 border-slate-100 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-sm">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                class="flex-1 px-4 py-2.5 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all text-white bg-primary hover:bg-primary-dark text-sm">
                                                Ya, Lanjutkan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-1 md:col-span-2 lg:col-span-3 flex flex-col items-center justify-center p-12 bg-white dark:bg-slate-800 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700 group hover:border-primary/50 transition-colors">
                    <div
                        class="w-20 h-20 bg-slate-50 dark:bg-slate-700/50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span
                            class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 group-hover:text-primary transition-colors">calendar_add_on</span>
                    </div>
                    <p class="text-slate-800 dark:text-white font-bold text-xl mb-2">Belum ada data</p>
                    <p class="text-slate-500 dark:text-slate-400 text-center max-w-sm mb-8 leading-relaxed">
                        Data tahun ajaran masih kosong. Mulailah dengan menambahkan tahun ajaran baru untuk sistem.
                    </p>
                    <a href="{{ route('academic-years.create') }}"
                        class="px-8 py-3 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-all shadow-xl shadow-primary/20 hover:shadow-2xl hover:shadow-primary/30 transform hover:-translate-y-1">
                        Buat Tahun Ajaran
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection