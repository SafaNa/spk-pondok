@props(['inputId' => 'password'])

<!-- Password Strength Indicator -->
<div id="strength-bars-{{ $inputId }}" class="flex gap-1 h-1 mt-2">
    <div class="flex-1 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
    <div class="flex-1 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
    <div class="flex-1 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
    <div class="flex-1 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
</div>
<p id="strength-text-{{ $inputId }}" class="text-xs text-slate-500 font-medium mt-1">Masukkan password</p>
<!-- Validation Rules -->
<ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3">
    <li id="rule-length-{{ $inputId }}" class="flex items-center gap-2 text-xs text-[#4c739a]" data-text="Minimal 8 karakter">
        <div class="w-4 h-4 flex items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700">
            <div class="w-1 h-1 bg-[#4c739a] rounded-full"></div>
        </div>
        Minimal 8 karakter
    </li>
    <li id="rule-uppercase-{{ $inputId }}" class="flex items-center gap-2 text-xs text-[#4c739a]" data-text="Minimal satu huruf kapital">
        <div class="w-4 h-4 flex items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700">
            <div class="w-1 h-1 bg-[#4c739a] rounded-full"></div>
        </div>
        Minimal satu huruf kapital
    </li>
    <li id="rule-number-{{ $inputId }}" class="flex items-center gap-2 text-xs text-[#4c739a]" data-text="Minimal satu angka">
        <div class="w-4 h-4 flex items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700">
            <div class="w-1 h-1 bg-[#4c739a] rounded-full"></div>
        </div>
        Minimal satu angka
    </li>
</ul>

@pushOnce('scripts')
<script>
    function setupPasswordStrength(inputId) {
        const passwordInput = document.getElementById(inputId);
        if(!passwordInput) return;
        
        passwordInput.addEventListener('input', function(e) {
            const val = e.target.value;
            
            const hasLength = val.length >= 8;
            const hasUppercase = /[A-Z]/.test(val);
            const hasNumber = /[0-9]/.test(val);
            
            updateRule('rule-length-' + inputId, hasLength);
            updateRule('rule-uppercase-' + inputId, hasUppercase);
            updateRule('rule-number-' + inputId, hasNumber);
            
            let strength = 0;
            if (val.length > 0) strength++; // Base bar for typing
            if (hasLength) strength++;
            if (hasUppercase) strength++;
            if (hasNumber) strength++;
            if (strength > 4) strength = 4;
            
            const bars = document.querySelectorAll('#strength-bars-' + inputId + ' > div');
            const text = document.getElementById('strength-text-' + inputId);
            
            bars.forEach((bar, index) => {
                if (index < strength) {
                    if (strength <= 2) bar.className = 'flex-1 rounded-full bg-yellow-400';
                    else if (strength === 3) bar.className = 'flex-1 rounded-full bg-blue-500';
                    else bar.className = 'flex-1 rounded-full bg-green-500';
                } else {
                    bar.className = 'flex-1 rounded-full bg-slate-200 dark:bg-slate-700';
                }
            });
            
            if (val.length === 0) {
                text.textContent = 'Masukkan password';
                text.className = 'text-xs font-medium mt-1 text-slate-500';
            } else if (hasLength && hasUppercase && hasNumber) {
                text.textContent = 'Password memenuhi syarat';
                text.className = 'text-xs font-medium mt-1 text-green-600 dark:text-green-500';
            } else {
                text.textContent = 'Password belum memenuhi syarat';
                text.className = 'text-xs font-medium mt-1 text-blue-600 dark:text-blue-500';
            }
        });
    }

    function updateRule(id, isValid) {
        const el = document.getElementById(id);
        if (!el) return;
        const text = el.getAttribute('data-text');
        if (isValid) {
            el.className = 'flex items-center gap-2 text-xs text-green-600 dark:text-green-400 font-medium';
            el.innerHTML = '<span class="material-symbols-outlined text-[16px]">check</span> ' + text;
        } else {
            el.className = 'flex items-center gap-2 text-xs text-[#4c739a]';
            el.innerHTML = '<div class="w-4 h-4 flex items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700"><div class="w-1 h-1 bg-[#4c739a] rounded-full"></div></div> ' + text;
        }
    }
</script>
@endPushOnce

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setupPasswordStrength('{{ $inputId }}');
    });
</script>
@endpush
