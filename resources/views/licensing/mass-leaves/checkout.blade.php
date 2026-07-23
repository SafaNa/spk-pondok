@extends('layouts.app')

@section('title', 'Checkout Liburan Serentak')

@push('styles')
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 3rem;
        border-radius: 0.75rem;
        border-color: #e2e8f0;
        display: flex;
        align-items: center;
    }
    .dark .select2-container--default .select2-selection--single {
        background-color: #0f172a;
        border-color: #334155;
    }
    .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #f8fafc;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="checkoutApp()">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.mass-leaves.show', $mass_leaf->id) }}"
            class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Kasir Checkout Liburan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $mass_leaf->title }}</p>
        </div>
    </div>

    <!-- Scanner / Input Box -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-8">
            <div class="w-20 h-20 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-4xl text-blue-600 dark:text-blue-400">qr_code_scanner</span>
            </div>
            
            <h2 class="text-center text-lg font-bold text-slate-900 dark:text-white mb-6">Scan atau Cari Nama Santri</h2>
            
            <form @submit.prevent="processCheckout" class="max-w-md mx-auto space-y-4">
                <div wire:ignore>
                    <select id="student_id" class="w-full" style="width: 100%;">
                        <option value="">-- Ketik Nama Santri --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->rayon->name ?? '-' }} / {{ $student->room->name ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" 
                    :disabled="isLoading || !selectedStudent"
                    class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="material-symbols-outlined" x-show="!isLoading">check_circle</span>
                    <span class="material-symbols-outlined animate-spin" x-show="isLoading" style="display: none;">progress_activity</span>
                    <span x-text="isLoading ? 'Memproses...' : 'Checkout / ACC Liburan'"></span>
                </button>
            </form>
        </div>
        
        <div class="px-8 py-4 bg-slate-50 dark:bg-slate-700/50 border-t border-slate-200 dark:border-slate-700 text-center text-sm text-slate-500 dark:text-slate-400">
            Sistem otomatis mengecek tanggungan pelanggaran sebelum ACC.
        </div>
    </div>

    <!-- Log Checkout Terbaru -->
    <div x-show="recentCheckouts.length > 0" style="display: none;">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3">Baru saja di-ACC:</h3>
        <div class="space-y-2">
            <template x-for="log in recentCheckouts" :key="log.time">
                <div class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 flex justify-between items-center animate-[slideIn_0.3s_ease-out]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                            <span class="material-symbols-outlined">how_to_reg</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white" x-text="log.name"></p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Berhasil checkout</p>
                        </div>
                    </div>
                    <span class="text-xs font-medium text-slate-400" x-text="log.time"></span>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('checkoutApp', () => ({
            selectedStudent: '',
            isLoading: false,
            recentCheckouts: [],
            
            init() {
                // Initialize Select2
                $('#student_id').select2({
                    placeholder: '-- Ketik Nama Santri --',
                    allowClear: true
                }).on('change', (e) => {
                    this.selectedStudent = e.target.value;
                });
            },
            
            async processCheckout() {
                if (!this.selectedStudent) return;
                
                this.isLoading = true;
                
                try {
                    const response = await fetch("{{ route('admin.mass-leaves.processCheckout', $mass_leaf->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ student_id: this.selectedStudent })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        // Success
                        Swal.fire({
                            icon: 'success',
                            title: 'ACC Berhasil',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Get student name from select options
                        const studentName = $('#student_id option:selected').text().split(' (')[0];
                        
                        // Add to log
                        this.recentCheckouts.unshift({
                            name: studentName,
                            time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                        });
                        
                        // Limit log to 5
                        if(this.recentCheckouts.length > 5) {
                            this.recentCheckouts.pop();
                        }
                        
                        // Reset selection
                        $('#student_id').val(null).trigger('change');
                        this.selectedStudent = '';
                        
                        // Focus back for next scan
                        $('#student_id').select2('open');
                        
                    } else {
                        // Error (e.g. pending violations or already checkout)
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal ACC',
                            text: data.message,
                            confirmButtonColor: '#ef4444'
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal terhubung ke server.'
                    });
                } finally {
                    this.isLoading = false;
                }
            }
        }));
    });
</script>
<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
