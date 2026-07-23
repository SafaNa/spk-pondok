@extends('layouts.app')

@section('title', 'Data Wali')
@section('breadcrumb', 'Data Wali')
@section('mobile_title', 'Data Wali')

@section('content')

    <div class="rounded-2xl p-5 sm:p-6 border border-blue-100 mb-2"
        style="background: linear-gradient(135deg, #eff6ffff 20%, #eef2ffb3 50%, #faf5ff99 80%);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-black text-[#0d141b] dark:text-white mb-0.5">Data Wali Santri</h1>
                <p class="text-sm text-[#4c739a]">Kelola akun wali dan hubungan mereka dengan santri.</p>
            </div>
            <a href="{{ route('admin.guardians.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-md transition-all shrink-0">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                Tambah Wali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi Password Baru (setelah Create) --}}
    @if(session('created_guardian_password'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-900/20 dark:border-emerald-700/50 overflow-hidden shadow-sm">
            <div class="flex items-start gap-4 px-5 py-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-800 text-emerald-600 dark:text-emerald-300">
                    <span class="material-symbols-outlined text-[22px]">key</span>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-emerald-800 dark:text-emerald-200 text-sm mb-1">
                        Akun wali <span class="text-emerald-700 dark:text-emerald-300">{{ session('created_guardian_name') }}</span> berhasil dibuat!
                    </p>
                    <p class="text-xs text-emerald-700 dark:text-emerald-400 mb-3">Berikan informasi login berikut kepada wali / santri:</p>
                    <div class="flex flex-wrap gap-3">
                        <div class="inline-flex items-center gap-2 rounded-lg bg-white dark:bg-slate-800 border border-emerald-200 dark:border-emerald-700 px-3 py-2">
                            <span class="material-symbols-outlined text-[15px] text-emerald-500">alternate_email</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">Username:</span>
                            <span class="text-sm font-bold text-slate-800 dark:text-white font-mono">{{ session('created_guardian_username') }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2 rounded-lg bg-white dark:bg-slate-800 border border-emerald-200 dark:border-emerald-700 px-3 py-2">
                            <span class="material-symbols-outlined text-[15px] text-emerald-500">lock</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">Password:</span>
                            <span class="text-sm font-bold text-slate-800 dark:text-white font-mono">{{ session('created_guardian_password') }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">info</span>
                        Notifikasi ini hanya tampil sekali. Simpan password sebelum meninggalkan halaman ini.
                    </p>
                </div>
                <button onclick="this.closest('.rounded-2xl').remove()" class="text-emerald-400 hover:text-emerald-600 transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
        </div>
    @endif

    {{-- Notifikasi Password Reset --}}
    @if(session('reset_guardian_password'))
        <div class="rounded-2xl border border-blue-200 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-700/50 overflow-hidden shadow-sm">
            <div class="flex items-start gap-4 px-5 py-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-300">
                    <span class="material-symbols-outlined text-[22px]">lock_reset</span>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-blue-800 dark:text-blue-200 text-sm mb-1">
                        Password wali <span class="text-blue-700 dark:text-blue-300">{{ session('reset_guardian_name') }}</span> berhasil direset!
                    </p>
                    <p class="text-xs text-blue-700 dark:text-blue-400 mb-3">Berikan informasi login terbaru berikut kepada wali / santri:</p>
                    <div class="flex flex-wrap gap-3">
                        <div class="inline-flex items-center gap-2 rounded-lg bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700 px-3 py-2">
                            <span class="material-symbols-outlined text-[15px] text-blue-500">alternate_email</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">Username:</span>
                            <span class="text-sm font-bold text-slate-800 dark:text-white font-mono">{{ session('reset_guardian_username') }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2 rounded-lg bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-700 px-3 py-2">
                            <span class="material-symbols-outlined text-[15px] text-blue-500">lock</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">Password Baru:</span>
                            <span class="text-sm font-bold text-slate-800 dark:text-white font-mono">{{ session('reset_guardian_password') }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">info</span>
                        Notifikasi ini hanya tampil sekali. Simpan password sebelum meninggalkan halaman ini.
                    </p>
                </div>
                <button onclick="this.closest('.rounded-2xl').remove()" class="text-blue-400 hover:text-blue-600 transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-[#e7edf3] dark:border-slate-800 overflow-hidden">
        {{-- Search & Filter Section --}}
        <div class="p-5 border-b border-[#e7edf3] dark:border-slate-800 bg-white dark:bg-slate-900">
            <form action="{{ route('admin.guardians.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="material-symbols-outlined text-slate-400 text-[20px]">search</span>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, username, atau no HP wali..." 
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                </div>
                <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary/90 text-white text-sm font-semibold rounded-xl transition-colors shrink-0 shadow-sm">
                    Cari Wali
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.guardians.index') }}" class="px-5 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-sm font-semibold rounded-xl transition-colors shrink-0 text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#f8fafc] dark:bg-slate-800/50 border-b border-[#e7edf3] dark:border-slate-700">
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Nama Wali</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Username</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">Hubungan</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase">No. HP</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase text-center">Santri</th>
                        <th class="px-5 py-3 text-xs font-semibold text-[#4c739a] uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e7edf3] dark:divide-slate-700">
                    @forelse($guardians as $guardian)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    @php
                                        $initials = strtoupper(substr($guardian->name, 0, 1) . (str_contains($guardian->name, ' ') ? substr($guardian->name, strpos($guardian->name, ' ') + 1, 1) : substr($guardian->name, 1, 1)));
                                        $colors = ['blue', 'pink', 'amber', 'rose', 'indigo', 'green', 'purple', 'cyan', 'orange', 'teal'];
                                        $color = $colors[crc32($guardian->id) % count($colors)];
                                    @endphp
                                    <div class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 font-bold text-xs shrink-0 ring-1 ring-{{ $color }}-600/20">
                                        @if($guardian->avatar)
                                            <img src="{{ asset('storage/' . $guardian->avatar) }}" alt="Avatar" class="h-full w-full object-cover">
                                        @else
                                            {{ $initials }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-[#0d141b] dark:text-white">{{ $guardian->name }}</p>
                                        @if($guardian->email)
                                            <p class="text-xs text-[#4c739a]">{{ $guardian->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm text-[#4c739a] font-mono">{{ $guardian->username }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $relMap = [
                                        'father' => ['label' => 'Ayah', 'color' => 'blue'],
                                        'mother' => ['label' => 'Ibu', 'color' => 'pink'],
                                        'sibling' => ['label' => 'Saudara', 'color' => 'emerald'],
                                        'uncle' => ['label' => 'Paman', 'color' => 'cyan'],
                                        'aunt' => ['label' => 'Bibi', 'color' => 'rose'],
                                        'nephew_niece' => ['label' => 'Keponakan', 'color' => 'teal'],
                                        'grandfather' => ['label' => 'Kakek', 'color' => 'slate'],
                                        'grandmother' => ['label' => 'Nenek', 'color' => 'gray'],
                                        'guardian' => ['label' => 'Wali', 'color' => 'amber']
                                    ];
                                    $rel = $relMap[$guardian->relationship] ?? ['label'=>ucfirst($guardian->relationship),'color'=>'slate'];
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                    bg-{{ $rel['color'] }}-100 text-{{ $rel['color'] }}-700 border border-{{ $rel['color'] }}-200">
                                    {{ $rel['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm text-[#4c739a]">{{ $guardian->phone ?? '-' }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center justify-center h-7 w-7 rounded-full text-xs font-bold
                                    {{ $guardian->students_count > 0 ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $guardian->students_count }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Tombol Reset Password --}}
                                    <button type="button"
                                        onclick="openResetModal('{{ $guardian->id }}', '{{ addslashes($guardian->name) }}', '{{ addslashes($guardian->username) }}')"
                                        class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-amber-600 border border-amber-200 hover:bg-amber-50 transition-colors">
                                        <span class="material-symbols-outlined text-[15px]">lock_reset</span>
                                        Reset PW
                                    </button>
                                    <a href="{{ route('admin.guardians.show', $guardian) }}"
                                        title="Detail"
                                        class="flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 border border-blue-200 hover:bg-blue-50 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.guardians.edit', $guardian) }}"
                                        title="Edit"
                                        class="flex items-center justify-center w-8 h-8 rounded-lg text-primary border border-primary/20 hover:bg-primary/5 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.guardians.destroy', $guardian) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            title="Hapus"
                                            onclick="return confirm('Hapus data wali {{ addslashes($guardian->name) }}? Semua hubungan dengan santri akan ikut dihapus.')"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg text-red-600 border border-red-200 hover:bg-red-50 transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <span class="material-symbols-outlined text-4xl text-slate-300 block mb-2">family_restroom</span>
                                <p class="text-sm text-[#4c739a] mb-3">Belum ada data wali yang terdaftar.</p>
                                <a href="{{ route('admin.guardians.create') }}"
                                    class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:underline">
                                    <span class="material-symbols-outlined text-[16px]">add</span>
                                    Tambah wali pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guardians->hasPages())
            <div class="px-5 py-4 border-t border-[#e7edf3] dark:border-slate-700">
                {{ $guardians->links() }}
            </div>
        @endif
    </div>

    {{-- ===================== MODAL RESET PASSWORD ===================== --}}
    <div id="resetPasswordModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
        style="background: rgba(0,0,0,0.45); backdrop-filter: blur(4px);">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-md overflow-hidden animate-fade-in">

            {{-- Modal Header --}}
            <div class="flex items-center gap-4 px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-amber-50 dark:bg-amber-900/20">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-800 text-amber-600 dark:text-amber-300">
                    <span class="material-symbols-outlined text-[22px]">lock_reset</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-slate-800 dark:text-white text-base">Reset Password Wali</h3>
                    <p id="resetModalSubtitle" class="text-xs text-slate-500 dark:text-slate-400"></p>
                </div>
                <button onclick="closeResetModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <form id="resetPasswordForm" method="POST" action="">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div class="rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 px-4 py-3 text-sm text-amber-700 dark:text-amber-300 flex items-start gap-2">
                        <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">warning</span>
                        <span>Password lama wali akan diganti. Pastikan Anda memberikan password baru ini kepada wali / santri yang bersangkutan.</span>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                            Password Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-amber-500 transition-colors">
                                <span class="material-symbols-outlined">lock</span>
                            </div>
                            <input type="text" name="new_password" id="newPasswordInput"
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-mono font-medium focus:outline-none focus:border-amber-400 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-amber-400/10 transition-all duration-200"
                                placeholder="Masukkan password baru (min. 6 karakter)"
                                required minlength="6" autocomplete="new-password">
                        </div>
                        <p class="text-xs text-slate-400">Gunakan kombinasi huruf dan angka agar lebih aman.</p>
                    </div>

                    {{-- Quick generate --}}
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="generatePassword()"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined text-[15px]">auto_fix_high</span>
                            Generate Password Otomatis
                        </button>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex gap-3 px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                    <button type="button" onclick="closeResetModal()"
                        class="flex-1 px-4 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-sm hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-[2] px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-md shadow-amber-500/20 hover:shadow-lg hover:shadow-amber-500/30 transition-all duration-200 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openResetModal(guardianId, guardianName, guardianUsername) {
        const modal = document.getElementById('resetPasswordModal');
        const form  = document.getElementById('resetPasswordForm');
        const subtitle = document.getElementById('resetModalSubtitle');

        // Set form action
        form.action = `/admin/guardians/${guardianId}/reset-password`;

        // Set subtitle
        subtitle.textContent = guardianName + ' · @' + guardianUsername;

        // Clear previous input
        document.getElementById('newPasswordInput').value = '';

        modal.classList.remove('hidden');
        setTimeout(() => document.getElementById('newPasswordInput').focus(), 100);
    }

    function closeResetModal() {
        document.getElementById('resetPasswordModal').classList.add('hidden');
    }

    // Close modal when clicking backdrop
    document.getElementById('resetPasswordModal').addEventListener('click', function(e) {
        if (e.target === this) closeResetModal();
    });

    // Generate simple random password
    function generatePassword() {
        const chars = 'abcdefghijkmnpqrstuvwxyz23456789';
        let pass = '';
        for (let i = 0; i < 8; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('newPasswordInput').value = pass;
        document.getElementById('newPasswordInput').type = 'text';
        document.getElementById('newPasswordInput').select();
    }
</script>
@endpush
