@extends('layouts.app')

@section('title', 'Detail Perizinan')
@section('breadcrumb', 'Detail')
@section('breadcrumb_parent', 'Perizinan')
@section('breadcrumb_parent_route', 'admin.licenses.index')
@section('mobile_title', 'Detail Perizinan')

@section('content')
    <div class="flex flex-col gap-6" x-data="{ showApproveModal: false, showRejectModal: false, override: false, overrideReason: '' }">
        {{-- Back Button --}}
        <a href="{{ route('admin.licenses.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors w-fit group">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 group-hover:bg-primary/10 transition-colors">
                <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            </div>
            <span class="text-sm font-semibold">Kembali ke Daftar</span>
        </a>

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-4 border border-red-200 dark:border-red-800 flex items-center gap-3">
                <span class="material-symbols-outlined text-red-600 text-[24px]">error</span>
                <p class="text-sm font-semibold text-red-700 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 rounded-2xl p-4 border border-green-200 dark:border-green-800 flex items-center gap-3">
                <span class="material-symbols-outlined text-green-600 text-[24px]">check_circle</span>
                <p class="text-sm font-semibold text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Status Banner --}}
        @if($license->status === 'approved')
            <div class="bg-gradient-to-r from-green-500/10 to-emerald-500/10 rounded-2xl p-6 border border-green-500/20 flex items-center gap-5">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 border border-green-500/20 text-green-600 shadow-sm flex-shrink-0">
                    <span class="material-symbols-outlined text-[32px]">check_circle</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-green-700 dark:text-green-400">
                        Izin Disetujui
                        @if($license->is_emergency)
                            <span class="ml-2 text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">Darurat</span>
                        @endif
                    </h3>
                    <p class="text-green-600/80 dark:text-green-400 text-sm mt-0.5">
                        Berlaku mulai {{ $license->start_date->format('d F Y') }}
                    </p>
                </div>
            </div>
        @elseif($license->status === 'rejected')
            <div class="bg-gradient-to-r from-red-500/10 to-rose-500/10 rounded-2xl p-6 border border-red-500/20 flex items-center gap-5">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 border border-red-500/20 text-red-600 shadow-sm flex-shrink-0">
                    <span class="material-symbols-outlined text-[32px]">cancel</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-red-700 dark:text-red-400">Izin Ditolak</h3>
                    <p class="text-red-600/80 dark:text-red-400 text-sm mt-0.5">Perizinan ini telah ditolak</p>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-amber-500/10 to-orange-500/10 rounded-2xl p-6 border border-amber-500/20 flex items-center gap-5">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/50 dark:bg-slate-800/50 border border-amber-500/20 text-amber-600 shadow-sm flex-shrink-0">
                    <span class="material-symbols-outlined text-[32px]">hourglass_top</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-amber-700 dark:text-amber-400">Menunggu Validasi</h3>
                    <p class="text-amber-600/80 dark:text-amber-400 text-sm mt-0.5">Periksa hasil validasi di bawah sebelum menyetujui</p>
                </div>
            </div>
        @endif

        {{-- Santri Info --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Informasi Santri</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-5">
                    @php
                        $name = $license->student->name;
                        $initials = strtoupper(substr($name, 0, 1) . (str_contains($name, ' ') ? substr($name, strpos($name, ' ') + 1, 1) : substr($name, 1, 1)));
                    @endphp
                    @if($license->student->photo)
                        <img src="{{ asset('storage/' . $license->student->photo) }}"
                            alt="{{ $name }}"
                            class="h-14 w-14 rounded-full object-cover ring-2 ring-white dark:ring-slate-800 flex-shrink-0">
                    @else
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-xl flex-shrink-0">
                            {{ $initials }}
                        </div>
                    @endif
                    <div>
                        <p class="text-lg font-bold text-slate-800 dark:text-white">{{ $name }}</p>
                        <p class="text-sm text-slate-500 mt-0.5">NIS: {{ $license->student->nis ?? '-' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg px-4 py-3">
                        <p class="text-xs text-slate-500 mb-1">Kamar</p>
                        <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $license->student->room->name ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg px-4 py-3">
                        <p class="text-xs text-slate-500 mb-1">Rayon</p>
                        <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $license->student->rayon->name ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg px-4 py-3">
                        <p class="text-xs text-slate-500 mb-1">Pelanggaran Aktif</p>
                        <p class="text-sm font-semibold {{ $license->student->pending_violations_count > 0 ? 'text-red-600 dark:text-red-400' : 'text-slate-800 dark:text-white' }}">
                            {{ $license->student->pending_violations_count }}
                        </p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg px-4 py-3">
                        <p class="text-xs text-slate-500 mb-1">Total Izin Disetujui</p>
                        <p class="text-sm font-semibold text-slate-800 dark:text-white">
                            {{ $approvedCount }}{{ $maxLeaves ? '/'.$maxLeaves : '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Validasi Section (hanya tampil saat pending) --}}
        @if($license->status === 'pending')
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">checklist</span>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Hasil Validasi</h3>

                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($validation as $key => $check)
                        @php
                            $isPending = $check['pending'];
                            $pass      = !$isPending && $check['pass'];
                        @endphp
                        <div class="flex items-center gap-4 px-6 py-4">
                            {{-- Icon --}}
                            @if($key === 'alasan' && $canSkip)
                                <div class="flex h-9 w-9 items-center justify-center rounded-full flex-shrink-0 bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">
                                    <span class="material-symbols-outlined text-[20px]">warning</span>
                                </div>
                            @elseif($isPending)
                                <div class="flex h-9 w-9 items-center justify-center rounded-full flex-shrink-0 bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                                    <span class="material-symbols-outlined text-[20px]">schedule</span>
                                </div>
                            @elseif($pass)
                                <div class="flex h-9 w-9 items-center justify-center rounded-full flex-shrink-0 bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                    <span class="material-symbols-outlined text-[20px]">check</span>
                                </div>
                            @else
                                <div class="flex h-9 w-9 items-center justify-center rounded-full flex-shrink-0 bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                                    <span class="material-symbols-outlined text-[20px]">close</span>
                                </div>
                            @endif

                            {{-- Label & Detail --}}
                            <div class="flex-1">
                                <p class="text-sm font-semibold {{ $isPending ? 'text-slate-400 dark:text-slate-500' : 'text-slate-800 dark:text-white' }}">
                                    {{ $check['label'] }}
                                </p>
                                <p class="text-xs mt-0.5 {{ ($key === 'alasan' && $canSkip) ? 'text-orange-600 dark:text-orange-400 font-medium' : ($isPending ? 'text-slate-400 dark:text-slate-500' : ($pass ? 'text-slate-500 dark:text-slate-400' : 'text-red-600 dark:text-red-400 font-medium')) }}">
                                    {{ $check['detail'] }}
                                </p>
                            </div>

                            {{-- Badge --}}
                            @if($key === 'alasan' && $canSkip)
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">warning</span>
                                    Darurat
                                </span>
                            @elseif($isPending)
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                                    Segera
                                </span>
                            @elseif($pass)
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    Lolos
                                </span>
                            @else
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                    Tidak Lolos
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if(!$allPass)
                    <div class="px-6 py-4 bg-amber-50 dark:bg-amber-900/20 border-t border-amber-100 dark:border-amber-800">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" x-model="override" 
                                class="mt-1 w-4 h-4 text-amber-600 rounded border-amber-300 focus:ring-amber-500">
                            <div>
                                <span class="text-sm font-bold text-amber-800 dark:text-amber-400">Loloskan Validasi</span>
                                <p class="text-xs text-amber-600 dark:text-amber-500 mt-0.5">Paksakan persetujuan izin meskipun ada syarat yang tidak lolos.</p>
                            </div>
                        </label>
                        
                        <div x-show="override" x-transition class="mt-4 pl-7">
                            <label class="block text-xs font-semibold text-amber-700 dark:text-amber-400 mb-1">Alasan Meloloskan <span class="text-red-500">*</span></label>
                            <textarea x-model="overrideReason" rows="2" 
                                class="w-full rounded-lg border border-amber-200 bg-white px-3 py-2 text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500"
                                placeholder="Masukkan alasan mengapa validasi ini diloloskan..."></textarea>
                        </div>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="px-6 py-5 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row gap-3">
                    <button type="button" @click="showApproveModal = true"
                        :disabled="!{{ $allPass ? 'true' : 'false' }} && (!override || overrideReason.trim() === '')"
                        :class="(!{{ $allPass ? 'true' : 'false' }} && (!override || overrideReason.trim() === '')) ? 'opacity-50 cursor-not-allowed bg-slate-300 dark:bg-slate-700 text-slate-500' : 'bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20 hover:shadow-primary/30'"
                        class="flex-1 px-5 py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined">check_circle</span>
                        Setujui Izin
                    </button>
                    <button type="button" @click="showRejectModal = true"
                        class="flex-1 sm:flex-none px-5 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 font-bold flex items-center justify-center gap-2 shadow-lg shadow-red-500/20 transition-all">
                        <span class="material-symbols-outlined">cancel</span>
                        Tolak
                    </button>
                </div>
            </div>
        @endif

        {{-- Detail Perizinan --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Detail Perizinan</h3>
                @if($license->status === 'pending')
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.licenses.edit', $license->id) }}"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold text-primary hover:bg-primary/10 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                            Edit
                        </a>
                        <form action="{{ route('admin.licenses.destroy', $license->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan izin ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Tahun Ajaran</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                            <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                            {{ $license->academicYear->name ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Status</p>
                        @php
                            $statusClass = match($license->status) {
                                'approved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                default    => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            };
                            $statusIcon = match($license->status) {
                                'approved' => 'check_circle',
                                'rejected' => 'cancel',
                                default    => 'pending',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold {{ $statusClass }}">
                            <span class="material-symbols-outlined text-[16px]">{{ $statusIcon }}</span>
                            {{ ucfirst($license->status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Tanggal Mulai</p>
                        <p class="font-semibold text-slate-800 dark:text-white flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-primary">calendar_today</span>
                            {{ $license->start_date->format('d F Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Tanggal Selesai</p>
                        <p class="font-semibold text-slate-800 dark:text-white flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-primary">event</span>
                            {{ $license->end_date->format('d F Y') }}
                        </p>
                    </div>
                </div>

                @php $duration = $license->start_date->diffInDays($license->end_date) + 1; @endphp
                <div>
                    <p class="text-xs text-slate-500 mb-1">Durasi</p>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        {{ $duration }} Hari
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Kategori</p>
                        <p class="font-semibold text-slate-800 dark:text-white">{{ $license->leaveCategory->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Alasan</p>
                        <p class="font-semibold text-slate-800 dark:text-white">{{ $license->leaveReason->reason ?? '-' }}</p>
                    </div>
                </div>

                @if($license->description)
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
                        <p class="text-xs text-slate-500 mb-1">Keterangan</p>
                        <p class="text-slate-800 dark:text-white leading-relaxed">{{ $license->description }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-xs text-slate-500 mb-1">Tanggal Input</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $license->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Modal: Setujui --}}
        <div x-show="showApproveModal" class="relative z-50" style="display:none">
            <div class="fixed inset-0 bg-black/50" x-transition></div>
            <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                <div x-show="showApproveModal" x-transition
                    class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600">
                            <span class="material-symbols-outlined">check_circle</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Setujui Izin</h3>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                        Setujui izin kepulangan <strong class="text-slate-800 dark:text-white">{{ $license->student->name }}</strong>?
                    </p>
                    <div class="flex gap-3">
                        <button type="button" @click="showApproveModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </button>
                        <form action="{{ route('admin.licenses.approve', $license->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="override_validation" x-bind:value="override ? '1' : '0'">
                            <input type="hidden" name="override_reason" x-bind:value="overrideReason">
                            <button type="submit"
                                class="w-full px-4 py-2.5 rounded-xl bg-primary text-white font-bold hover:bg-primary/90 transition-colors">
                                Ya, Setujui
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal: Setujui Darurat (bypass) --}}
        <div x-show="showForceModal" class="relative z-50" style="display:none">
            <div class="fixed inset-0 bg-black/50" x-transition></div>
            <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                <div x-show="showForceModal" x-transition
                    class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                            <span class="material-symbols-outlined">priority_high</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Setujui sebagai Darurat</h3>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">
                        Validasi tidak sepenuhnya lolos. Setujui izin <strong class="text-slate-800 dark:text-white">{{ $license->student->name }}</strong> sebagai kasus darurat?
                    </p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 rounded-lg px-3 py-2 mb-6">
                        Izin akan ditandai sebagai darurat dan disetujui tanpa memenuhi semua syarat validasi.
                    </p>
                    <div class="flex gap-3">
                        <button type="button" @click="showForceModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </button>
                        <form action="{{ route('admin.licenses.force-approve', $license->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2.5 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-600 transition-colors">
                                Tetap Setujui
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal: Tolak --}}
        <div x-show="showRejectModal" class="relative z-50" style="display:none">
            <div class="fixed inset-0 bg-black/50" x-transition></div>
            <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
                <div x-show="showRejectModal" x-transition
                    class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <span class="material-symbols-outlined">cancel</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Tolak Izin</h3>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                        Tolak izin kepulangan <strong class="text-slate-800 dark:text-white">{{ $license->student->name }}</strong>?
                    </p>
                    <div class="flex gap-3">
                        <button type="button" @click="showRejectModal = false"
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </button>
                        <form action="{{ route('admin.licenses.reject', $license->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full px-4 py-2.5 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors">
                                Ya, Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
