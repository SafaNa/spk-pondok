@props(['id', 'title'])

<div x-data="{ open: false }" x-show="open" x-on:open-modal.window="if ($event.detail === '{{ $id }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $id }}') open = false" x-on:keydown.escape.window="open = false"
    x-init="$watch('open', value => {
        if (value) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
     })" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" style="display: none;">

    {{-- Backdrop --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>

    {{-- Modal Panel --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-800">

        {{-- Header --}}
        <div
            class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ $title }}</h3>
            <button @click="open = false"
                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Content --}}
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>

<script>
    // helper functions for dispatching events
    if (typeof window.openModal === 'undefined') {
        window.openModal = (id) => {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: id }));
        };
    }
    if (typeof window.closeModal === 'undefined') {
        window.closeModal = (id) => {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: id }));
        };
    }
</script>