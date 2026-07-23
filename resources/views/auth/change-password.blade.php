@extends('layouts.app')

@section('title', 'Ganti Password - Santri Admin')
@section('mobile_title', 'Ganti Password')
@section('breadcrumb', 'Ganti Password')

@section('content')
    <!-- Full Width Page Header -->
    <div
        class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 rounded-2xl p-6 border border-primary/20 mb-8">
        <div class="flex flex-col gap-1">
            <h1 class="text-3xl font-bold text-[#0d141b] dark:text-white tracking-tight">Ganti Password</h1>
            <p class="text-[#4c739a] text-base">Pastikan akun Anda tetap aman dengan memperbarui password secara berkala.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Context & Tips -->
        <div class="lg:col-span-1 space-y-4">
            <div>
                <h3 class="text-lg font-bold text-[#0d141b] dark:text-white mb-2">Mengapa perlu diperbarui?</h3>
                <p class="text-[#4c739a] leading-relaxed text-sm">
                    Kami menyarankan Anda menggunakan password unik yang tidak Anda gunakan untuk akun online lainnya. Pembaruan rutin membantu mencegah akses yang tidak sah.
                </p>
            </div>
            <!-- Security Tip Card -->
            <div class="bg-blue-50 dark:bg-slate-800 border border-blue-100 dark:border-slate-700 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary mt-0.5">security</span>
                    <div>
                        <h4 class="text-sm font-semibold text-[#0d141b] dark:text-white">Tips Keamanan</h4>
                        <p class="text-xs text-[#4c739a] mt-1">
                            Jangan pernah membagikan password Anda kepada siapa pun. Administrator pesantren tidak akan pernah meminta password Anda melalui email atau telepon.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: The Form -->
        <div class="lg:col-span-2">
            <!-- Success Alert -->
            @if(session('success'))
                <div
                    class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                        <p class="text-sm text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div
                class="bg-white dark:bg-slate-900 shadow-sm border border-[#e7edf3] dark:border-slate-800 rounded-xl overflow-hidden">
                <form action="{{ route('admin.password.update') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    <!-- Current Password -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white"
                            for="current_password">Password Saat Ini</label>
                        <div class="relative rounded-md shadow-sm">
                            <input
                                class="block w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary sm:text-sm py-3 px-4 pr-10"
                                id="current_password" name="current_password" placeholder="Masukkan password saat ini"
                                type="password" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-[#4c739a] hover:text-primary"
                                onclick="togglePassword('current_password')">
                                <span class="material-symbols-outlined text-[20px]"
                                    id="current_password_icon">visibility</span>
                            </div>
                        </div>
                        @error('current_password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="h-px bg-slate-100 dark:bg-slate-800"></div>

                    <!-- New Password -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white" for="new_password">Password Baru</label>
                        <div class="relative rounded-md shadow-sm">
                            <input
                                class="block w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary sm:text-sm py-3 px-4 pr-10"
                                id="new_password" name="password" placeholder="Masukkan password baru" type="password" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-[#4c739a] hover:text-primary"
                                onclick="togglePassword('new_password')">
                                <span class="material-symbols-outlined text-[20px]" id="new_password_icon">visibility</span>
                            </div>
                        </div>
                        <!-- Password Strength Component -->
                        <x-password-strength input-id="new_password" />
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-[#0d141b] dark:text-white"
                            for="password_confirmation">Konfirmasi Password Baru</label>
                        <div class="relative rounded-md shadow-sm">
                            <input
                                class="block w-full rounded-lg border-[#e7edf3] dark:border-slate-600 bg-white dark:bg-slate-800 text-[#0d141b] dark:text-white focus:border-primary focus:ring-primary sm:text-sm py-3 px-4 pr-10"
                                id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru"
                                type="password" />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-[#4c739a] hover:text-primary"
                                onclick="togglePassword('password_confirmation')">
                                <span class="material-symbols-outlined text-[20px]"
                                    id="password_confirmation_icon">visibility</span>
                            </div>
                        </div>
                    </div>
                    <!-- Footer Actions -->
                    <div
                        class="px-6 py-4 bg-[#f6f7f8] dark:bg-slate-800/50 border-t border-[#e7edf3] dark:border-slate-800 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.dashboard') }}"
                            class="px-4 py-2.5 rounded-lg border border-[#e7edf3] dark:border-slate-600 text-[#0d141b] dark:text-white bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 font-medium text-sm transition-colors">
                            Batal
                        </a>
                        <button
                            class="px-4 py-2.5 rounded-lg border border-transparent bg-primary hover:bg-blue-600 text-white font-medium text-sm shadow-sm transition-colors flex items-center gap-2"
                            type="submit">
                            Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '_icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }


    </script>
@endsection