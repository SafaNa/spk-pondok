@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="glass-card rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-white/50">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-key mr-3 text-[var(--color-primary-600)]"></i>
                    Ganti Password
                </h3>
            </div>

            <div class="p-6">
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <div class="space-y-4">
                        <div x-data="{ show: false }">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat
                                Ini</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="current_password" id="current_password"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-[var(--color-primary-500)] shadow-sm py-2.5 px-3 pr-10 @error('current_password') border-red-500 @enderror">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" id="password" required
                                    class="w-full rounded-lg border-gray-300 focus:border-[var(--color-primary-500)] shadow-sm py-2.5 px-3 pr-10 @error('password') border-red-500 @enderror">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                    id="password_confirmation" required
                                    class="w-full rounded-lg border-gray-300 focus:border-[var(--color-primary-500)] shadow-sm py-2.5 px-3 pr-10">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-4 space-x-3">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[var(--color-primary-600)] hover:bg-[var(--color-primary-700)] focus:outline-none">
                                <i class="fas fa-save mr-2"></i> Simpan Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection