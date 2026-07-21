<div id="cropperModal" class="fixed inset-0 z-[9999] items-center justify-center p-4 sm:p-6" style="display:none; background: rgba(15,23,42,0.8); backdrop-filter: blur(4px);">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[95vh] overflow-hidden flex flex-col border border-slate-200 dark:border-slate-700 animate-fade-in relative">
        
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shrink-0">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">crop</span>
                Sesuaikan Foto
            </h3>
            <button type="button" onclick="window.closeCropperModal()" class="text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 p-2 rounded-xl transition-colors">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>

        {{-- Cropper Container --}}
        <div class="p-4 sm:p-6 bg-slate-100 dark:bg-slate-900 flex-1 flex items-center justify-center overflow-hidden min-h-[300px]">
            <div class="w-full h-full max-h-[50vh] mx-auto shadow-inner rounded-xl overflow-hidden bg-black/5 relative flex items-center justify-center">
                <img id="cropperImage" src="" alt="Image to crop" style="max-width:100%; max-height:100%; display:block;">
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="px-6 py-5 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 flex items-center justify-end gap-3">
            <button type="button" onclick="window.closeCropperModal()" class="px-5 py-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                Batal
            </button>
            <button type="button" onclick="window.saveCrop()" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white font-semibold transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">crop</span>
                Potong & Simpan
            </button>
        </div>

    </div>
</div>

<script>
(function() {
    // Hindari registrasi ulang jika script sudah pernah jalan
    if (window._cropperReady) return;
    window._cropperReady = true;

    var cropperInstance = null;
    var currentFileInput = null;

    // SATU listener saja di document level
    document.addEventListener('change', function(e) {
        if (!e.target || !e.target.classList || !e.target.classList.contains('crop-avatar')) return;
        
        var file = e.target.files[0];
        if (!file) return;
        if (e.target.dataset.isCropped === '1') return;
        if (!file.type.startsWith('image/')) return;

        currentFileInput = e.target;

        var reader = new FileReader();
        reader.onload = function(evt) {
            var img = document.getElementById('cropperImage');
            if (!img) return;
            img.src = evt.target.result;
            openModal();
        };
        reader.readAsDataURL(file);
    });

    function openModal() {
        var modal = document.getElementById('cropperModal');
        var img = document.getElementById('cropperImage');
        if (!modal || !img) return;

        // Gunakan inline style, BUKAN class Tailwind, dengan important
        modal.style.setProperty('display', 'flex', 'important');

        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }

        setTimeout(function() {
            cropperInstance = new Cropper(img, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        }, 150);
    }

    window.closeCropperModal = function() {
        var modal = document.getElementById('cropperModal');
        var img = document.getElementById('cropperImage');

        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }
        if (img) img.src = '';
        if (modal) modal.style.display = 'none';
        
        if (currentFileInput) {
            currentFileInput.value = '';
            currentFileInput = null;
        }
    };

    window.saveCrop = function() {
        if (!cropperInstance || !currentFileInput) return;

        var canvas = cropperInstance.getCroppedCanvas({
            width: 500,
            height: 500,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        if (!canvas) return;

        canvas.toBlob(function(blob) {
            if (!blob) return;

            var originalName = (currentFileInput.files[0] && currentFileInput.files[0].name) || 'avatar.png';
            var ext = originalName.split('.').pop();
            var file = new File([blob], 'cropped_avatar.' + ext, { type: blob.type });

            var dt = new DataTransfer();
            dt.items.add(file);

            currentFileInput.dataset.isCropped = '1';
            currentFileInput.files = dt.files;
            currentFileInput.dispatchEvent(new Event('change', { bubbles: true }));

            // Tutup modal tanpa reset input
            var modal = document.getElementById('cropperModal');
            var img = document.getElementById('cropperImage');
            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }
            if (img) img.src = '';
            if (modal) modal.style.display = 'none';
            currentFileInput = null;
        }, 'image/png');
    };
})();
</script>
