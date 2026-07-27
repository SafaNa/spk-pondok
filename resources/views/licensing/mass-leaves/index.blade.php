@extends('layouts.app')

@section('title', 'Libur Massal')
@section('breadcrumb', 'Libur Massal')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800 mb-8">
        <div class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-6 py-8 border-b border-primary/10">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div class="flex items-center gap-6">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">groups</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Liburan Serentak</h1>
                        <p class="text-slate-500 dark:text-slate-400 mt-1">Kelola event kepulangan massal santri</p>
                    </div>
                </div>
                <a href="{{ route('admin.mass-leaves.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all group">
                    <span class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform">add</span>
                    Buat Event Liburan
                </a>
            </div>
        </div>
    </div>

    <!-- Stats/Info -->
    @if(session('success'))
    <div class="p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-start gap-3">
        <span class="material-symbols-outlined text-green-500">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-start gap-3">
        <span class="material-symbols-outlined text-red-500">error</span>
        {{ session('error') }}
    </div>
    @endif

    <!-- List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($leaves as $leave)
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 flex flex-col">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $leave->title }}</h3>
                    <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-1">
                        <span class="material-symbols-outlined text-[14px]">calendar_month</span>
                        {{ \Carbon\Carbon::parse($leave->start_date)->locale('id')->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->locale('id')->translatedFormat('d M Y') }}
                    </div>
                </div>
                <div>
                    @if($leave->status === 'completed')
                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                        Selesai
                    </span>
                    @else
                    <div class="flex items-center gap-2">
                        @if($leave->canBeFinishedManually())
                        <form action="{{ route('admin.mass-leaves.finish', $leave->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="button" @click.prevent="$store.confirmModal.open($el.closest('form'), 'Akhiri Event', 'Apakah Anda yakin ingin menyudahi event liburan ini dan mengubah statusnya menjadi Selesai permanen?', 'Ya, Akhiri Sekarang', 'Batal', 'warning')"
                                class="px-2.5 py-1 text-xs font-bold rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 transition-colors"
                                title="Tutup / Akhiri Event">
                                Akhiri Event
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('admin.mass-leaves.toggle-status', $leave->id) }}" method="POST" class="inline flex items-center">
                            @csrf
                            <button type="button" @click.prevent="$store.confirmModal.open($el.closest('form'), 'Konfirmasi Ubah Status', 'Apakah Anda yakin ingin {{ $leave->status === 'active' ? 'menonaktifkan' : 'mengaktifkan' }} event liburan ini?', 'Ya, Lanjutkan', 'Batal', 'primary')"
                                class="group/toggle flex items-center gap-2 transition-all cursor-pointer" title="Ubah Status (On/Off)">
                                <span class="text-xs font-semibold {{ $leave->status === 'active' ? 'text-primary dark:text-blue-400' : 'text-slate-400' }}">
                                    {{ $leave->status === 'active' ? 'On' : 'Off' }}
                                </span>
                                <div class="w-11 h-6 rounded-full p-1 flex items-center transition-colors duration-300 {{ $leave->status === 'active' ? 'bg-primary justify-end' : 'bg-slate-300 dark:bg-slate-600 justify-start group-hover/toggle:bg-slate-400' }}">
                                    <div class="bg-white w-4 h-4 rounded-full shadow-sm"></div>
                                </div>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex-1 flex flex-col justify-center items-center py-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl mb-4">
                <span class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ $leave->students_count }}</span>
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Santri Pulang</span>
            </div>

            <div class="flex items-center gap-2 mt-auto">
                <a href="{{ route('admin.mass-leaves.show', $leave->id) }}"
                    class="flex-1 inline-flex justify-center items-center gap-1.5 px-3 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 font-bold text-xs rounded-xl transition-colors">
                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                    Detail
                </a>
                @if(\Carbon\Carbon::now()->startOfDay()->lt(\Carbon\Carbon::parse($leave->start_date)->subDay()->startOfDay()))
                <a href="{{ route('admin.mass-leaves.edit', $leave->id) }}"
                    class="inline-flex justify-center items-center gap-1.5 px-3 py-2 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/50 font-bold text-xs rounded-xl transition-colors"
                    title="Edit Event">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Edit
                </a>
                <form action="{{ route('admin.mass-leaves.destroy', $leave->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click.prevent="$store.deleteModal.open($el.closest('form'), 'Apakah Anda yakin ingin menghapus event liburan ini? Data santri yang terkait juga akan dihapus.')"
                        class="inline-flex justify-center items-center gap-1.5 px-3 py-2 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 font-bold text-xs rounded-xl transition-colors"
                        title="Hapus Event">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                        Hapus
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700">
            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-slate-400 text-3xl">event_busy</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Belum ada event liburan</h3>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Buat event pertama untuk mulai mencatat kepulangan santri.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
