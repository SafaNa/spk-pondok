@extends('layouts.app')

@section('title', 'Edit Pembayaran SPP')
@section('breadcrumb', 'Edit Pembayaran')
@section('breadcrumb_parent', 'Pembayaran SPP')
@section('breadcrumb_parent_route', 'spp-payments.index')
@section('mobile_title', 'Edit SPP')

@section('content')
    <div class="flex flex-col gap-6 w-full mx-auto pb-10">
        {{-- Main Card --}}
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">
            {{-- Header --}}
            <div
                class="bg-gradient-to-br from-primary/10 via-purple-500/5 to-pink-500/5 px-4 py-6 sm:px-6 sm:py-8 border-b border-primary/10">
                {{-- Back Button --}}
                <a href="{{ route('spp-payments.index') }}"
                    class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors group mb-6">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    <span class="text-sm font-medium">Kembali ke Daftar Pembayaran</span>
                </a>
                
                {{-- Title Section --}}
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-primary/20 text-primary">
                        <span class="material-symbols-outlined text-[32px]">edit_document</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Edit Pembayaran SPP
                            </h1>
                            <div class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold border border-primary/20">
                                {{ $sppPayment->academicYear->name }}
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-base max-w-xl">Perbarui detail pembayaran SPP
                            santri.</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('spp-payments.update', $sppPayment->id) }}" method="POST" class="p-4 sm:p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-8">
                    {{-- SECTION 1: Informasi Santri & Periode --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Santri --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Pilih Santri <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">school</span>
                                    </div>
                                    <select name="student_id" required style="background-image: none;"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                        <option value="">-- Pilih Santri --</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}" data-info="({{ $student->rayon?->name }} - {{ $student->room?->name }})"
                                                {{ old('student_id', $sppPayment->student_id) == $student->id ? 'selected' : '' }}>
                                                {{ $student->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('student_id')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Tahun Ajaran (Hidden) --}}
                            <input type="hidden" name="academic_year_id" value="{{ $sppPayment->academic_year_id }}">

                            {{-- Tanggal Pembayaran --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Tanggal Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">event</span>
                                    </div>
                                    <input type="date" name="payment_date"
                                        value="{{ old('payment_date', $sppPayment->payment_date) }}" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200">
                                </div>
                                @error('payment_date')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Detail Pembayaran --}}
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             {{-- Tahap Pembayaran --}}
                             <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Tahap Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">filter_1</span>
                                    </div>
                                    <select name="stage" required style="background-image: none;"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                        <option value="1" {{ old('stage', $sppPayment->stage) == '1' ? 'selected' : '' }}>Tahap 1</option>
                                        <option value="2" {{ old('stage', $sppPayment->stage) == '2' ? 'selected' : '' }}>Tahap 2</option>
                                        <option value="full" {{ old('stage', $sppPayment->stage) == 'full' ? 'selected' : '' }}>Langsung Lunas (Full)</option>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                        <span class="material-symbols-outlined">expand_more</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Batas Waktu (Info) --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Batas Waktu <span class="text-slate-400 font-normal ml-1">(Otomatis)</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">event_busy</span>
                                    </div>
                                    <input type="text" id="deadline_display" readonly
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 font-medium focus:outline-none cursor-not-allowed"
                                        placeholder="- Pilih Tahap Dahulu -">
                                </div>
                                <p class="text-xs text-slate-500 mt-1" id="deadline_info">Pilih tahap untuk melihat batas waktu pembayaran.</p>
                            </div>
                            {{-- Nominal --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Nominal (Rp) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="font-bold text-sm">Rp</span>
                                    </div>
                                    <input type="text" id="amount_display" required
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white placeholder:text-slate-400 font-medium focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 transition-all duration-200"
                                        placeholder="Contoh: 150.000"
                                        value="{{ old('amount', $sppPayment->amount) ? number_format(old('amount', $sppPayment->amount), 0, ',', '.') : '' }}">
                                    <input type="hidden" name="amount" id="amount" value="{{ old('amount', $sppPayment->amount) }}">
                                </div>
                                @error('amount')
                                    <p class="text-sm text-red-500 flex items-center gap-1 mt-1">
                                        <span class="material-symbols-outlined text-[16px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Status Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">check_circle</span>
                                    </div>
                                    <select name="status" required style="background-image: none;"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-normal focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200 appearance-none">
                                        <option value="paid"
                                            {{ old('status', $sppPayment->status) == 'paid' ? 'selected' : '' }}>Lunas (Paid)
                                        </option>
                                        <option value="pending"
                                            {{ old('status', $sppPayment->status) == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                        <span class="material-symbols-outlined">expand_more</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Late Fee Waiver --}}
                            <div class="space-y-2">
                                <div class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-800/50">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="is_late_fee_waived" id="is_late_fee_waived" value="1" 
                                            class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-slate-300 transition-all checked:border-primary checked:bg-primary hover:shadow-sm focus:ring-2 focus:ring-primary/20 dark:border-slate-600 dark:bg-slate-700 dark:checked:border-primary dark:checked:bg-primary"
                                            {{ old('is_late_fee_waived', $sppPayment->is_late_fee_waived) ? 'checked' : '' }}>
                                        <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity">
                                            <span class="material-symbols-outlined text-sm font-bold">check</span>
                                        </span>
                                    </div>
                                    <label for="is_late_fee_waived" class="cursor-pointer text-sm font-medium text-slate-700 dark:text-slate-300 selection:bg-transparent">
                                        Bebaskan Denda <span class="text-slate-400 font-normal ml-1">(Izin Telat)</span>
                                    </label>
                                </div>
                                <p class="text-xs text-slate-500 ml-1">Jika dicentang, denda tidak akan dikenakan meskipun pembayaran terlambat.</p>
                            </div>

                            {{-- Catatan (Full Width) --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    Catatan <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                                </label>
                                <div class="relative group">
                                    <div
                                        class="absolute top-3.5 left-4 pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined">notes</span>
                                    </div>
                                    <textarea name="note" rows="3"
                                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 placeholder:text-slate-400 font-normal resize-none focus:outline-none focus:border-primary/60 focus:ring-2 focus:ring-primary/20 transition-all duration-200"
                                        placeholder="Tambahkan catatan jika diperlukan...">{{ old('note', $sppPayment->note) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 mt-10 pt-8 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('spp-payments.index') }}"
                        class="order-2 sm:order-1 flex-1 px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 font-bold text-center hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="order-1 sm:order-2 flex-[2] px-8 py-4 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            // Initialize Select2 with Custom Template
            $('select[name="student_id"]').select2({
                placeholder: '-- Pilih Santri --',
                allowClear: true,
                width: '100%',
                dropdownCssClass: 'select2-premium-dropdown',
                containerCssClass: 'select2-premium-container',
                templateResult: formatStudent,
                templateSelection: formatStudent
            });

            function formatStudent(student) {
                if (!student.id) {
                    return student.text;
                }
                
                var $student = $(
                    '<span>' + student.text + ' <span class="text-slate-400 text-xs font-normal ml-1">' + ($(student.element).data('info') || '') + '</span></span>'
                );
                return $student;
            }

            // Auto-fill amount based on Academic Year
            const amountInput = $('#amount');
            const amountDisplay = $('#amount_display');
            
            // Format number to Indonesian currency (Ribuan)
            function formatRupiah(angka) {
                if (!angka) return '';
                var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }

            // Sync display input changes to hidden input
            amountDisplay.on('keyup', function(e) {
                var displayVal = $(this).val();
                
                // Format display with dots
                $(this).val(formatRupiah(displayVal));
                
                // Set hidden input with raw number (remove dots)
                var rawVal = $(this).val().replace(/\./g, '');
                amountInput.val(rawVal);
            });
            
            // Note: On edit, we don't auto-run anything on load 
            // because we strictly resolve to the existing saved amount

            // Deadlines Data based on the Payment's Academic Year
            const deadlines = {
                1: "{{ $sppPayment->academicYear->stage1_deadline ? \Carbon\Carbon::parse($sppPayment->academicYear->stage1_deadline)->isoFormat('D MMMM Y') : '-' }}",
                2: "{{ $sppPayment->academicYear->stage2_deadline ? \Carbon\Carbon::parse($sppPayment->academicYear->stage2_deadline)->isoFormat('D MMMM Y') : '-' }}",
                full: "{{ $sppPayment->academicYear->stage1_deadline ? \Carbon\Carbon::parse($sppPayment->academicYear->stage1_deadline)->isoFormat('D MMMM Y') : '-' }}"
            };

            // SPP Amount
            const fullAmount = {{ $sppPayment->academicYear->spp_amount }};

            // Handle Stage Change
            $('select[name="stage"]').on('change', function() {
                const stage = $(this).val();
                const deadline = deadlines[stage] || '-';
                $('#deadline_display').val(deadline);
                
                // Determine Amount (Only on change, not on init to preserve edits)
                // Actually, if user changes stage, they likely want the amount to update.
                // But we must be careful not to overwrite custom input if they just want to see deadline.
                // For simplicity/UX in this system: Update it. User can edit back if needed.
                let amount = 0;
                if (stage == 'full') {
                    amount = fullAmount; // Full Payment = Total SPP
                } else if (stage == '1' || stage == '2') {
                    amount = fullAmount / 2; // Half Payment
                }

                if (amount > 0) {
                    amountInput.val(amount);
                    amountDisplay.val(formatRupiah(amount));
                }

                if (deadline !== '-') {
                    $('#deadline_info').text(`Jika melewati ${deadline}, denda Rp 500 akan otomatis dicatat.`);
                } else {
                    $('#deadline_info').text('Tidak ada batas waktu untuk tahap ini.');
                }
            }); 
            // Trigger change only to set deadline info, not amount (to preserve saved amount)
             const initialStage = $('select[name="stage"]').val();
             const initialDeadline = deadlines[initialStage] || '-';
             $('#deadline_display').val(initialDeadline);
        });
    </script>

    <style>
        /* Base Container */
        .select2-container--default .select2-selection--single {
            height: 54px !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 1rem 0 3rem !important; /* pl-12 equivalent */
            transition: all 0.2s;
        }

        /* Dark Mode */
        .dark .select2-container--default .select2-selection--single {
            background-color: #1e293b !important; /* bg-slate-800 */
            border-color: #475569 !important; /* border-slate-600 */
        }

        /* Focus State */
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: rgba(99, 102, 241, 0.6) !important; /* border-primary/60 */
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important; /* ring-2 ring-primary/20 */
            outline: none !important;
        }

        /* Text Styling */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #334155 !important; /* text-slate-700 */
            line-height: normal !important;
            padding: 0 !important;
            font-size: 1rem !important; /* text-base */
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e2e8f0 !important; /* text-slate-200 */
        }

        /* Placeholder */
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important; /* text-slate-400 */
        }

        /* Arrow/Chevron */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 52px !important;
            position: absolute !important;
            top: 0 !important;
            right: 0.75rem !important;
            width: 20px !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #64748b transparent transparent transparent !important;
            border-style: solid !important;
            border-width: 5px 4px 0 4px !important;
            height: 0 !important;
            left: 50% !important;
            margin-left: -4px !important;
            margin-top: -2px !important;
            position: absolute !important;
            top: 50% !important;
            width: 0 !important;
        }

        /* Dropdown Menu */
        .select2-dropdown {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important; /* rounded-xl */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            margin-top: 4px !important;
            overflow: hidden !important;
            z-index: 50 !important;
        }

        .dark .select2-dropdown {
            background-color: #1e293b !important;
            border-color: #475569 !important;
        }

        /* Search Input in Dropdown */
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.5rem !important; /* rounded-lg */
            padding: 0.6rem 1rem !important;
            margin: 0.5rem !important;
            width: calc(100% - 1rem) !important;
            outline: none !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 1px #6366f1 !important;
        }

        .dark .select2-search--dropdown .select2-search__field {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }

        /* Options */
        .select2-results__option {
            padding: 0.75rem 1rem !important;
            font-size: 0.95rem !important;
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: rgba(99, 102, 241, 0.1) !important; /* primary/10 */
            color: #4f46e5 !important; /* primary-600 */
        }

        .dark .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: rgba(99, 102, 241, 0.2) !important;
            color: #818cf8 !important;
        }

        .select2-container--default .select2-results__option--selected {
            background-color: #e0e7ff !important;
            color: #4338ca !important;
            font-weight: 600 !important;
        }

        .dark .select2-container--default .select2-results__option--selected {
            background-color: #312e81 !important;
            color: #c7d2fe !important;
        }
    </style>
@endsection

